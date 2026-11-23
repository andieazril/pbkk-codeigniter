<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends Admin_Controller
{
    public function index()
    {
        $data['title'] = 'Ganti Password';
        $this->render('admin/profil', $data);
    }

    public function save()
    {
        $this->only_post();
        $a = $this->db->get_where('tbl_admin', array('id_admin' => $this->session->userdata('admin_id')))->row();
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('konfirmasi', 'Konfirmasi', 'required|matches[password_baru]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
        } elseif (!password_verify($this->input->post('password_lama'), $a->password)) {
            $this->session->set_flashdata('error', 'Password lama salah.');
        } else {
            $this->db->where('id_admin', $a->id_admin)->update('tbl_admin', array('password' => password_hash($this->input->post('password_baru'), PASSWORD_DEFAULT)));
            $this->session->set_flashdata('success', 'Password berhasil diubah.');
        }
        redirect('admin/profil');
    }
}
