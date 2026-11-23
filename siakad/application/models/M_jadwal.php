<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal extends MY_Model
{
    protected $table = 'tbl_jadwal';
    protected $pk    = 'id_jadwal';

    private function base_query()
    {
        return $this->db->select('jd.*, mk.nama_mk, mk.sks, mk.semester AS smt_mk, mk.kd_prodi, p.nama_prodi, d.nama_dosen, d.nidn, ru.nama_ruangan, t.tahun_akademik, t.semester,
                (SELECT COUNT(*) FROM tbl_krs k WHERE k.id_jadwal = jd.id_jadwal) AS terisi', FALSE)
            ->from('tbl_jadwal jd')
            ->join('tbl_matakuliah mk', 'mk.kd_mk = jd.kd_mk', 'left')
            ->join('tbl_prodi p', 'p.kd_prodi = mk.kd_prodi', 'left')
            ->join('tbl_dosen d', 'd.id_dosen = jd.id_dosen', 'left')
            ->join('tbl_ruangan ru', 'ru.kd_ruangan = jd.kd_ruangan', 'left')
            ->join('tbl_tahun_akademik t', 't.id_tahun_akademik = jd.id_tahun_akademik', 'left');
    }

    private function order()
    {
        $this->db->order_by("FIELD(jd.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')", '', FALSE)
            ->order_by('jd.jam_mulai');
    }

    public function get_filter($id_tahun_akademik, $kd_prodi = NULL, $id_dosen = NULL)
    {
        $this->base_query()->where('jd.id_tahun_akademik', $id_tahun_akademik);
        if ($kd_prodi) $this->db->where('mk.kd_prodi', $kd_prodi);
        if ($id_dosen) $this->db->where('jd.id_dosen', $id_dosen);
        $this->order();
        return $this->db->get()->result();
    }

    public function get_detail($id)
    {
        return $this->base_query()->where('jd.id_jadwal', $id)->get()->row();
    }

    /**
     * Cek bentrok: ruangan / dosen yang sama pada hari & jam yang beririsan
     * @return string|false pesan bentrok
     */
    public function cek_bentrok($d, $except_id = NULL)
    {
        $checks = array('kd_ruangan' => 'Ruangan sudah dipakai', 'id_dosen' => 'Dosen sudah mengajar');
        foreach ($checks as $field => $msg) {
            $this->db->from('tbl_jadwal')
                ->where('id_tahun_akademik', $d['id_tahun_akademik'])
                ->where('hari', $d['hari'])
                ->where($field, $d[$field])
                ->where('jam_mulai <', $d['jam_selesai'])
                ->where('jam_selesai >', $d['jam_mulai']);
            if ($except_id) $this->db->where('id_jadwal !=', $except_id);
            if ($this->db->count_all_results() > 0) {
                return $msg . ' pada hari ' . $d['hari'] . ' jam tersebut.';
            }
        }
        // kelas yang sama untuk mata kuliah yang sama tidak boleh dobel
        $this->db->where(array('id_tahun_akademik' => $d['id_tahun_akademik'], 'kd_mk' => $d['kd_mk'], 'kelas' => $d['kelas']));
        if ($except_id) $this->db->where('id_jadwal !=', $except_id);
        if ($this->db->count_all_results('tbl_jadwal') > 0) {
            return 'Mata kuliah ini sudah memiliki kelas ' . $d['kelas'] . ' pada tahun akademik tersebut.';
        }
        return FALSE;
    }

    public function count_tahun($id_tahun_akademik)
    {
        return $this->db->where('id_tahun_akademik', $id_tahun_akademik)->count_all_results($this->table);
    }

    public function jumlah_peserta($id)
    {
        return $this->db->where('id_jadwal', $id)->count_all_results('tbl_krs');
    }
}
