<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Front_Controller
{
    public function index()
    {
        $this->load->model('M_produk');
        $data['terbaru']  = $this->M_produk->terbaru(8);
        $data['terlaris'] = $this->M_produk->terlaris(4);
        $this->render('front/home', $data);
    }

    public function cara_belanja()
    {
        $data['title']    = 'Cara Belanja';
        $data['rekening'] = $this->db->get_where('tbl_rekening', array('is_aktif' => 'y'))->result();
        $this->render('front/cara_belanja', $data);
    }
}
