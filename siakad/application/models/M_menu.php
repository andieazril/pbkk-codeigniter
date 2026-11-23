<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_menu extends MY_Model
{
    protected $table    = 'tbl_menu';
    protected $pk       = 'id_menu';
    protected $order_by = 'is_main_menu ASC, urutan ASC';

    /**
     * Menu bertingkat (menu utama + sub menu) sesuai hak akses level user
     */
    public function get_menu_user($id_level_user)
    {
        $rows = $this->db->select('m.*')
            ->from('tbl_menu m')
            ->join('tbl_user_rule r', 'r.id_menu = m.id_menu')
            ->where('r.id_level_user', $id_level_user)
            ->order_by('m.urutan')
            ->get()->result();

        $main = array();
        $sub  = array();
        foreach ($rows as $r) {
            if ((int) $r->is_main_menu === 0) {
                $r->children = array();
                $main[$r->id_menu] = $r;
            } else {
                $sub[] = $r;
            }
        }
        foreach ($sub as $s) {
            if (isset($main[$s->is_main_menu])) {
                $main[$s->is_main_menu]->children[] = $s;
            }
        }
        // menu induk '#' tanpa anak tidak perlu ditampilkan
        return array_filter($main, function ($m) {
            return $m->link !== '#' || count($m->children) > 0;
        });
    }

    public function get_all_with_parent()
    {
        return $this->db->select('m.*, p.nama_menu AS nama_induk')
            ->from('tbl_menu m')
            ->join('tbl_menu p', 'p.id_menu = m.is_main_menu', 'left')
            ->order_by('COALESCE(p.urutan, m.urutan), m.is_main_menu, m.urutan', '', FALSE)
            ->get()->result();
    }

    public function main_menu_dropdown()
    {
        $out = array('0' => '[ Menu Utama ]');
        foreach ($this->db->where('is_main_menu', 0)->order_by('urutan')->get($this->table)->result() as $r) {
            $out[$r->id_menu] = $r->nama_menu;
        }
        return $out;
    }

    public function delete($id)
    {
        $this->db->trans_start();
        $child = $this->db->select('id_menu')->where('is_main_menu', $id)->get($this->table)->result_array();
        $ids   = array_merge(array($id), array_column($child, 'id_menu'));
        $this->db->where_in('id_menu', $ids)->delete('tbl_user_rule');
        $this->db->where_in('id_menu', $ids)->delete($this->table);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
