<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_imunisasi extends CI_Model
{
	var $table = 'tb_imunisasi';
	var $column_search = array('imunisasi'); 
	var $column_order = array('imunisasi');
	var $order = array('id_imunisasi' => 'desc'); 
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

		private function _get_datatables_query()
	{
		
		$this->db->from('tb_imunisasi');
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
		$this->db->from('tb_imunisasi');
		return $this->db->count_all_results();
	}

	function insert_imunisasi($table, $data)
    {
        $insert = $this->db->insert($table, $data);
        return $insert;
    }

    function update_imunisasi($id, $data)
    {
        $this->db->where('id_imunisasi', $id);
        $this->db->update('tb_imunisasi', $data);
    }

        function get_imunisasi($id)
    {   
        $this->db->where('id_imunisasi',$id);
        return $this->db->get('tb_imunisasi')->row();
    }

        function delete_imunisasi($id, $table)
    {
        $this->db->where('id_imunisasi', $id);
        $this->db->delete($table);
    }

     function view_imunisasi($id)
    {	
    	$this->db->where('id_imunisasi',$id);
    	return $this->db->get('tb_imunisasi');
    }

}