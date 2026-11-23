<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Akun pelanggan: login, daftar, profil */
class Akun extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_pelanggan', 'M_ongkir'));
    }

    private function wajib_login()
    {
        if (!$this->pelanggan_id()) redirect('akun/login?redirect=akun');
    }

    /** hanya izinkan redirect ke path lokal */
    private function tujuan()
    {
        $r = (string) $this->input->get_post('redirect', TRUE);
        return preg_match('#^[a-z0-9/_\-]*$#i', $r) && $r !== '' ? $r : 'pesanan';
    }

    private function set_login($p)
    {
        $this->session->sess_regenerate(TRUE);
        $this->session->set_userdata(array('id_pelanggan' => $p->id_pelanggan, 'nama_pelanggan' => $p->nama, 'email_pelanggan' => $p->email));
    }

    public function login()
    {
        if ($this->pelanggan_id()) redirect($this->tujuan());
        if ($this->input->method() === 'post') {
            $p = $this->M_pelanggan->get_by_email($this->input->post('email'));
            if ($p && password_verify((string) $this->input->post('password'), $p->password)) {
                // keranjang tamu tetap terbawa setelah login
                $keranjang = $this->session->userdata('keranjang');
                $this->set_login($p);
                if ($keranjang) $this->session->set_userdata('keranjang', $keranjang);
                $this->session->set_flashdata('success', 'Selamat datang kembali, ' . html_escape($p->nama) . '!');
                redirect($this->tujuan());
            }
            $this->session->set_flashdata('error', 'Email atau password salah.');
            redirect('akun/login?redirect=' . rawurlencode($this->tujuan()));
        }
        $data['title']    = 'Masuk';
        $data['redirect'] = $this->tujuan();
        $this->render('front/akun_login', $data);
    }

    public function daftar()
    {
        if ($this->pelanggan_id()) redirect('akun');
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama', 'Nama', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[100]');
            $this->form_validation->set_rules('telpon', 'No. HP', 'required|trim|numeric|min_length[9]|max_length[15]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('konfirmasi', 'Konfirmasi Password', 'required|matches[password]');
            if (!$this->form_validation->run()) {
                $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
                redirect('akun/daftar?redirect=' . rawurlencode($this->tujuan()));
            }
            if ($this->M_pelanggan->email_exists($this->input->post('email'))) {
                $this->session->set_flashdata('error', 'Email sudah terdaftar. Silakan masuk.');
                redirect('akun/login');
            }
            $this->M_pelanggan->insert(array(
                'nama'     => $this->input->post('nama', TRUE),
                'email'    => strtolower($this->input->post('email', TRUE)),
                'telpon'   => $this->input->post('telpon', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            ));
            $keranjang = $this->session->userdata('keranjang');
            $this->set_login($this->M_pelanggan->get_by_id($this->db->insert_id()));
            if ($keranjang) $this->session->set_userdata('keranjang', $keranjang);
            $this->session->set_flashdata('success', 'Pendaftaran berhasil. Selamat berbelanja!');
            redirect($this->tujuan() === 'pesanan' ? 'produk' : $this->tujuan());
        }
        $data['title']    = 'Daftar Akun';
        $data['redirect'] = $this->tujuan();
        $this->render('front/akun_daftar', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata(array('id_pelanggan', 'nama_pelanggan', 'email_pelanggan'));
        $this->session->set_flashdata('success', 'Anda telah keluar.');
        redirect();
    }

    public function index()
    {
        $this->wajib_login();
        $data['title']  = 'Profil Saya';
        $data['p']      = $this->M_pelanggan->get_by_id($this->pelanggan_id());
        $data['kota']   = $this->M_ongkir->get_all();
        $data['menu']   = 'profil';
        $this->render('front/akun_profil', $data);
    }

    public function simpan()
    {
        $this->wajib_login();
        $this->only_post();
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('telpon', 'No. HP', 'required|trim|numeric|min_length[9]|max_length[15]');
        $this->form_validation->set_rules('kode_pos', 'Kode Pos', 'trim|numeric|max_length[10]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
            redirect('akun');
        }
        $d = array(
            'nama'      => $this->input->post('nama', TRUE),
            'telpon'    => $this->input->post('telpon', TRUE),
            'alamat'    => $this->input->post('alamat', TRUE),
            'id_ongkir' => $this->input->post('id_ongkir') ? (int) $this->input->post('id_ongkir') : NULL,
            'kode_pos'  => $this->input->post('kode_pos', TRUE),
        );
        $this->M_pelanggan->update($this->pelanggan_id(), $d);
        $this->session->set_userdata('nama_pelanggan', $d['nama']);
        $this->session->set_flashdata('success', 'Profil berhasil disimpan.');
        redirect('akun');
    }

    public function password()
    {
        $this->wajib_login();
        $this->only_post();
        $p = $this->M_pelanggan->get_by_id($this->pelanggan_id());
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('konfirmasi', 'Konfirmasi', 'required|matches[password_baru]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
        } elseif (!password_verify($this->input->post('password_lama'), $p->password)) {
            $this->session->set_flashdata('error', 'Password lama salah.');
        } else {
            $this->M_pelanggan->update($p->id_pelanggan, array('password' => password_hash($this->input->post('password_baru'), PASSWORD_DEFAULT)));
            $this->session->set_flashdata('success', 'Password berhasil diubah.');
        }
        redirect('akun');
    }
}
