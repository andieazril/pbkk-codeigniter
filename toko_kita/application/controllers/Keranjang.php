<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keranjang extends Front_Controller
{
    public function index()
    {
        $items = $this->keranjang->items();
        $data['title']    = 'Keranjang Belanja';
        $data['items']    = $items;
        $data['ringkas']  = $this->keranjang->ringkasan($items);
        $this->render('front/keranjang', $data);
    }

    public function tambah()
    {
        $this->only_post();
        $id  = (int) $this->input->post('id_produk');
        $ok  = $this->keranjang->tambah($id, (int) $this->input->post('qty') ?: 1);
        if ($ok !== TRUE) {
            $this->session->set_flashdata('error', $ok);
            $slug = $this->db->select('slug')->get_where('tbl_produk', array('id_produk' => $id))->row();
            redirect($slug ? 'p/' . $slug->slug : 'produk');
        }
        if ($this->input->post('aksi') === 'beli') {
            redirect('checkout');
        }
        $this->session->set_flashdata('success', 'Produk ditambahkan ke keranjang. <a href="' . site_url('keranjang') . '"><b>Lihat keranjang &rarr;</b></a>');
        redirect($this->agent_referrer());
    }

    public function ubah()
    {
        $this->only_post();
        foreach ((array) $this->input->post('qty') as $id => $qty) {
            $ok = $this->keranjang->ubah((int) $id, (int) $qty);
            if ($ok !== TRUE) $this->session->set_flashdata('error', $ok);
        }
        redirect('keranjang');
    }

    public function hapus($id = NULL)
    {
        $this->only_post();
        $this->keranjang->hapus((int) $id);
        $this->session->set_flashdata('success', 'Produk dihapus dari keranjang.');
        redirect('keranjang');
    }

    private function agent_referrer()
    {
        $this->load->library('user_agent');
        $ref = $this->agent->referrer();
        return ($ref && strpos($ref, base_url()) === 0) ? $ref : 'keranjang';
    }
}
