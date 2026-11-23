<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggan extends Admin_Controller
{
    public function index()
    {
        $this->load->model('M_pelanggan');
        $data['title'] = 'Pelanggan';
        $data['rows']  = $this->M_pelanggan->get_all();
        $this->render('admin/pelanggan/index', $data);
    }

    public function detail($id = NULL)
    {
        $this->load->model(array('M_pelanggan', 'M_pesanan'));
        $p = $this->db->select('pl.*, o.kota')->from('tbl_pelanggan pl')->join('tbl_ongkir o', 'o.id_ongkir = pl.id_ongkir', 'left')
            ->where('pl.id_pelanggan', (int) $id)->get()->row();
        if (!$p) show_404();
        $data['title']   = 'Detail Pelanggan';
        $data['subtitle'] = $p->nama;
        $data['p']       = $p;
        $data['pesanan'] = $this->M_pesanan->milik_pelanggan($p->id_pelanggan);
        $this->render('admin/pelanggan/detail', $data);
    }
}
