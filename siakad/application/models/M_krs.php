<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * KRS (Kartu Rencana Studi) + Nilai + KHS + Transkrip
 * Nilai disimpan langsung pada baris KRS mahasiswa.
 */
class M_krs extends MY_Model
{
    protected $table = 'tbl_krs';
    protected $pk    = 'id_krs';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_tahunakademik');   // dipakai untuk urutan semester
    }

    private function base()
    {
        return $this->db->select('k.*, jd.kd_mk, jd.kelas, jd.hari, jd.jam_mulai, jd.jam_selesai, jd.id_tahun_akademik, jd.id_dosen,
                mk.nama_mk, mk.sks, mk.semester AS smt_mk, mk.jenis, d.nama_dosen, ru.nama_ruangan', FALSE)
            ->from('tbl_krs k')
            ->join('tbl_jadwal jd', 'jd.id_jadwal = k.id_jadwal')
            ->join('tbl_matakuliah mk', 'mk.kd_mk = jd.kd_mk')
            ->join('tbl_dosen d', 'd.id_dosen = jd.id_dosen', 'left')
            ->join('tbl_ruangan ru', 'ru.kd_ruangan = jd.kd_ruangan', 'left');
    }

    /** KRS mahasiswa pada satu tahun akademik */
    public function get_krs($nim, $id_ta)
    {
        return $this->base()->where('k.nim', $nim)->where('jd.id_tahun_akademik', $id_ta)
            ->order_by("FIELD(jd.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')", '', FALSE)
            ->order_by('jd.jam_mulai')->get()->result();
    }

    public function total_sks($nim, $id_ta)
    {
        $r = $this->db->select('COALESCE(SUM(mk.sks),0) AS total', FALSE)
            ->from('tbl_krs k')->join('tbl_jadwal jd', 'jd.id_jadwal = k.id_jadwal')
            ->join('tbl_matakuliah mk', 'mk.kd_mk = jd.kd_mk')
            ->where('k.nim', $nim)->where('jd.id_tahun_akademik', $id_ta)->get()->row();
        return (int) $r->total;
    }

    /** status KRS: NULL (kosong) | Diajukan | Disetujui */
    public function status($nim, $id_ta)
    {
        $rows = $this->get_krs($nim, $id_ta);
        if (!$rows) return NULL;
        foreach ($rows as $r) {
            if ($r->status === 'Diajukan') return 'Diajukan';
        }
        return 'Disetujui';
    }

    /** IPS semester terakhir sebelum tahun akademik $id_ta (untuk batas SKS) */
    public function ips_sebelumnya($nim, $id_ta)
    {
        $u = M_tahunakademik::urutan_sql('t');
        $cur = $this->db->select("$u AS urut", FALSE)->from('tbl_tahun_akademik t')->where('t.id_tahun_akademik', $id_ta)->get()->row();
        if (!$cur) return NULL;
        $prev = $this->db->select("t.id_tahun_akademik", FALSE)
            ->from('tbl_krs k')->join('tbl_jadwal jd', 'jd.id_jadwal = k.id_jadwal')
            ->join('tbl_tahun_akademik t', 't.id_tahun_akademik = jd.id_tahun_akademik')
            ->where('k.nim', $nim)->where("$u <", $cur->urut)->where('k.nilai_huruf IS NOT NULL', NULL, FALSE)
            ->order_by($u, 'DESC', FALSE)->limit(1)->get()->row();
        if (!$prev) return NULL;
        $ip = hitung_ip($this->get_krs($nim, $prev->id_tahun_akademik));
        return $ip['ip'];
    }

    /** jadwal yang dapat diambil mahasiswa (prodi yang sama) */
    public function jadwal_tersedia($mhs, $id_ta)
    {
        return $this->db->select('jd.*, mk.nama_mk, mk.sks, mk.semester AS smt_mk, mk.jenis, d.nama_dosen, ru.nama_ruangan,
                (SELECT COUNT(*) FROM tbl_krs x WHERE x.id_jadwal = jd.id_jadwal) AS terisi,
                (SELECT COUNT(*) FROM tbl_krs y WHERE y.id_jadwal = jd.id_jadwal AND y.nim = ' . $this->db->escape($mhs->nim) . ') AS diambil', FALSE)
            ->from('tbl_jadwal jd')
            ->join('tbl_matakuliah mk', 'mk.kd_mk = jd.kd_mk')
            ->join('tbl_dosen d', 'd.id_dosen = jd.id_dosen', 'left')
            ->join('tbl_ruangan ru', 'ru.kd_ruangan = jd.kd_ruangan', 'left')
            ->where('jd.id_tahun_akademik', $id_ta)
            ->where('mk.kd_prodi', $mhs->kd_prodi)
            ->order_by('mk.semester, mk.kd_mk, jd.kelas')->get()->result();
    }

    /**
     * Tambah mata kuliah ke KRS dengan validasi lengkap
     * @return true|string pesan error
     */
    public function ambil($mhs, $id_jadwal, $id_ta)
    {
        $j = $this->db->select('jd.*, mk.sks, mk.nama_mk, mk.kd_prodi')->from('tbl_jadwal jd')
            ->join('tbl_matakuliah mk', 'mk.kd_mk = jd.kd_mk')
            ->where('jd.id_jadwal', (int) $id_jadwal)->get()->row();
        if (!$j || (int) $j->id_tahun_akademik !== (int) $id_ta) return 'Jadwal tidak ditemukan pada tahun akademik aktif.';
        if ($j->kd_prodi !== $mhs->kd_prodi) return 'Mata kuliah bukan dari program studi mahasiswa.';

        $krs = $this->get_krs($mhs->nim, $id_ta);
        foreach ($krs as $k) {
            if ($k->kd_mk === $j->kd_mk) return $j->nama_mk . ' sudah diambil.';
            if ($k->hari === $j->hari && $k->jam_mulai < $j->jam_selesai && $k->jam_selesai > $j->jam_mulai) {
                return 'Jadwal ' . $j->nama_mk . ' bentrok dengan ' . $k->nama_mk . ' (' . $k->hari . ').';
            }
        }
        if ($this->db->where('id_jadwal', $j->id_jadwal)->count_all_results('tbl_krs') >= (int) $j->kuota) {
            return 'Kuota kelas ' . $j->nama_mk . ' sudah penuh.';
        }
        $maks = maks_sks($this->ips_sebelumnya($mhs->nim, $id_ta));
        if ($this->total_sks($mhs->nim, $id_ta) + (int) $j->sks > $maks) {
            return 'Total SKS melebihi batas maksimal (' . $maks . ' SKS).';
        }
        $this->db->insert('tbl_krs', array('nim' => $mhs->nim, 'id_jadwal' => $j->id_jadwal, 'status' => 'Diajukan'));
        // perubahan KRS -> perlu persetujuan ulang dosen wali
        $this->set_status($mhs->nim, $id_ta, 'Diajukan');
        return TRUE;
    }

    public function set_status($nim, $id_ta, $status)
    {
        $ids = $this->db->select('id_jadwal')->where('id_tahun_akademik', $id_ta)->get_compiled_select('tbl_jadwal');
        return $this->db->where('nim', $nim)->where("id_jadwal IN ($ids)", NULL, FALSE)
            ->update('tbl_krs', array('status' => $status));
    }

    /** Peserta kuliah pada satu jadwal (untuk input nilai) */
    public function peserta($id_jadwal, $hanya_disetujui = TRUE)
    {
        $this->db->select('k.*, m.nama')->from('tbl_krs k')->join('tbl_mahasiswa m', 'm.nim = k.nim')
            ->where('k.id_jadwal', $id_jadwal);
        if ($hanya_disetujui) $this->db->where('k.status', 'Disetujui');
        return $this->db->order_by('k.nim')->get()->result();
    }

    public function simpan_nilai($id_krs, $tugas, $uts, $uas)
    {
        $akhir = hitung_nilai_akhir($tugas, $uts, $uas);
        list($huruf, $bobot) = nilai_huruf($akhir);
        return $this->db->where('id_krs', $id_krs)->update('tbl_krs', array(
            'nilai_tugas' => $tugas, 'nilai_uts' => $uts, 'nilai_uas' => $uas,
            'nilai_akhir' => $akhir, 'nilai_huruf' => $huruf, 'bobot' => $bobot,
        ));
    }

    /** Semua nilai mahasiswa s/d tahun akademik tertentu (untuk IPK) */
    public function nilai_sampai($nim, $id_ta = NULL)
    {
        $this->base()->join('tbl_tahun_akademik t', 't.id_tahun_akademik = jd.id_tahun_akademik')
            ->where('k.nim', $nim)->where('k.nilai_huruf IS NOT NULL', NULL, FALSE);
        if ($id_ta) {
            $u = M_tahunakademik::urutan_sql('t');
            $sub = $this->db->escape_str((int) $id_ta);
            $this->db->where("$u <= (SELECT " . M_tahunakademik::urutan_sql('t2') . " FROM tbl_tahun_akademik t2 WHERE t2.id_tahun_akademik = $sub)", NULL, FALSE);
        }
        return $this->db->order_by('mk.semester, mk.kd_mk')->get()->result();
    }

    /**
     * Transkrip: setiap mata kuliah dihitung sekali, memakai nilai terbaik bila diulang
     */
    public function transkrip($nim, $id_ta = NULL)
    {
        $best = array();
        foreach ($this->nilai_sampai($nim, $id_ta) as $r) {
            if (!isset($best[$r->kd_mk]) || (float) $r->bobot > (float) $best[$r->kd_mk]->bobot) {
                $best[$r->kd_mk] = $r;
            }
        }
        return array_values($best);
    }

    /** Tahun akademik yang pernah diikuti mahasiswa (untuk pilihan KHS) */
    public function ta_mahasiswa($nim)
    {
        $u = M_tahunakademik::urutan_sql('t');
        $out = array();
        foreach ($this->db->select('DISTINCT t.id_tahun_akademik, t.tahun_akademik, t.semester, ' . $u . ' AS urut', FALSE)
            ->from('tbl_krs k')->join('tbl_jadwal jd', 'jd.id_jadwal = k.id_jadwal')
            ->join('tbl_tahun_akademik t', 't.id_tahun_akademik = jd.id_tahun_akademik')
            ->where('k.nim', $nim)->order_by('urut', 'ASC')->get()->result() as $r) {
            $out[$r->id_tahun_akademik] = $r->tahun_akademik . ' ' . $r->semester;
        }
        return $out;
    }
}
