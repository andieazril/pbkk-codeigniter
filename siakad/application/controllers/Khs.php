<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kartu Hasil Studi per semester (IPS & IPK) */
class Khs extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_krs', 'M_mahasiswa', 'M_tahunakademik'));
    }

    private function data($nim, $id_ta)
    {
        $mhs = $this->M_mahasiswa->get_detail($nim);
        $ta  = $this->M_tahunakademik->get_by_id($id_ta);
        $rows = $this->M_krs->get_krs($nim, $id_ta);
        usort($rows, function ($a, $b) { return strcmp($a->kd_mk, $b->kd_mk); });
        return array(
            'mhs'  => $mhs,
            'ta'   => $ta,
            'smt'  => $this->M_mahasiswa->semester_ke($mhs->angkatan, $ta),
            'rows' => $rows,
            'ips'  => hitung_ip($rows),
            'ipk'  => hitung_ip($this->M_krs->transkrip($nim, $id_ta)),
            'sks_diambil' => array_sum(array_map(function ($r) { return $r->sks; }, $rows)),
        );
    }

    public function index()
    {
        $nim = $this->is_mhs() ? $this->my_nim() : (string) $this->input->get('nim', TRUE);
        $data['title']    = 'Kartu Hasil Studi';
        $data['opsi_mhs'] = $this->is_mhs() ? array() : $this->opsi_mahasiswa();
        $data['nim']      = $nim;
        $data['ta_opt']   = array();
        $data['id_ta']    = NULL;
        $data['khs']      = NULL;
        if ($nim && $this->boleh_akses_nim($nim) && $this->M_mahasiswa->exists($nim)) {
            $data['ta_opt'] = $this->M_krs->ta_mahasiswa($nim);
            $id_ta = (int) $this->input->get('id_tahun_akademik');
            if (!isset($data['ta_opt'][$id_ta])) {
                // default: semester terakhir yang sudah memiliki nilai, jika tidak ada pakai semester terakhir
                $id_ta = NULL;
                foreach (array_reverse(array_keys($data['ta_opt'])) as $k) {
                    if (hitung_ip($this->M_krs->get_krs($nim, $k))['sks'] > 0) { $id_ta = $k; break; }
                }
                if (!$id_ta && $data['ta_opt']) {
                    $keys  = array_keys($data['ta_opt']);
                    $id_ta = end($keys);
                }
            }
            $data['id_ta'] = $id_ta;
            if ($id_ta) $data['khs'] = $this->data($nim, $id_ta);
        }
        $this->render('khs/index', $data);
    }

    public function cetak($nim = NULL, $id_ta = NULL)
    {
        if (!$nim || !$this->boleh_akses_nim($nim) || !$this->M_mahasiswa->exists($nim)) show_error('Akses ditolak.', 403, 'Akses Ditolak');
        if (!$this->M_tahunakademik->exists((int) $id_ta)) show_404();
        $this->load->view('khs/cetak', $this->data($nim, (int) $id_ta));
    }
}
