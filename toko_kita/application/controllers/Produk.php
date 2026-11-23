<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends Front_Controller
{
    const PER_HALAMAN = 12;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_produk', 'M_kategori'));
    }

    public function index()
    {
        $f = array(
            'kategori' => (string) $this->input->get('kategori', TRUE),
            'q'        => trim((string) $this->input->get('q', TRUE)),
            'urut'     => (string) $this->input->get('urut', TRUE),
        );
        $hal   = max(1, (int) $this->input->get('hal'));
        $total = $this->M_produk->katalog_count($f);
        $jml_hal = max(1, (int) ceil($total / self::PER_HALAMAN));
        $hal   = min($hal, $jml_hal);

        $kat = $f['kategori'] ? $this->db->get_where('tbl_kategori', array('slug' => $f['kategori']))->row() : NULL;
        $data['title']    = $f['q'] ? 'Hasil pencarian "' . $f['q'] . '"' : ($kat ? $kat->nama_kategori : 'Semua Produk');
        $data['f']        = $f;
        $data['kat']      = $kat;
        $data['total']    = $total;
        $data['hal']      = $hal;
        $data['jml_hal']  = $jml_hal;
        $data['produk']   = $this->M_produk->katalog($f, self::PER_HALAMAN, ($hal - 1) * self::PER_HALAMAN);
        $data['kategori'] = $this->M_kategori->get_all();
        $this->render('front/produk_list', $data);
    }

    public function detail($slug = NULL)
    {
        $p = $this->M_produk->by_slug($slug);
        if (!$p) show_404();
        $data['title']     = $p->nama_produk;
        $data['meta_desc'] = mb_substr(strip_tags($p->deskripsi), 0, 150);
        $data['p']         = $p;
        $data['terkait']   = $this->M_produk->terkait($p, 4);
        $this->render('front/produk_detail', $data);
    }
}
