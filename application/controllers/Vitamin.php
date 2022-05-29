<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vitamin extends MY_Controller
{
    function __construct()
	{
		parent::__construct();
        $this->load->model(array('Mod_vitamin'));
	}

	public function index()
	{
		$this->load->helper('url');
        $this->template->load('layoutbackend','admin/vitamin/index');
	}

	 public function ajax_list()
    {
        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_vitamin->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $vit) {
            $no++;
            $row = array();
            $row[] = $vit->vitamin;
            $row[] = $vit->id_vitamin;
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mod_vitamin->count_all(),
                        "recordsFiltered" => $this->Mod_vitamin->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    //view data

    public function viewvitamin(){
        $id = $this->input->post('id');
        $table = $this->input->post('table');
        $data['table'] = $table;
        $data['data_field'] = $this->db->field_data($table);
        $data['data_table'] = $this->Mod_vitamin->view_vitamin($id)->result_array();
        $this->load->view('admin/vitamin/view', $data);
    }

    //add vitamin
    public function insert()
    {
        $this->_validate();
        // $kode= date('ymsi');
		$save  = array(
            'vitamin'	=> $this->input->post('vitamin'),
        );
            $this->Mod_vitamin->insert_vitamin("tb_vitamin", $save);
            echo json_encode(array("status" => TRUE));
    }

    //edit vitamin
    public function update()
    {
        $this->_validate();
        $id = $this->input->post('id_vitamin');
        $save  = array(
            'vitamin'      => $this->input->post('vitamin'),
        );
        $this->Mod_vitamin->update_vitamin($id, $save);
        echo json_encode(array("status" => TRUE));
    }

    public function edit_vitamin($id)
    {
            $data = $this->Mod_vitamin->get_vitamin($id);
            echo json_encode($data);
    }

    public function delete()
    {
        $id = $this->input->post('id_vitamin');
        $this->Mod_vitamin->delete_vitamin($id, 'tb_vitamin');        
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('vitamin') == '')
        {
            $data['inputerror'][] = 'vitamin';
            $data['error_string'][] = 'Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

}