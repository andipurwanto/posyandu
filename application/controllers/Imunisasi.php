<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imunisasi extends MY_Controller
{
    function __construct()
	{
		parent::__construct();
        $this->load->model(array('Mod_imunisasi'));
	}

	public function index()
	{
		$this->load->helper('url');
        $this->template->load('layoutbackend','admin/imunisasi/index');
	}

	 public function ajax_list()
    {
        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_imunisasi->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $imun) {
            $no++;
            $row = array();
            $row[] = $imun->imunisasi;
            $row[] = $imun->id_imunisasi;
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mod_imunisasi->count_all(),
                        "recordsFiltered" => $this->Mod_imunisasi->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    //view data

    public function viewimunisasi(){
        $id = $this->input->post('id');
        $table = $this->input->post('table');
        $data['table'] = $table;
        $data['data_field'] = $this->db->field_data($table);
        $data['data_table'] = $this->Mod_imunisasi->view_imunisasi($id)->result_array();
        $this->load->view('admin/imunisasi/view', $data);
    }

    //add imunisasi
    public function insert()
    {
        $this->_validate();
        // $kode= date('ymsi');
		$save  = array(
            'imunisasi'	=> $this->input->post('imunisasi'),
        );
            $this->Mod_imunisasi->insert_imunisasi("tb_imunisasi", $save);
            echo json_encode(array("status" => TRUE));
    }

    //edit imunisasi
    public function update()
    {
        $this->_validate();
        $id = $this->input->post('id_imunisasi');
        $save  = array(
            'imunisasi'      => $this->input->post('imunisasi'),
        );
        $this->Mod_imunisasi->update_imunisasi($id, $save);
        echo json_encode(array("status" => TRUE));
    }

    public function edit_imunisasi($id)
    {
            $data = $this->Mod_imunisasi->get_imunisasi($id);
            echo json_encode($data);
    }

    public function delete()
    {
        $id = $this->input->post('id_imunisasi');
        $this->Mod_imunisasi->delete_imunisasi($id, 'tb_imunisasi');        
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('imunisasi') == '')
        {
            $data['inputerror'][] = 'imunisasi';
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