<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function index()
    {
        if ($this->session->userdata('admin_login')) redirect('admin/dashboard');
        $this->load->view('admin/auth/login');
    }

    public function login()
    {
        $this->only_post();
        $a = $this->db->get_where('tbl_admin', array('username' => (string) $this->input->post('username', TRUE)))->row();
        if (!$a || !password_verify((string) $this->input->post('password'), $a->password)) {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('admin/auth');
        }
        $this->session->sess_regenerate(TRUE);
        $this->session->set_userdata(array('admin_login' => TRUE, 'admin_id' => $a->id_admin, 'admin_nama' => $a->nama, 'admin_username' => $a->username));
        redirect('admin/dashboard');
    }

    public function logout()
    {
        $this->session->unset_userdata(array('admin_login', 'admin_id', 'admin_nama', 'admin_username'));
        redirect('admin/auth');
    }
}
