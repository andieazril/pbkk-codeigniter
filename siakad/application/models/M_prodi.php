<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_prodi extends MY_Model
{
    protected $table    = 'tbl_prodi';
    protected $pk       = 'kd_prodi';
    protected $order_by = 'kd_prodi ASC';

    public function dropdown()
    {
        $out = array();
        foreach ($this->get_all() as $r) {
            $out[$r->kd_prodi] = $r->jenjang . ' ' . $r->nama_prodi;
        }
        return $out;
    }
}
