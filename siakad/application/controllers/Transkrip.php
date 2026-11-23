<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Transkrip nilai kumulatif (nilai terbaik tiap mata kuliah) */
class Transkrip extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_krs', 'M_mahasiswa'));
    }

    private function data($nim)
    {
        $rows = $this->M_krs->transkrip($nim);
        usort($rows, function ($a, $b) { return $a->smt_mk <=> $b->smt_mk ?: strcmp($a->kd_mk, $b->kd_mk); });
        $ipk = hitung_ip($rows);
        return array(
            'mhs'     => $this->M_mahasiswa->get_detail($nim),
            'rows'    => $rows,
            'ipk'     => $ipk,
            'predikat' => self::predikat_lulus($ipk['ip']),
        );
    }

    public static function predikat_lulus($ipk)
    {
        if ($ipk >= 3.51) return 'Dengan Pujian (Cum Laude)';
        if ($ipk >= 3.01) return 'Sangat Memuaskan';
        if ($ipk >= 2.76) return 'Memuaskan';
        if ($ipk > 0)     return 'Cukup';
        return '-';
    }

    public function index()
    {
        $nim = $this->is_mhs() ? $this->my_nim() : (string) $this->input->get('nim', TRUE);
        $data['title']    = 'Transkrip Nilai';
        $data['opsi_mhs'] = $this->is_mhs() ? array() : $this->opsi_mahasiswa();
        $data['nim']      = $nim;
        $data['tr']       = ($nim && $this->boleh_akses_nim($nim) && $this->M_mahasiswa->exists($nim)) ? $this->data($nim) : NULL;
        $this->render('transkrip/index', $data);
    }

    public function cetak($nim = NULL)
    {
        if (!$nim || !$this->boleh_akses_nim($nim) || !$this->M_mahasiswa->exists($nim)) show_error('Akses ditolak.', 403, 'Akses Ditolak');
        $this->load->view('transkrip/cetak', $this->data($nim));
    }
}
