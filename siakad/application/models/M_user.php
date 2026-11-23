<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends MY_Model
{
    protected $table    = 'tbl_user';
    protected $pk       = 'id_user';
    protected $order_by = 'id_user ASC';

    public function get_all()
    {
        return $this->db->select('u.*, l.nama_level')
            ->from('tbl_user u')
            ->join('tbl_level_user l', 'l.id_level_user = u.id_level_user', 'left')
            ->order_by('u.id_user')->get()->result();
    }

    public function get_by_username($username)
    {
        return $this->db->select('u.*, l.nama_level')
            ->from('tbl_user u')
            ->join('tbl_level_user l', 'l.id_level_user = u.id_level_user', 'left')
            ->where('u.username', $username)->get()->row();
    }

    public function username_exists($username, $except_id = NULL)
    {
        $this->db->where('username', $username);
        if ($except_id) {
            $this->db->where('id_user !=', $except_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
}
