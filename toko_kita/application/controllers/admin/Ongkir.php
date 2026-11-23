<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ongkir extends Crud_Controller
{
    protected $model  = 'M_ongkir';
    protected $pk     = 'id_ongkir';
    protected $title  = 'Ongkos Kirim per Kota';
    protected $fields = array(
        'kota'         => array('label' => 'Kota Tujuan', 'rules' => 'required|max_length[50]'),
        'tarif_per_kg' => array('label' => 'Tarif per Kg (Rp)', 'type' => 'number', 'rules' => 'required|integer|greater_than_equal_to[0]', 'format' => 'rupiah'),
        'estimasi'     => array('label' => 'Estimasi', 'rules' => 'max_length[30]', 'default' => '2-3 hari'),
    );
    protected $used_in = array('tbl_pelanggan' => 'id_ongkir');
}
