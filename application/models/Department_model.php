<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department_model extends MY_Model {

	public $tbl_name = "tbl_department";	

	public function insert_department($data) {
		$this->db->insert($this->tbl_name, $data);
		return $this->db->insert_id();
	}

	public function get_dept_by_id($id) {
		$query = $this->db->query('SELECT tbl_department.department_name, tbl_department.department_id FROM tbl_department WHERE tbl_department.department_id = "'.$id.'" ');
        return $query->row();
	}

	public function update_department($data,$where) {
		$this->db->update($this->tbl_name, $data, $where);
		return $this->db->affected_rows();
	}

}

/* End of file Department.php */
/* Location: ./application/models/Department.php */