<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_model extends MY_Model {

    public $tbl_name = "tbl_leave";    

    public function insert_leave($data) {
		$this->db->insert($this->tbl_name, $data);
		return $this->db->insert_id();
	}

	public function get_leave_id_list() {
		$query = $this->db->query('SELECT tbl_leave.leave_id, tbl_leave.leave_title FROM tbl_leave WHERE tbl_leave.status = 1 AND tbl_leave.delete_status = 0');
		return $query->result();
	}

	public function checkDateHired($emp_id) {
		$query = $this->db->query('SELECT tbl_user.date_hired FROM tbl_user WHERE tbl_user.employee_id = "'.$emp_id.'" AND tbl_user.date_hired < DATE_SUB(NOW(),INTERVAL 1 YEAR)');
		return $query->row();
	}	

	public function getLeaveDuration($leave_id) {
		$query = $this->db->query('SELECT tbl_leave.duration FROM tbl_leave WHERE tbl_leave.leave_id = "'.$leave_id.'"');
		return $query->row('duration');
	}
	

}

/* End of file Leave_model.php */
/* Location: ./application/models/Leave_model.php */