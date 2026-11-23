<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Pesanan Saya (pelanggan) */
class Pesanan extends Member_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_pesanan', 'M_rekening'));
    }

    private function ambil($kode)
    {
        $ps = $this->M_pesanan->by_kode($kode, $this->pelanggan_id());
        if (!$ps) show_404();
        return $ps;
    }

    public function index()
    {
        $data['title']   = 'Pesanan Saya';
        $data['menu']    = 'pesanan';
        $data['pesanan'] = $this->M_pesanan->milik_pelanggan($this->pelanggan_id());
        $this->render('front/pesanan_list', $data);
    }

    public function detail($kode = NULL)
    {
        $ps = $this->ambil($kode);
        $bayar = $this->M_pesanan->pembayaran($ps->id_pesanan);
        $data['title']    = 'Pesanan ' . $ps->kode_pesanan;
        $data['menu']     = 'pesanan';
        $data['ps']       = $ps;
        $data['items']    = $this->M_pesanan->detail($ps->id_pesanan);
        $data['bayar']    = $bayar;
        $data['rekening'] = $this->db->get_where('tbl_rekening', array('is_aktif' => 'y'))->result();
        // form upload tampil bila belum bayar, atau pembayaran terakhir ditolak
        $data['boleh_bayar'] = $ps->status === 'Menunggu Pembayaran';
        $this->render('front/pesanan_detail', $data);
    }

    public function bayar($kode = NULL)
    {
        $this->only_post();
        $ps = $this->ambil($kode);
        if ($ps->status !== 'Menunggu Pembayaran') {
            $this->session->set_flashdata('warning', 'Pesanan ini tidak sedang menunggu pembayaran.');
            redirect('pesanan/detail/' . $ps->kode_pesanan);
        }
        $this->form_validation->set_rules('id_rekening', 'Rekening Tujuan', 'required|integer');
        $this->form_validation->set_rules('nama_pengirim', 'Nama Pemilik Rekening', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('bank_pengirim', 'Bank Pengirim', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('jumlah', 'Jumlah Transfer', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('tanggal_transfer', 'Tanggal Transfer', 'required');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
            redirect('pesanan/detail/' . $ps->kode_pesanan);
        }
        if (empty($_FILES['bukti']['name'])) {
            $this->session->set_flashdata('error', 'Bukti transfer wajib diunggah.');
            redirect('pesanan/detail/' . $ps->kode_pesanan);
        }
        $this->load->library('upload', array(
            'upload_path'   => FCPATH . 'uploads/bukti/',
            'allowed_types' => 'jpg|jpeg|png|pdf',
            'max_size'      => 2048,
            'file_name'     => 'bukti-' . $ps->kode_pesanan . '-' . time(),
        ));
        if (!$this->upload->do_upload('bukti')) {
            $this->session->set_flashdata('error', $this->upload->display_errors(' ', ' '));
            redirect('pesanan/detail/' . $ps->kode_pesanan);
        }
        $this->db->trans_start();
        $this->db->insert('tbl_pembayaran', array(
            'id_pesanan'       => $ps->id_pesanan,
            'id_rekening'      => (int) $this->input->post('id_rekening'),
            'nama_pengirim'    => $this->input->post('nama_pengirim', TRUE),
            'bank_pengirim'    => $this->input->post('bank_pengirim', TRUE),
            'jumlah'           => (int) $this->input->post('jumlah'),
            'tanggal_transfer' => $this->input->post('tanggal_transfer', TRUE),
            'bukti'            => $this->upload->data('file_name'),
        ));
        $this->M_pesanan->set_status($ps, 'Menunggu Verifikasi');
        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Konfirmasi pembayaran terkirim. Admin akan memverifikasi dalam 1x24 jam.');
        redirect('pesanan/detail/' . $ps->kode_pesanan);
    }

    public function batal($kode = NULL)
    {
        $this->only_post();
        $ps = $this->ambil($kode);
        if ($ps->status !== 'Menunggu Pembayaran') {
            $this->session->set_flashdata('error', 'Pesanan hanya dapat dibatalkan sebelum pembayaran dikonfirmasi.');
        } else {
            $this->M_pesanan->set_status($ps, 'Dibatalkan');
            $this->session->set_flashdata('success', 'Pesanan dibatalkan.');
        }
        redirect('pesanan/detail/' . $ps->kode_pesanan);
    }

    public function selesai($kode = NULL)
    {
        $this->only_post();
        $ps = $this->ambil($kode);
        if ($ps->status === 'Dikirim') {
            $this->M_pesanan->set_status($ps, 'Selesai');
            $this->session->set_flashdata('success', 'Terima kasih! Pesanan ditandai selesai.');
        }
        redirect('pesanan/detail/' . $ps->kode_pesanan);
    }

    public function invoice($kode = NULL)
    {
        $ps = $this->ambil($kode);
        $this->load->view('invoice', array('ps' => $ps, 'items' => $this->M_pesanan->detail($ps->id_pesanan)));
    }
}
