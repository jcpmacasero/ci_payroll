<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Additional_model extends MY_Model {

    public $tbl_name = "tbl_additional";

    public function insert_additional($data) {
		$this->db->insert($this->tbl_name, $data);
		return $this->db->insert_id();
	}

	public function get_addt_by_id($id) {
		$query = $this->db->query('SELECT tbl_additional.additional_title, tbl_additional.additional_status, tbl_additional.amount, tbl_additional.created_at FROM tbl_additional WHERE tbl_additional.additional_id= "'.$id.'"');
        return $query->row();
	}

	public function update_additional($data,$where) {
		$this->db->update($this->tbl_name, $data, $where);
		return $this->db->affected_rows();
	}

}

/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */