<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deduction_tagging_model extends MY_Model {

    public $tbl_name = "tbl_deduction_employee";

    public function checkEmpIfExist($emp_id){
    	$query = $this->db->query('SELECT tbl_user.employee_id FROM tbl_user WHERE tbl_user.employee_id = "'.$emp_id.'"');
    	return $query->row();
    }

    public function getEmployeeDetails($emp_id) {
    	$query = $this->db->query('SELECT tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext, tbl_position.position_title, tbl_user.date_hired, tbl_department.department_name, tbl_salary.salary_title, tbl_user.user_id FROM tbl_user INNER JOIN tbl_position ON tbl_user.position_id = tbl_position.position_id INNER JOIN tbl_department ON tbl_position.department_id = tbl_department.department_id INNER JOIN tbl_salary ON tbl_position.salary_id = tbl_salary.salary_id WHERE tbl_user.employee_id = "'.$emp_id.'"');
        return $query->row();
    }

    public function insert_for_deduction($data) {
    	$insert = $this->db->insert($this->tbl_name,$data);
        return $this->db->insert_id();
    }
   
}

/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */