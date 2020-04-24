<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Position_model extends MY_Model {

	public $tbl_name = "tbl_position";	

	public function insert_position($data) {
		$this->db->insert($this->tbl_name, $data);
		return $this->db->insert_id();	
	}

	public function get_position_by_id($id) {
		$query = $this->db->query('SELECT tbl_position.position_title, tbl_position.department_id, tbl_position.salary_id FROM tbl_position WHERE tbl_position.position_id = "'.$id.'"');
        return $query->row();
	}

	public function update_position($data,$where) {
		$this->db->update($this->tbl_name, $data, $where);
		return $this->db->affected_rows();
	}

}

/* End of file Department.php */
/* Location: ./application/models/Department.php */