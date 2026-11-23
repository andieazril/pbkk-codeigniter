<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen extends Crud_Controller
{
    protected $model   = 'M_dosen';
    protected $pk      = 'id_dosen';
    protected $title   = 'Data Dosen';
    protected $used_in = array('tbl_jadwal' => 'id_dosen', 'tbl_mahasiswa' => 'id_dosen_wali', 'tbl_user' => 'id_dosen');

    protected function get_fields()
    {
        $this->load->model('M_prodi');
        return array(
            'nidn'          => array('label' => 'NIDN', 'rules' => 'max_length[20]'),
            'nama_dosen'    => array('label' => 'Nama Dosen (dengan gelar)', 'rules' => 'required|max_length[100]'),
            'gender'        => array('label' => 'L/P', 'type' => 'select', 'rules' => 'required|in_list[L,P]', 'options' => array('L' => 'Laki-laki', 'P' => 'Perempuan')),
            'kd_prodi'      => array('label' => 'Homebase Prodi', 'type' => 'select', 'rules' => 'required', 'options' => $this->M_prodi->dropdown(), 'display' => 'nama_prodi'),
            'tempat_lahir'  => array('label' => 'Tempat Lahir', 'list' => FALSE),
            'tanggal_lahir' => array('label' => 'Tanggal Lahir', 'type' => 'date', 'list' => FALSE),
            'alamat'        => array('label' => 'Alamat', 'type' => 'textarea', 'list' => FALSE),
            'telpon'        => array('label' => 'No. Telepon', 'rules' => 'max_length[20]'),
            'email'         => array('label' => 'Email', 'type' => 'email', 'rules' => 'valid_email|max_length[100]', 'list' => FALSE),
        );
    }

    public function _row_actions($row)
    {
        return '<a href="' . site_url('mahasiswa?id_dosen_wali=' . $row->id_dosen) . '" class="btn btn-xs btn-info" title="Mahasiswa bimbingan"><i class="fa fa-users"></i></a>';
    }
}
