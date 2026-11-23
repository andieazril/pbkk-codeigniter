<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_pesanan');
    }

    private function ambil($id)
    {
        $ps = $this->db->select('ps.*, pl.nama AS nama_pelanggan, pl.email')->from('tbl_pesanan ps')
            ->join('tbl_pelanggan pl', 'pl.id_pelanggan = ps.id_pelanggan', 'left')
            ->where('ps.id_pesanan', (int) $id)->get()->row();
        if (!$ps) show_404();
        return $ps;
    }

    public function index()
    {
        $f = array(
            'status' => (string) $this->input->get('status', TRUE),
            'dari'   => (string) $this->input->get('dari', TRUE),
            'sampai' => (string) $this->input->get('sampai', TRUE),
        );
        $data['title']  = 'Pesanan';
        $data['f']      = $f;
        $data['rows']   = $this->M_pesanan->daftar_admin($f);
        $data['jumlah'] = $this->M_pesanan->jumlah_per_status();
        $this->render('admin/pesanan/index', $data);
    }

    public function detail($id = NULL)
    {
        $ps = $this->ambil($id);
        $data['title']    = 'Detail Pesanan';
        $data['subtitle'] = $ps->kode_pesanan;
        $data['ps']       = $ps;
        $data['items']    = $this->M_pesanan->detail($ps->id_pesanan);
        $data['bayar']    = $this->M_pesanan->pembayaran($ps->id_pesanan);
        $this->render('admin/pesanan/detail', $data);
    }

    public function verifikasi($id_pembayaran = NULL)
    {
        $this->only_post();
        $b = $this->db->get_where('tbl_pembayaran', array('id_pembayaran' => (int) $id_pembayaran))->row();
        if (!$b) show_404();
        $ps = $this->ambil($b->id_pesanan);
        if ($b->status !== 'Menunggu' || $ps->status !== 'Menunggu Verifikasi') {
            $this->session->set_flashdata('warning', 'Pembayaran ini sudah diproses sebelumnya.');
            redirect('admin/pesanan/detail/' . $ps->id_pesanan);
        }
        $aksi = $this->input->post('aksi');
        $this->db->trans_start();
        if ($aksi === 'terima') {
            $this->db->where('id_pembayaran', $b->id_pembayaran)->update('tbl_pembayaran', array('status' => 'Diterima'));
            $this->M_pesanan->set_status($ps, 'Diproses');
            $msg = 'Pembayaran diterima. Pesanan masuk tahap Diproses.';
        } else {
            $this->db->where('id_pembayaran', $b->id_pembayaran)->update('tbl_pembayaran', array(
                'status' => 'Ditolak', 'catatan_admin' => $this->input->post('catatan', TRUE) ?: 'Bukti transfer tidak valid')
            );
            // pelanggan dapat mengunggah ulang bukti
            $this->M_pesanan->set_status($ps, 'Menunggu Pembayaran');
            $msg = 'Pembayaran ditolak. Pelanggan diminta mengunggah ulang bukti transfer.';
        }
        $this->db->trans_complete();
        $this->session->set_flashdata('success', $msg);
        redirect('admin/pesanan/detail/' . $ps->id_pesanan);
    }

    public function kirim($id = NULL)
    {
        $this->only_post();
        $ps   = $this->ambil($id);
        $resi = trim((string) $this->input->post('no_resi', TRUE));
        if ($ps->status !== 'Diproses' && $ps->status !== 'Dikirim') {
            $this->session->set_flashdata('error', 'Pesanan belum dibayar / belum diproses.');
        } elseif ($resi === '') {
            $this->session->set_flashdata('error', 'Nomor resi wajib diisi.');
        } else {
            $this->M_pesanan->set_status($ps, 'Dikirim', $resi);
            $this->session->set_flashdata('success', 'Pesanan ditandai dikirim dengan resi ' . html_escape($resi) . '.');
        }
        redirect('admin/pesanan/detail/' . $ps->id_pesanan);
    }

    public function selesai($id = NULL)
    {
        $this->only_post();
        $ps = $this->ambil($id);
        if ($ps->status === 'Dikirim') {
            $this->M_pesanan->set_status($ps, 'Selesai');
            $this->session->set_flashdata('success', 'Pesanan ditandai selesai.');
        }
        redirect('admin/pesanan/detail/' . $ps->id_pesanan);
    }

    public function batal($id = NULL)
    {
        $this->only_post();
        $ps = $this->ambil($id);
        if (in_array($ps->status, array('Selesai', 'Dibatalkan', 'Dikirim'))) {
            $this->session->set_flashdata('error', 'Pesanan dengan status ' . $ps->status . ' tidak dapat dibatalkan.');
        } else {
            $this->M_pesanan->set_status($ps, 'Dibatalkan');
            $this->session->set_flashdata('success', 'Pesanan dibatalkan dan stok produk dikembalikan.');
        }
        redirect('admin/pesanan/detail/' . $ps->id_pesanan);
    }

    public function invoice($id = NULL)
    {
        $ps = $this->ambil($id);
        $this->load->view('invoice', array('ps' => $ps, 'items' => $this->M_pesanan->detail($ps->id_pesanan)));
    }
}
