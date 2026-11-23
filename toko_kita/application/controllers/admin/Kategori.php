<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends Crud_Controller
{
    protected $model   = 'M_kategori';
    protected $pk      = 'id_kategori';
    protected $title   = 'Kategori Produk';
    protected $used_in = array('tbl_produk' => 'id_kategori');
    protected $fields  = array(
        'nama_kategori' => array('label' => 'Nama Kategori', 'rules' => 'required|max_length[50]'),
        'icon'          => array('label' => 'Icon (Font Awesome 4, cth: fa fa-tag)', 'rules' => 'required|max_length[40]', 'default' => 'fa fa-tag'),
        'jml_produk'    => array('label' => 'Jumlah Produk', 'form' => FALSE),
    );

    protected function get_fields()
    {
        // field 'form' => FALSE hanya tampil di tabel
        if ($this->router->fetch_method() !== 'index') {
            return array_filter($this->fields, function ($f) { return !isset($f['form']) || $f['form']; });
        }
        return $this->fields;
    }

    /** slug otomatis dari nama kategori */
    protected function after_save($id, $data)
    {
        $base = slugify($data['nama_kategori']); $slug = $base; $i = 2;
        while ($this->db->where('slug', $slug)->where('id_kategori !=', $id)->count_all_results('tbl_kategori')) $slug = $base . '-' . $i++;
        $this->db->where('id_kategori', $id)->update('tbl_kategori', array('slug' => $slug));
    }
}
