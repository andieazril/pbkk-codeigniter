<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function index()
    {
        $this->load->model(array('M_jadwal', 'M_krs', 'M_mahasiswa'));
        $ta    = tahun_akademik_aktif();
        $id_ta = $ta ? $ta['id_tahun_akademik'] : NULL;

        $hari_map = array(1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
        $data['title']    = 'Dashboard';
        $data['subtitle'] = 'Ringkasan data akademik';
        $data['hari_ini'] = $hari_map[(int) date('N')];
        $data['jadwal_hari_ini'] = array();

        if ($this->is_admin()) {
            $data['jml'] = array(
                'mahasiswa'  => $this->db->where('status', 'Aktif')->count_all_results('tbl_mahasiswa'),
                'dosen'      => $this->db->count_all('tbl_dosen'),
                'prodi'      => $this->db->count_all('tbl_prodi'),
                'matakuliah' => $this->db->count_all('tbl_matakuliah'),
            );
            $data['per_prodi'] = $this->db->select('p.jenjang, p.nama_prodi, COUNT(m.nim) AS total')
                ->from('tbl_prodi p')->join('tbl_mahasiswa m', "m.kd_prodi = p.kd_prodi AND m.status = 'Aktif'", 'left')
                ->group_by(array('p.kd_prodi', 'p.jenjang', 'p.nama_prodi'))->order_by('p.kd_prodi')->get()->result();
            // progres KRS semester aktif
            $data['krs'] = array('isi' => 0, 'acc' => 0, 'jadwal' => $id_ta ? $this->M_jadwal->count_tahun($id_ta) : 0);
            if ($id_ta) {
                $sub = $this->db->select('id_jadwal')->where('id_tahun_akademik', $id_ta)->get_compiled_select('tbl_jadwal');
                $data['krs']['isi'] = $this->db->query("SELECT COUNT(DISTINCT nim) n FROM tbl_krs WHERE id_jadwal IN ($sub)")->row()->n;
                $data['krs']['acc'] = $this->db->query("SELECT COUNT(DISTINCT nim) n FROM tbl_krs WHERE id_jadwal IN ($sub) AND nim NOT IN
                    (SELECT nim FROM tbl_krs WHERE status = 'Diajukan' AND id_jadwal IN ($sub))")->row()->n;
            }
        } elseif ($this->is_dosen() && $id_ta) {
            $all = $this->M_jadwal->get_filter($id_ta, NULL, $this->my_id_dosen() ?: -1);
            $data['jml_kelas'] = count($all);
            $data['jadwal_hari_ini'] = array_filter($all, function ($j) use ($data) { return $j->hari === $data['hari_ini']; });
            $data['jml_bimbingan'] = $this->db->where(array('id_dosen_wali' => $this->my_id_dosen(), 'status' => 'Aktif'))->count_all_results('tbl_mahasiswa');
            $data['menunggu_acc'] = 0;
            foreach ($this->M_mahasiswa->get_all(array('id_dosen_wali' => $this->my_id_dosen() ?: -1, 'status' => 'Aktif')) as $m) {
                if ($this->M_krs->status($m->nim, $id_ta) === 'Diajukan') $data['menunggu_acc']++;
            }
        } elseif ($this->is_mhs()) {
            $nim = $this->my_nim();
            $data['ipk'] = hitung_ip($this->M_krs->transkrip($nim));
            $data['krs_status'] = $id_ta ? $this->M_krs->status($nim, $id_ta) : NULL;
            $data['krs_sks']    = $id_ta ? $this->M_krs->total_sks($nim, $id_ta) : 0;
            if ($id_ta) {
                $data['jadwal_hari_ini'] = array_filter($this->M_krs->get_krs($nim, $id_ta), function ($k) use ($data) { return $k->hari === $data['hari_ini']; });
            }
        }
        $this->render('dashboard/index', $data);
    }
}
