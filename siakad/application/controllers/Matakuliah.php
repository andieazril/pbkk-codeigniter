<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matakuliah extends Crud_Controller
{
    protected $model   = 'M_matakuliah';
    protected $pk      = 'kd_mk';
    protected $pk_auto = FALSE;
    protected $title   = 'Mata Kuliah';
    protected $used_in = array('tbl_jadwal' => 'kd_mk');

    protected function get_fields()
    {
        $this->load->model('M_prodi');
        $smt = array();
        for ($i = 1; $i <= 8; $i++) $smt[$i] = 'Semester ' . $i;
        return array(
            'nama_mk'  => array('label' => 'Nama Mata Kuliah', 'rules' => 'required|max_length[100]'),
            'sks'      => array('label' => 'SKS', 'type' => 'number', 'rules' => 'required|integer|greater_than[0]|less_than_equal_to[6]', 'default' => 3),
            'semester' => array('label' => 'Semester', 'type' => 'select', 'rules' => 'required|integer', 'options' => $smt),
            'jenis'    => array('label' => 'Jenis', 'type' => 'select', 'rules' => 'required|in_list[Wajib,Pilihan]',
                                'options' => array('Wajib' => 'Wajib', 'Pilihan' => 'Pilihan'), 'default' => 'Wajib'),
            'kd_prodi' => array('label' => 'Program Studi', 'type' => 'select', 'rules' => 'required', 'options' => $this->M_prodi->dropdown(), 'display' => 'nama_prodi'),
        );
    }
}
