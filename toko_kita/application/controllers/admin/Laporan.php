<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends Admin_Controller
{
    private function periode()
    {
        $dari   = $this->input->get('dari', TRUE);
        $sampai = $this->input->get('sampai', TRUE);
        $ok = function ($d) { return $d && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d); };
        $dari   = $ok($dari) ? $dari : date('Y-m-01');
        $sampai = $ok($sampai) ? $sampai : date('Y-m-d');
        if ($dari > $sampai) list($dari, $sampai) = array($sampai, $dari);
        return array($dari, $sampai);
    }

    private function data()
    {
        $this->load->model('M_pesanan');
        list($dari, $sampai) = $this->periode();
        return array(
            'dari'     => $dari,
            'sampai'   => $sampai,
            'ringkas'  => $this->M_pesanan->ringkasan($dari, $sampai),
            'rows'     => array_values(array_filter($this->M_pesanan->daftar_admin(array('dari' => $dari, 'sampai' => $sampai)),
                            function ($r) { return in_array($r->status, M_pesanan::STATUS_TERJUAL); })),
            'terlaris' => $this->M_pesanan->produk_terlaris($dari, $sampai, 10),
        );
    }

    public function index()
    {
        $data = $this->data();
        $data['title'] = 'Laporan Penjualan';
        $data['subtitle'] = 'Pesanan berstatus Diproses, Dikirim, dan Selesai';
        $this->render('admin/laporan/index', $data);
    }

    public function cetak()
    {
        $this->load->view('admin/laporan/cetak', $this->data());
    }
}
