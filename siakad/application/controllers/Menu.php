<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends Crud_Controller
{
    protected $model  = 'M_menu';
    protected $pk     = 'id_menu';
    protected $title  = 'Manajemen Menu';

    protected function get_fields()
    {
        return array(
            'nama_menu'    => array('label' => 'Nama Menu', 'rules' => 'required|max_length[50]'),
            'link'         => array('label' => 'Link (nama controller, # untuk induk)', 'rules' => 'required|max_length[100]|regex_match[/^[#a-z0-9_\/]+$/]'),
            'icon'         => array('label' => 'Icon (Font Awesome 4)', 'rules' => 'required|max_length[50]', 'default' => 'fa fa-circle-o'),
            'is_main_menu' => array('label' => 'Induk Menu', 'type' => 'select', 'rules' => 'required|integer',
                                    'options' => $this->M_menu->main_menu_dropdown(), 'display' => 'nama_induk', 'default' => '0'),
            'urutan'       => array('label' => 'Urutan', 'type' => 'number', 'rules' => 'required|integer', 'default' => 1),
        );
    }

    public function index()
    {
        $data['title']   = $this->title;
        $data['rows']    = $this->M_menu->get_all_with_parent();
        $data['fields']  = $this->get_fields();
        $data['pk']      = $this->pk;
        $data['pk_auto'] = TRUE;
        $data['module']  = 'menu';
        $data['ctrl']    = $this;
        $this->render('crud/list', $data);
    }

    /** menu baru otomatis bisa diakses Administrator */
    protected function after_save($id, $data)
    {
        if (!$this->db->where(array('id_menu' => $id, 'id_level_user' => 1))->count_all_results('tbl_user_rule')) {
            $this->db->insert('tbl_user_rule', array('id_menu' => $id, 'id_level_user' => 1));
        }
    }
}
