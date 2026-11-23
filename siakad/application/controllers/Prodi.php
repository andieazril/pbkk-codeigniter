<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prodi extends Crud_Controller
{
    protected $model   = 'M_prodi';
    protected $pk      = 'kd_prodi';
    protected $pk_auto = FALSE;
    protected $title   = 'Program Studi';
    protected $fields  = array(
        'nama_prodi'   => array('label' => 'Nama Program Studi', 'rules' => 'required|max_length[100]'),
        'jenjang'      => array('label' => 'Jenjang', 'type' => 'select', 'rules' => 'required|in_list[D3,D4,S1,S2,S3]',
                                'options' => array('D3' => 'D3', 'D4' => 'D4', 'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3'), 'default' => 'S1'),
        'fakultas'     => array('label' => 'Fakultas', 'rules' => 'max_length[100]'),
        'ketua_prodi'  => array('label' => 'Ketua Program Studi', 'rules' => 'max_length[100]'),
        'nidn_kaprodi' => array('label' => 'NIDN Kaprodi', 'rules' => 'max_length[30]', 'list' => FALSE),
    );
    protected $used_in = array('tbl_mahasiswa' => 'kd_prodi', 'tbl_matakuliah' => 'kd_prodi', 'tbl_dosen' => 'kd_prodi');
}
