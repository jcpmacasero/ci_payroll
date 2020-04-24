<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_model extends MY_Model {

    public $tbl_name = "tbl_salary";    

	public function insert_salary($data) {
		$this->db->insert($this->tbl_name, $data);
		return $this->db->insert_id();	
	}

	public function get_salary_by_id($id) {
		$query = $this->db->query('SELECT tbl_salary.salary_title, tbl_salary.salary_status, tbl_salary.amount FROM tbl_salary WHERE tbl_salary.salary_id = "'.$id.'"');
        return $query->row();
	}

	public function update_salary($data,$where) {
		$this->db->update($this->tbl_name, $data, $where);
		return $this->db->affected_rows();
	}


}

/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */