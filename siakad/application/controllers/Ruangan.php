<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ruangan extends Crud_Controller
{
    protected $model   = 'M_ruangan';
    protected $pk      = 'kd_ruangan';
    protected $pk_auto = FALSE;
    protected $title   = 'Data Ruangan';
    protected $fields  = array(
        'nama_ruangan' => array('label' => 'Nama Ruangan', 'rules' => 'required|max_length[50]'),
        'kapasitas'    => array('label' => 'Kapasitas', 'type' => 'number', 'rules' => 'required|integer', 'default' => 40),
    );
    protected $used_in = array('tbl_jadwal' => 'kd_ruangan');
}
