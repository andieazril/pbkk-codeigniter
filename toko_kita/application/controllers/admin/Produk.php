<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_produk', 'M_kategori'));
    }

    public function index()
    {
        $f = array('id_kategori' => $this->input->get('id_kategori', TRUE), 'stok' => $this->input->get('stok', TRUE));
        $data['title']    = 'Produk';
        $data['f']        = $f;
        $data['rows']     = $this->M_produk->get_all($f);
        $data['kategori'] = $this->M_kategori->dropdown();
        $this->render('admin/produk/index', $data);
    }

    public function add()
    {
        $data['title']    = 'Tambah Produk';
        $data['row']      = NULL;
        $data['kategori'] = $this->M_kategori->dropdown();
        $this->render('admin/produk/form', $data);
    }

    public function edit($id = NULL)
    {
        $data['row'] = $this->M_produk->get_by_id((int) $id);
        if (!$data['row']) show_404();
        $data['title']    = 'Edit Produk';
        $data['kategori'] = $this->M_kategori->dropdown();
        $this->render('admin/produk/form', $data);
    }

    public function save()
    {
        $this->only_post();
        $id   = (int) $this->input->post('id_produk');
        $back = $id ? 'admin/produk/edit/' . $id : 'admin/produk/add';

        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required|integer');
        $this->form_validation->set_rules('harga', 'Harga', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('harga_coret', 'Harga Coret', 'integer');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('berat', 'Berat', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('is_aktif', 'Status', 'required|in_list[y,n]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
            redirect($back);
        }
        $d = array(
            'nama_produk' => $this->input->post('nama_produk', TRUE),
            'id_kategori' => (int) $this->input->post('id_kategori'),
            'deskripsi'   => $this->input->post('deskripsi', TRUE),
            'harga'       => (int) $this->input->post('harga'),
            'harga_coret' => $this->input->post('harga_coret') ? (int) $this->input->post('harga_coret') : NULL,
            'stok'        => (int) $this->input->post('stok'),
            'berat'       => (int) $this->input->post('berat'),
            'is_aktif'    => $this->input->post('is_aktif'),
        );
        if ($d['harga_coret'] !== NULL && $d['harga_coret'] <= $d['harga']) {
            $this->session->set_flashdata('error', 'Harga coret harus lebih besar dari harga jual (atau kosongkan).');
            redirect($back);
        }
        $d['slug'] = $this->M_produk->slug_unik($d['nama_produk'], $id ?: NULL);

        if (!empty($_FILES['gambar']['name'])) {
            $this->load->library('upload', array(
                'upload_path'   => FCPATH . 'uploads/produk/',
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => 2048,
                'file_name'     => $d['slug'] . '-' . time(),
            ));
            if (!$this->upload->do_upload('gambar')) {
                $this->session->set_flashdata('error', $this->upload->display_errors(' ', ' '));
                redirect($back);
            }
            $d['gambar'] = $this->upload->data('file_name');
            if ($id && ($lama = $this->M_produk->get_by_id($id)) && $lama->gambar && is_file(FCPATH . 'uploads/produk/' . $lama->gambar)) {
                @unlink(FCPATH . 'uploads/produk/' . $lama->gambar);
            }
        }

        if ($id) {
            $this->M_produk->update($id, $d);
            $this->session->set_flashdata('success', 'Produk berhasil diperbarui.');
        } else {
            $this->M_produk->insert($d);
            $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
        }
        redirect('admin/produk');
    }

    public function delete($id = NULL)
    {
        $this->only_post();
        $p = $this->M_produk->get_by_id((int) $id);
        if (!$p) show_404();
        if ($this->db->where('id_produk', $p->id_produk)->count_all_results('tbl_pesanan_detail') > 0) {
            // produk yang sudah pernah dipesan cukup dinonaktifkan agar riwayat tetap utuh
            $this->M_produk->update($p->id_produk, array('is_aktif' => 'n'));
            $this->session->set_flashdata('warning', 'Produk sudah pernah dipesan, sehingga hanya dinonaktifkan (tidak tampil di toko).');
        } else {
            $this->M_produk->delete($p->id_produk);
            if ($p->gambar && is_file(FCPATH . 'uploads/produk/' . $p->gambar)) @unlink(FCPATH . 'uploads/produk/' . $p->gambar);
            $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        }
        redirect('admin/produk');
    }
}
