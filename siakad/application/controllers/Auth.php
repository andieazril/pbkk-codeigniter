<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_user');
    }

    public function index()
    {
        if ($this->session->userdata('login')) {
            redirect('dashboard');
        }
        $this->load->view('auth/login');
    }

    public function login()
    {
        if ($this->input->method() !== 'post') {
            redirect('auth');
        }
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', 'Username dan password wajib diisi.');
            redirect('auth');
        }

        $user = $this->M_user->get_by_username($this->input->post('username', TRUE));
        if (!$user || !password_verify($this->input->post('password'), $user->password)) {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth');
        }

        $this->session->sess_regenerate(TRUE);
        $this->session->set_userdata(array(
            'login'         => TRUE,
            'id_user'       => $user->id_user,
            'username'      => $user->username,
            'nama_lengkap'  => $user->nama_lengkap,
            'id_level_user' => $user->id_level_user,
            'nama_level'    => $user->nama_level,
            'id_dosen'      => $user->id_dosen,
            'nim'           => $user->nim,
        ));
        redirect('dashboard');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
