<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    public function index()
    {
        $this->load->model(array('M_pesanan'));
        $hari_ini = date('Y-m-d');
        $awal_bln = date('Y-m-01');
        $data['title']    = 'Dashboard';
        $data['subtitle'] = 'Ringkasan toko';
        $data['bulan_ini'] = $this->M_pesanan->ringkasan($awal_bln, $hari_ini);
        $data['hari_ini']  = $this->M_pesanan->ringkasan($hari_ini, $hari_ini);
        $data['status']    = $this->M_pesanan->jumlah_per_status();
        $data['grafik']    = $this->M_pesanan->harian(date('Y-m-d', strtotime('-29 days')), $hari_ini);
        $data['terlaris']  = $this->M_pesanan->produk_terlaris($awal_bln, $hari_ini, 5);
        $data['terbaru']   = array_slice($this->M_pesanan->daftar_admin(), 0, 6);
        $data['jml_pelanggan'] = $this->db->count_all('tbl_pelanggan');
        $data['stok_menipis']  = $this->db->where('stok <=', 5)->where('is_aktif', 'y')->order_by('stok')->get('tbl_produk')->result();
        $this->render('admin/dashboard', $data);
    }
}
