<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class On_leave_model extends MY_Model {

    public $tbl_name = "tbl_on_leave";        	

    public function insert_on_leave($data) {
		$this->db->insert($this->tbl_name, $data);
		return $this->db->insert_id();
	}

	public function getAllLeave($emp_id,$leave_id) {
		$query = $this->db->query('SELECT SUM(duration) FROM tbl_on_leave WHERE tbl_on_leave.emp_id = "'.$emp_id.'" AND tbl_on_leave.leave_id = "'.$leave_id.'" AND YEAR(tbl_on_leave.date_applied) = YEAR(CURDATE()) ');
		return $query->row('SUM(duration)');
	}


}

/* End of file On_leave_model.php */
/* Location: ./application/models/On_leave_model.php */