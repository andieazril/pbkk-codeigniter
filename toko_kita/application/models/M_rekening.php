<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_rekening extends MY_Model
{
    protected $table    = 'tbl_rekening';
    protected $pk       = 'id_rekening';
    protected $order_by = 'id_rekening ASC';
}
