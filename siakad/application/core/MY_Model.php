<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model dasar CRUD sederhana
 */
class MY_Model extends CI_Model
{
    protected $table;
    protected $pk;
    protected $order_by;

    public function get_all()
    {
        if ($this->order_by) {
            $this->db->order_by($this->order_by);
        }
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, array($this->pk => $id))->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where($this->pk, $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where($this->pk, $id)->delete($this->table);
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function exists($id)
    {
        return $this->db->where($this->pk, $id)->count_all_results($this->table) > 0;
    }
}
