<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pengaturan hak akses menu per level user (tbl_user_rule)
 */
class Hakakses extends MY_Controller
{
    public function index($id_level = NULL)
    {
        $id_level = (int) ($id_level ?: $this->input->get('id_level_user') ?: 1);
        $this->load->model(array('M_menu', 'M_leveluser'));
        $data['title']    = 'Hak Akses Menu';
        $data['id_level'] = $id_level;
        $data['levels']   = $this->M_leveluser->get_all();
        $data['menus']    = $this->M_menu->get_all_with_parent();
        $data['akses']    = array_column(
            $this->db->select('id_menu')->where('id_level_user', $id_level)->get('tbl_user_rule')->result_array(), 'id_menu');
        $this->render('hakakses/index', $data);
    }

    public function save()
    {
        $this->only_post();
        $id_level = (int) $this->input->post('id_level_user');
        $menus    = array_map('intval', (array) $this->input->post('id_menu'));

        // pengaman: admin selalu punya akses ke menu Hak Akses & Dashboard
        if ($id_level === 1) {
            foreach ($this->db->where_in('link', array('hakakses', 'dashboard'))->get('tbl_menu')->result() as $m) {
                $menus[] = (int) $m->id_menu;
            }
        }

        // jika sub menu dicentang, induknya ikut dicentang
        if ($menus) {
            foreach ($this->db->where_in('id_menu', $menus)->where('is_main_menu >', 0)->get('tbl_menu')->result() as $m) {
                $menus[] = (int) $m->is_main_menu;
            }
        }
        $menus = array_unique($menus);

        $this->db->trans_start();
        $this->db->where('id_level_user', $id_level)->delete('tbl_user_rule');
        foreach ($menus as $id_menu) {
            $this->db->insert('tbl_user_rule', array('id_menu' => $id_menu, 'id_level_user' => $id_level));
        }
        $this->db->trans_complete();

        $this->session->set_flashdata('success', 'Hak akses berhasil disimpan.');
        redirect('hakakses/index/' . $id_level);
    }
}
