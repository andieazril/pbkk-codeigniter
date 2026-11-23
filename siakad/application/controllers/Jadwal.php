<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal extends MY_Controller
{
    private $hari = array('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_jadwal', 'M_tahunakademik', 'M_prodi', 'M_dosen'));
    }

    public function index()
    {
        $ta    = $this->input->get('id_tahun_akademik', TRUE) ?: tahun_akademik_aktif('id_tahun_akademik');
        $prodi = $this->input->get('kd_prodi', TRUE);
        $data['title']    = 'Jadwal Kuliah';
        $data['ta']       = $ta;
        $data['prodi']    = $prodi;
        $data['ta_opt']   = $this->M_tahunakademik->dropdown();
        $data['prodi_opt'] = $this->M_prodi->dropdown();
        $data['rows']     = $ta ? $this->M_jadwal->get_filter($ta, $prodi) : array();
        $this->render('jadwal/index', $data);
    }

    private function form_data($row)
    {
        $mk = array();
        foreach ($this->db->select('m.kd_mk, m.nama_mk, m.sks, m.kd_prodi')->order_by('m.kd_prodi, m.kd_mk')->get('tbl_matakuliah m')->result() as $r) {
            $mk[$r->kd_mk] = $r->kd_mk . ' - ' . $r->nama_mk . ' (' . $r->sks . ' SKS, ' . $r->kd_prodi . ')';
        }
        return array(
            'row'     => $row,
            'hari'    => $this->hari,
            'ta_opt'  => $this->M_tahunakademik->dropdown(),
            'mk_opt'  => $mk,
            'dosen_opt' => $this->M_dosen->dropdown(),
        );
    }

    public function add()
    {
        $data = $this->form_data((object) array(
            'id_jadwal' => '', 'id_tahun_akademik' => tahun_akademik_aktif('id_tahun_akademik'),
            'kd_mk' => '', 'kelas' => 'A', 'id_dosen' => '', 'kd_ruangan' => '',
            'hari' => '', 'jam_mulai' => '08:00', 'jam_selesai' => '10:30', 'kuota' => 40,
        ));
        $data['title'] = 'Tambah Jadwal Kuliah';
        $this->render('jadwal/form', $data);
    }

    public function edit($id = NULL)
    {
        $row = $this->M_jadwal->get_by_id((int) $id);
        if (!$row) show_404();
        $data = $this->form_data($row);
        $data['title'] = 'Edit Jadwal Kuliah';
        $this->render('jadwal/form', $data);
    }

    public function save()
    {
        $this->only_post();
        $id   = (int) $this->input->post('id_jadwal');
        $back = $id ? 'jadwal/edit/' . $id : 'jadwal/add';

        $rules = array(
            'id_tahun_akademik' => 'Tahun Akademik', 'kd_mk' => 'Mata Kuliah', 'kelas' => 'Kelas', 'id_dosen' => 'Dosen',
            'kd_ruangan' => 'Ruangan', 'hari' => 'Hari', 'jam_mulai' => 'Jam Mulai', 'jam_selesai' => 'Jam Selesai', 'kuota' => 'Kuota',
        );
        foreach ($rules as $f => $label) {
            $this->form_validation->set_rules($f, $label, 'required|trim');
        }
        $this->form_validation->set_rules('hari', 'Hari', 'required|in_list[' . implode(',', $this->hari) . ']');
        $this->form_validation->set_rules('kelas', 'Kelas', 'required|alpha_numeric|max_length[5]');
        $this->form_validation->set_rules('kuota', 'Kuota', 'required|integer|greater_than[0]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors(' ', '<br>'));
            redirect($back);
        }

        $d = array();
        foreach (array_keys($rules) as $f) {
            $d[$f] = $this->input->post($f, TRUE);
        }
        $d['kelas'] = strtoupper($d['kelas']);
        if ($d['jam_selesai'] <= $d['jam_mulai']) {
            $this->session->set_flashdata('error', 'Jam selesai harus lebih besar dari jam mulai.');
            redirect($back);
        }
        if ($msg = $this->M_jadwal->cek_bentrok($d, $id ?: NULL)) {
            $this->session->set_flashdata('error', 'Jadwal bentrok: ' . $msg);
            redirect($back);
        }
        if ($id && $this->M_jadwal->jumlah_peserta($id) > (int) $d['kuota']) {
            $this->session->set_flashdata('error', 'Kuota tidak boleh lebih kecil dari jumlah mahasiswa yang sudah mengambil.');
            redirect($back);
        }

        if ($id) {
            $this->M_jadwal->update($id, $d);
            $this->session->set_flashdata('success', 'Jadwal berhasil diperbarui.');
        } else {
            $this->M_jadwal->insert($d);
            $this->session->set_flashdata('success', 'Jadwal berhasil ditambahkan.');
        }
        redirect('jadwal?id_tahun_akademik=' . $d['id_tahun_akademik']);
    }

    public function delete($id = NULL)
    {
        $this->only_post();
        if ($this->M_jadwal->jumlah_peserta((int) $id) > 0) {
            $this->session->set_flashdata('error', 'Jadwal tidak dapat dihapus karena sudah diambil mahasiswa di KRS.');
        } else {
            $this->M_jadwal->delete((int) $id);
            $this->session->set_flashdata('success', 'Jadwal berhasil dihapus.');
        }
        redirect('jadwal');
    }
}
