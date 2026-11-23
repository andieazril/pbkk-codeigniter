<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ongkir extends MY_Model
{
    protected $table    = 'tbl_ongkir';
    protected $pk       = 'id_ongkir';
    protected $order_by = 'kota ASC';
}
