<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends Member_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_pelanggan', 'M_ongkir', 'M_pesanan'));
    }

    public function index()
    {
        $items = $this->keranjang->items();
        if (!$items) {
            $this->session->set_flashdata('warning', 'Keranjang belanja masih kosong.');
            redirect('keranjang');
        }
        $ringkas = $this->keranjang->ringkasan($items);
        if ($ringkas['stok_kurang']) {
            $this->session->set_flashdata('error', 'Ada produk yang jumlahnya melebihi stok. Silakan sesuaikan.');
            redirect('keranjang');
        }
        $data['title']   = 'Checkout';
        $data['items']   = $items;
        $data['ringkas'] = $ringkas;
        $data['p']       = $this->M_pelanggan->get_by_id($this->pelanggan_id());
        $data['kota']    = $this->M_ongkir->get_all();
        $this->render('front/checkout', $data);
    }

    public function proses()
    {
        $this->only_post();
        $this->form_validation->set_rules('nama_penerima', 'Nama Penerima', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('telpon', 'No. HP', 'required|trim|numeric|min_length[9]|max_length[15]');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim|min_length[10]');
        $this->form_validation->set_rules('id_ongkir', 'Kota Tujuan', 'required|integer');
        $this->form_validation->set_rules('kode_pos', 'Kode Pos', 'trim|numeric|max_length[10]');
        $this->form_validation->set_rules('catatan', 'Catatan', 'trim|max_length[255]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
            redirect('checkout');
        }
        $ongkir = $this->M_ongkir->get_by_id((int) $this->input->post('id_ongkir'));
        if (!$ongkir) {
            $this->session->set_flashdata('error', 'Kota tujuan tidak valid.');
            redirect('checkout');
        }
        $form = array();
        foreach (array('nama_penerima', 'telpon', 'alamat', 'kode_pos', 'catatan') as $f) {
            $form[$f] = $this->input->post($f, TRUE);
        }
        $hasil = $this->M_pesanan->buat($this->pelanggan_id(), $form, $ongkir);
        if (!$hasil['ok']) {
            $this->session->set_flashdata('error', $hasil['pesan']);
            redirect('keranjang');
        }
        // simpan alamat sebagai alamat default pelanggan bila belum ada
        $p = $this->M_pelanggan->get_by_id($this->pelanggan_id());
        if (!$p->alamat) {
            $this->M_pelanggan->update($p->id_pelanggan, array('alamat' => $form['alamat'], 'id_ongkir' => $ongkir->id_ongkir, 'kode_pos' => $form['kode_pos']));
        }
        $this->session->set_flashdata('success', 'Pesanan <b>' . $hasil['kode'] . '</b> berhasil dibuat. Silakan lakukan pembayaran.');
        redirect('pesanan/detail/' . $hasil['kode']);
    }
}
