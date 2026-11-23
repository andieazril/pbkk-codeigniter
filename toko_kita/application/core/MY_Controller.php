<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base controller.
 *  - Front_Controller  : halaman toko (publik)
 *  - Member_Controller : halaman toko yang wajib login pelanggan
 *  - Admin_Controller  : panel admin (wajib login admin)
 *  - Crud_Controller   : CRUD generik untuk tabel master di panel admin
 */
class MY_Controller extends CI_Controller
{
    /** Pastikan request aksi (hapus/ubah) selalu lewat POST */
    protected function only_post()
    {
        if ($this->input->method() !== 'post') {
            show_error('Method tidak diizinkan.', 405);
        }
    }
}

class Front_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('keranjang_lib', NULL, 'keranjang');
    }

    protected function pelanggan_id()
    {
        return (int) $this->session->userdata('id_pelanggan');
    }

    protected function render($view, $data = array())
    {
        $data['kategori_menu'] = $this->db->order_by('id_kategori')->get('tbl_kategori')->result();
        $data['jml_keranjang'] = $this->keranjang->count();
        $data['content'] = $this->load->view($view, $data, TRUE);
        $this->load->view('front/layout', $data);
    }
}

class Member_Controller extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->pelanggan_id()) {
            $this->session->set_flashdata('warning', 'Silakan masuk atau daftar terlebih dahulu.');
            redirect('akun/login?redirect=' . rawurlencode(uri_string()));
        }
    }
}

class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('admin_login')) {
            $this->session->set_flashdata('error', 'Silakan login sebagai admin.');
            redirect('admin/auth');
        }
    }

    protected function render($view, $data = array())
    {
        $data['pesanan_baru'] = $this->db->where_in('status', array('Menunggu Verifikasi'))->count_all_results('tbl_pesanan');
        $data['content'] = $this->load->view($view, $data, TRUE);
        $this->load->view('admin/template/layout', $data);
    }
}

/**
 * Controller CRUD generik untuk tabel master sederhana.
 * Cukup definisikan $model, $pk, $title, $fields (dan opsional $used_in).
 *
 * $fields = array(
 *   'nama_field' => array('label' => 'Label', 'type' => 'text|number|date|select|textarea',
 *                         'rules' => 'required', 'options' => array(), 'list' => TRUE)
 * );
 */
abstract class Crud_Controller extends Admin_Controller
{
    protected $model;
    protected $pk;
    protected $pk_auto = TRUE;   // FALSE jika primary key diinput manual (kode)
    protected $title;
    protected $fields = array();
    /** tabel lain yang mereferensikan data ini: array('tbl_x' => 'kolom') */
    protected $used_in = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model($this->model);
    }

    protected function module()
    {
        return 'admin/' . strtolower($this->router->fetch_class());
    }

    /** override bila butuh field dinamis (mis. options dari DB) */
    protected function get_fields()
    {
        return $this->fields;
    }

    /** override untuk aksi tambahan per baris (prefix _ agar tidak bisa diakses via URL) */
    public function _row_actions($row)
    {
        return '';
    }

    public function index()
    {
        $data['title']   = $this->title;
        $data['subtitle'] = 'Daftar data';
        $data['rows']    = $this->{$this->model}->get_all();
        $data['fields']  = $this->get_fields();
        $data['pk']      = $this->pk;
        $data['pk_auto'] = $this->pk_auto;
        $data['module']  = $this->module();
        $data['ctrl']    = $this;
        $this->render('admin/crud/list', $data);
    }

    public function add()
    {
        $this->form(NULL);
    }

    public function edit($id = NULL)
    {
        $row = $this->{$this->model}->get_by_id(rawurldecode($id));
        if (!$row) show_404();
        $this->form($row);
    }

    protected function form($row)
    {
        $fields = $this->get_fields();
        if (!$this->pk_auto) {
            $fields = array($this->pk => array('label' => 'Kode', 'type' => 'text', 'rules' => 'required|max_length[10]|alpha_dash')) + $fields;
        }
        $data['title']    = $this->title;
        $data['subtitle'] = $row ? 'Edit data' : 'Tambah data';
        $data['fields']   = $fields;
        $data['row']      = $row;
        $data['pk']       = $this->pk;
        $data['pk_auto']  = $this->pk_auto;
        $data['module']   = $this->module();
        $this->render('admin/crud/form', $data);
    }

    public function save()
    {
        $this->only_post();
        $old_id = $this->input->post('_id');
        $fields = $this->get_fields();
        if (!$this->pk_auto) {
            $fields = array($this->pk => array('label' => 'Kode', 'rules' => 'required|max_length[10]|alpha_dash')) + $fields;
        }

        foreach ($fields as $name => $f) {
            $this->form_validation->set_rules($name, $f['label'], 'trim|' . (isset($f['rules']) ? $f['rules'] : ''));
        }
        $back = $old_id ? $this->module() . '/edit/' . rawurlencode($old_id) : $this->module() . '/add';

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
            redirect($back);
        }

        $data = array();
        foreach ($fields as $name => $f) {
            $val = $this->input->post($name, TRUE);
            $data[$name] = ($val === '' && isset($f['type']) && in_array($f['type'], array('date', 'number'))) ? NULL : $val;
        }

        $m = $this->{$this->model};
        if ($old_id) {
            if (!$this->pk_auto && $data[$this->pk] !== $old_id && $m->exists($data[$this->pk])) {
                $this->session->set_flashdata('error', 'Kode ' . html_escape($data[$this->pk]) . ' sudah digunakan.');
                redirect($back);
            }
            if (!$this->pk_auto && $data[$this->pk] !== $old_id && $this->is_used($old_id)) {
                $this->session->set_flashdata('error', 'Kode tidak dapat diubah karena sudah dipakai di data lain.');
                redirect($back);
            }
            $m->update($old_id, $data);
            $this->after_save($this->pk_auto ? $old_id : $data[$this->pk], $data);
            $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
        } else {
            if (!$this->pk_auto && $m->exists($data[$this->pk])) {
                $this->session->set_flashdata('error', 'Kode ' . html_escape($data[$this->pk]) . ' sudah digunakan.');
                redirect($back);
            }
            $m->insert($data);
            $this->after_save($this->pk_auto ? $this->db->insert_id() : $data[$this->pk], $data);
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan.');
        }
        redirect($this->module());
    }

    /** hook setelah data disimpan */
    protected function after_save($id, $data)
    {
    }

    protected function is_used($id)
    {
        foreach ($this->used_in as $table => $col) {
            if ($this->db->where($col, $id)->count_all_results($table) > 0) {
                return $table;
            }
        }
        return FALSE;
    }

    public function delete($id = NULL)
    {
        $this->only_post();
        $id = rawurldecode($id);
        if ($table = $this->is_used($id)) {
            $this->session->set_flashdata('error', 'Data tidak dapat dihapus karena masih digunakan (' . $table . ').');
        } else {
            $this->{$this->model}->delete($id);
            $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        }
        redirect($this->module());
    }
}
