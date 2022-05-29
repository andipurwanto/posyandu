<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balita extends MY_Controller
{
    function __construct()
	{
		parent::__construct();
        $this->load->model(array('Mod_balita'));
	}

	public function index()
	{
		$this->load->helper('url');
        $this->template->load('layoutbackend','admin/balita/index');
	}

	 public function ajax_list()
    {
        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_balita->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $bal) {
            $no++;
            $row = array();
            $row[] = $bal->nik_balita;
            $row[] = $bal->nama_balita;
            $row[] = $bal->usia;
            $row[] = $bal->nama_orangtua;
            $row[] = $bal->tempat_lahir;
            $row[] = $bal->tanggal_lahir;
            $row[] = $bal->no_hp;
            $row[] = $bal->alamat;
            $row[] = $bal->id_balita;
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mod_balita->count_all(),
                        "recordsFiltered" => $this->Mod_balita->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    //view data balita

    public function viewbalita(){
        $id = $this->input->post('id');
        $table = $this->input->post('table');
        $data['table'] = $table;
        $data['data_field'] = $this->db->field_data($table);
        $data['data_table'] = $this->Mod_balita->view_balita($id)->result_array();
        $this->load->view('admin/balita/view', $data);
    }

    //add balita
    public function insert()
    {
        $this->_validate();
        // $kode= date('ymsi');
		$save  = array(
            'nik_balita'	=> $this->input->post('nik_balita'),
            'nama_balita'	=> $this->input->post('nama_balita'),
            'tempat_lahir'  => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'usia'			=> $this->input->post('usia'),
            'nama_orangtua'	=> $this->input->post('nama_orangtua'),
            'no_hp'			=> $this->input->post('no_hp'),
            'alamat'		=> $this->input->post('alamat')

        );
            $this->Mod_balita->insert_balita("tb_balita", $save);
            echo json_encode(array("status" => TRUE));
    }

    //edit balita
    public function update()
    {
        $this->_validate();
        $id = $this->input->post('id_balita');
        $save  = array(
            'nik_balita'      => $this->input->post('nik_balita'),
            'nama_balita'      => $this->input->post('nama_balita'),
            'tempat_lahir'      => $this->input->post('tempat_lahir'),
            'tanggal_lahir'      => $this->input->post('tanggal_lahir'),
            'usia'      => $this->input->post('usia'),
            'nama_orangtua'      => $this->input->post('nama_orangtua'),
            'no_hp'      => $this->input->post('no_hp'),
            'alamat'      => $this->input->post('alamat')
        );
        $this->Mod_balita->update_balita($id, $save);
        echo json_encode(array("status" => TRUE));
    }

    public function edit_balita($id)
    {
            $data = $this->Mod_balita->get_balita($id);
            echo json_encode($data);
    }

    public function delete()
    {
        $id = $this->input->post('id_balita');
        $this->Mod_balita->delete_balita($id, 'tb_balita');        
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('nik_balita') == '')
        {
            $data['inputerror'][] = 'nik_balita';
            $data['error_string'][] = 'NIK Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('nama_balita') == '')
        {
            $data['inputerror'][] = 'nama_balita';
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

        if($this->input->post('nama_orangtua') == '')
        {
            $data['inputerror'][] = 'nama_orangtua';
            $data['error_string'][] = 'Nama Orang Tua Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

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