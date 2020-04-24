<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deduction_model extends MY_Model {

    public $tbl_name = "tbl_deduction";

    public function insert_deduction($data) {
		$this->db->insert($this->tbl_name, $data);
		return $this->db->insert_id();
	}

	public function get_deduction_by_id($id) {
		$query = $this->db->query('SELECT tbl_deduction.deduction_title, tbl_deduction.amount, tbl_deduction.deduction_status, tbl_deduction.created_at FROM tbl_deduction WHERE tbl_deduction.deduction_id = "'.$id.'" ');
        return $query->row();
	}

	public function update_deduction($data,$where) {
		$this->db->update($this->tbl_name, $data, $where);
		return $this->db->affected_rows();
	}

}

/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */