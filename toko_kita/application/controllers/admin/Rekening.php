<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening extends Crud_Controller
{
    protected $model  = 'M_rekening';
    protected $pk     = 'id_rekening';
    protected $title  = 'Rekening Bank';
    protected $fields = array(
        'nama_bank'   => array('label' => 'Nama Bank', 'rules' => 'required|max_length[50]'),
        'no_rekening' => array('label' => 'No. Rekening', 'rules' => 'required|numeric|max_length[30]'),
        'atas_nama'   => array('label' => 'Atas Nama', 'rules' => 'required|max_length[100]'),
        'is_aktif'    => array('label' => 'Status', 'type' => 'select', 'rules' => 'required|in_list[y,n]', 'options' => array('y' => 'Aktif', 'n' => 'Nonaktif'), 'default' => 'y'),
    );
    protected $used_in = array('tbl_pembayaran' => 'id_rekening');
}
