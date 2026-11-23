<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leveluser extends Crud_Controller
{
    protected $model  = 'M_leveluser';
    protected $pk     = 'id_level_user';
    protected $title  = 'Level User';
    protected $fields = array(
        'nama_level' => array('label' => 'Nama Level', 'rules' => 'required|max_length[50]'),
    );
    protected $used_in = array('tbl_user' => 'id_level_user', 'tbl_user_rule' => 'id_level_user');

    public function _row_actions($row)
    {
        return '<a href="' . site_url('hakakses/index/' . $row->id_level_user) . '" class="btn btn-xs btn-info" title="Atur Hak Akses"><i class="fa fa-lock"></i></a>';
    }

    public function delete($id = NULL)
    {
        if ((int) $id === 1) {
            $this->session->set_flashdata('error', 'Level Administrator tidak boleh dihapus.');
            redirect('leveluser');
        }
        parent::delete($id);
    }
}
