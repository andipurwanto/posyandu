<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_ibuhamil extends CI_Model
{
	var $table = 'tb_ibuhamil';
	var $column_search = array('nik_ibuhamil','nama_ibuhamil','tempat_lahir','tanggal_lahir', 'usia', 'no_hp', 'alamat'); 
	var $column_order = array('nik_ibuhamil','nama_ibuhamil','tempat_lahir','tanggal_lahir', 'usia', 'no_hp', 'alamat');
	var $order = array('id_ibuhamil' => 'desc'); 
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

		private function _get_datatables_query()
	{
		
		$this->db->from('tb_ibuhamil');
		$i = 0;

	foreach ($this->column_search as $item) // loop column 
	{
	if($_POST['search']['value']) // if datatable send POST for search
	{

	if($i===0) // first loop
	{
	$this->db->group_start();
	$this->db->like($item, $_POST['search']['value']);
	}
	else
	{
		$this->db->or_like($item, $_POST['search']['value']);
	}

		if(count($this->column_search) - 1 == $i) //last loop
		$this->db->group_end(); //close bracket
	}
	$i++;
	}

		if(isset($_POST['order']))
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_all()
	{
		$this->db->from('tb_ibuhamil');
		return $this->db->count_all_results();
	}

	function insert_ibuhamil($table, $data)
    {
        $insert = $this->db->insert($table, $data);
        return $insert;
    }

    function update_ibuhamil($id, $data)
    {
        $this->db->where('id_ibuhamil', $id);
        $this->db->update('tb_ibuhamil', $data);
    }

        function get_ibuhamil($id)
    {   
        $this->db->where('id_ibuhamil',$id);
        return $this->db->get('tb_ibuhamil')->row();
    }

        function delete_ibuhamil($id, $table)
    {
        $this->db->where('id_ibuhamil', $id);
        $this->db->delete($table);
    }

     function view_ibuhamil($id)
    {	
    	$this->db->where('id_ibuhamil',$id);
    	return $this->db->get('tb_ibuhamil');
    }

}