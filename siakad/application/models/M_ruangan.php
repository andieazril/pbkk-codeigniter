<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ruangan extends MY_Model
{
    protected $table    = 'tbl_ruangan';
    protected $pk       = 'kd_ruangan';
    protected $order_by = 'kd_ruangan ASC';
}
