<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ibuhamil extends MY_Controller
{
    function __construct()
	{
		parent::__construct();
        $this->load->model(array('Mod_ibuhamil'));
	}

	public function index()
	{
		$this->load->helper('url');
        $this->template->load('layoutbackend','admin/ibuhamil/index');
	}

	 public function ajax_list()
    {
        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_ibuhamil->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $ibu) {
            $no++;
            $row = array();
            $row[] = $ibu->nik_ibuhamil;
            $row[] = $ibu->nama_ibuhamil;
            $row[] = $ibu->usia;
            $row[] = $ibu->alamat;
            $row[] = $ibu->tempat_lahir;
            $row[] = $ibu->tanggal_lahir;
            $row[] = $ibu->no_hp;
            $row[] = $ibu->id_ibuhamil;
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mod_ibuhamil->count_all(),
                        "recordsFiltered" => $this->Mod_ibuhamil->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    //view data ibuhamil

    public function viewibuhamil(){
        $id = $this->input->post('id');
        $table = $this->input->post('table');
        $data['table'] = $table;
        $data['data_field'] = $this->db->field_data($table);
        $data['data_table'] = $this->Mod_ibuhamil->view_ibuhamil($id)->result_array();
        $this->load->view('admin/ibuhamil/view', $data);
    }

    //add ibuhamil
    public function insert()
    {
        $this->_validate();
        // $kode= date('ymsi');
		$save  = array(
            'nik_ibuhamil'	=> $this->input->post('nik_ibuhamil'),
            'nama_ibuhamil'	=> $this->input->post('nama_ibuhamil'),
            'tempat_lahir'  => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'usia'			=> $this->input->post('usia'),
            // 'nama_orangtua'	=> $this->input->post('nama_orangtua'),
            'no_hp'			=> $this->input->post('no_hp'),
            'alamat'		=> $this->input->post('alamat')

        );
            $this->Mod_ibuhamil->insert_ibuhamil("tb_ibuhamil", $save);
            echo json_encode(array("status" => TRUE));
    }

    //edit ibuhamil
    public function update()
    {
        $this->_validate();
        $id = $this->input->post('id_ibuhamil');
        $save  = array(
            'nik_ibuhamil'  => $this->input->post('nik_ibuhamil'),
            'nama_ibuhamil' => $this->input->post('nama_ibuhamil'),
            'tempat_lahir'  => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'usia'          => $this->input->post('usia'),
            // 'nama_orangtua' => $this->input->post('nama_orangtua'),
            'no_hp'         => $this->input->post('no_hp'),
            'alamat'        => $this->input->post('alamat')
        );
        $this->Mod_ibuhamil->update_ibuhamil($id, $save);
        echo json_encode(array("status" => TRUE));
    }

    public function edit_ibuhamil($id)
    {
            $data = $this->Mod_ibuhamil->get_ibuhamil($id);
            echo json_encode($data);
    }

    public function delete()
    {
        $id = $this->input->post('id_ibuhamil');
        $this->Mod_ibuhamil->delete_ibuhamil($id, 'tb_ibuhamil');        
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('nik_ibuhamil') == '')
        {
            $data['inputerror'][] = 'nik_ibuhamil';
            $data['error_string'][] = 'NIK Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('nama_ibuhamil') == '')
        {
            $data['inputerror'][] = 'nama_ibuhamil';
            $data['error_string'][] = 'Nama Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('tempat_lahir') == '')
        {
            $data['inputerror'][] = 'tempat_lahir';
            $data['error_string'][] = 'Tempat Lahir Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('tanggal_lahir') == '')
        {
            $data['inputerror'][] = 'tanggal_lahir';
            $data['error_string'][] = 'Tanggal Lahir Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('usia') == '')
        {
            $data['inputerror'][] = 'usia';
            $data['error_string'][] = 'Usia Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        // if($this->input->post('nama_orangtua') == '')
        // {
        //     $data['inputerror'][] = 'nama_orangtua';
        //     $data['error_string'][] = 'Nama Orang Tua Tidak Boleh Kosong';
        //     $data['status'] = FALSE;
        // }

        if($this->input->post('no_hp') == '')
        {
            $data['inputerror'][] = 'no_hp';
            $data['error_string'][] = 'No HP Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('alamat') == '')
        {
            $data['inputerror'][] = 'alamat';
            $data['error_string'][] = 'Alamat Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

}