<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_payroll_model extends MY_Model {

	public function get_all_emp($status) {
		$query = $this->db->query('SELECT DISTINCT tbl_user.employee_id, tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext, tbl_salary.salary_status FROM tbl_user INNER JOIN tbl_attendance ON tbl_user.employee_id = tbl_attendance.employee_id INNER JOIN tbl_position ON tbl_user.position_id = tbl_position.position_id INNER JOIN tbl_salary ON tbl_position.salary_id = tbl_salary.salary_id WHERE tbl_attendance.`status` = 2 AND tbl_user.delete_status = 0 AND tbl_salary.salary_status = "'.$status.'"');
        return $query->result();
	}

	public function get_schedule($id,$start_date,$end_date) {
		$query = $this->db->query('SELECT tbl_schedule.employee_id, tbl_schedule.schedule_id, tbl_schedule.time_in, tbl_schedule.time_out, tbl_schedule.date_from, tbl_schedule.date_to, tbl_schedule.rest_day  FROM tbl_schedule  WHERE tbl_schedule.employee_id = "'.$id.'" AND date_from <= "'.$start_date.'" AND date_to >= "'.$end_date.'"');
		return $query->result();
	}

	public function get_attendance_same_now_end($emp_id,$start_date,$end_date) {
		$query = $this->db->query('SELECT DISTINCT DATE(t.time) AS time, t.employee_id FROM tbl_attendance AS t JOIN tbl_attendance AS t2 ON DATE(t.time)= DATE (t2.time) AND t.employee_id = t2.employee_id WHERE t.`status` = 1 AND t2.`status` = 2 AND DATE(t.time) BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND t.employee_id = "'.$emp_id.'" ORDER BY t.time');
		return $query->result();
	}

	public function get_attendance_not_same_now_end($emp_id,$start_date,$end_date) {
		$query = $this->db->query('SELECT DISTINCT DATE(t.time) AS time, t.employee_id FROM tbl_attendance AS t  JOIN tbl_attendance AS t2 ON t.employee_id = t2.employee_id WHERE t.`status` = 1 AND t2.`status` = 2  AND DATE(t.time)  BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND t.employee_id = "'.$emp_id.'" ORDER BY t.time');
		return $query->result();
	}

	public function get_salary_details($employee_id) {
		$query = $this->db->query('SELECT tbl_salary.amount, tbl_salary.salary_status FROM tbl_position INNER JOIN tbl_user ON tbl_user.position_id = tbl_position.position_id INNER JOIN tbl_salary ON tbl_position.salary_id = tbl_salary.salary_id WHERE employee_id = "'.$employee_id.'" ');
		return $query->result();
	}

	public function getTimeinByDate($date,$id) {
		$query = $this->db->query('SELECT DISTINCT t1.time, t1.`status` FROM tbl_attendance t1 INNER JOIN(SELECT status, MIN(Time) AS min_time FROM tbl_attendance WHERE time >= "'.$date.'"  AND employee_id = "'.$id.'" GROUP BY Status) t2 ON t1.Status = t2.Status AND t1.Time = t2.min_time WHERE t1.Time >= "'.$date.'" AND t1.`status` <= 2 ORDER BY t1.Status');
		return $query->result();
	}

	public function get_calendar_event($date) {
		$query = $this->db->query('SELECT tbl_calendar.event_status FROM tbl_calendar WHERE event_date = "'.$date.'" AND delete_status = 0');
		return $query->row('event_status');
	}

	public function insert_daily_salary_per_day($data) {		
		$query = $this->db->insert_batch('tbl_salary_daily',$data);		
	}

	public function insert_fixed_salary($data) {
		$query1 = $this->db->insert_batch('tbl_salary_fixed',$data);			
	}

	public function record_calculate_date($start,$end,$status) {
		$data = array(
			'date_start' => $start,
			'date_end' => $end,
			'calculate_status' => $status
		);		
		$this->db->insert('tbl_calculate_salary',$data);	
		$insert_id = $this->db->insert_id();

		return $insert_id;
	}

	public function check_data_between_date($start,$end) {
		$query = $this->db->query('SELECT COUNT(tbl_attendance.attendance_id) AS ihap FROM tbl_attendance WHERE  DATE(time) BETWEEN "'.$start.'" AND "'.$end.'"');
		return $query->row('ihap');		
	}

	public function check_if_already_calculated($start,$end,$status) {
		$query = $this->db->query('SELECT calculate_id FROM tbl_calculate_salary WHERE DATE(date_end) BETWEEN "'.$start.'" AND "'.$end.'" AND tbl_calculate_salary.`status` = 0 AND tbl_calculate_salary.calculate_status = "'.$status.'"');
		return $query->row('calculate_id');
	}

	public function update_calculate_date($id,$salary_stat,$start,$end) {
		$data = array(
			'status' => 1
		);
		$this->db->where('calculate_id', $id);
		$this->db->update('tbl_calculate_salary',$data);

		//per day
		if($salary_stat == 0) {
			$data_status = array(
				'status' => 1
			);
			$this->db->where('calculate_id', $id);
			$this->db->where('date >=', $start);
			$this->db->where('date <=', $end);
			$this->db->update('tbl_salary_daily',$data);
			$this->db->update('tbl_overtime_paid',$data);
		}
		//fixed
		else if($salary_stat == 1) {
			$data_status = array(
				'status' => 1
			);
			$this->db->where('calculate_id', $id);
			$this->db->update('tbl_salary_fixed',$data);
		}

	}

	public function check_dtr($emp_id,$start,$end) {
		$query = $this->db->query('SELECT time,`status` FROM tbl_attendance WHERE employee_id = "'.$emp_id.'" AND DATE(time) BETWEEN "'.$start.'" AND "'.$end.'" GROUP BY employee_id,SUBSTRING(time,1,10),status');
		return $query->result();
	}

	public function employee_details($emp_id) {
		$query = $this->db->query('SELECT tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext, tbl_user.date_hired, tbl_user.employee_id, tbl_position.position_title, tbl_salary.salary_title, tbl_department.department_name FROM tbl_user INNER JOIN tbl_position ON tbl_user.position_id = tbl_position.position_id INNER JOIN tbl_salary ON tbl_position.salary_id = tbl_salary.salary_id INNER JOIN tbl_department ON tbl_position.department_id = tbl_department.department_id WHERE tbl_user.employee_id = "'.$emp_id.'"');
		return $query->result();
	}

	public function insert_att_record($data) {
		$insert = $this->db->insert("tbl_attendance",$data);
        return $this->db->insert_id();
	}

	public function already_calculated($start,$end,$status) {

		//per day
		if($status == 0) {			
			$query = $this->db->query('SELECT tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext, tbl_salary_daily.employee_id, Sum(tbl_salary_daily.salary) AS salary FROM tbl_salary_daily INNER JOIN tbl_user ON tbl_salary_daily.employee_id = tbl_user.employee_id WHERE tbl_salary_daily.`status` = 0 AND DATE(date) BETWEEN "'.$start.'" AND "'.$end.'" GROUP BY tbl_salary_daily.employee_id ');	
			return $query->result();
		}
		//fixed
		else if($status == 1) {
			$query = $this->db->query('SELECT tbl_salary.amount AS salary, tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext, tbl_salary_fixed.employee_id FROM tbl_position INNER JOIN tbl_user ON tbl_user.position_id = tbl_position.position_id INNER JOIN tbl_salary ON tbl_position.salary_id = tbl_salary.salary_id INNER JOIN tbl_salary_fixed ON tbl_salary_fixed.employee_id = tbl_user.employee_id WHERE tbl_salary_fixed.`status` = 0 AND DATE(tbl_salary_fixed.date) BETWEEN "'.$start.'" AND "'.$end.'" GROUP BY tbl_salary_fixed.employee_id');
			return $query->result();
		}				
	}

	public function getSalaries($status,$start,$end) {
		//per day
		if($status == 0) {	
			/*$query = $this->db->query('SELECT tbl_salary_daily.employee_id, Sum(tbl_salary_daily.salary) AS salary, tbl_salary_daily.date, tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext FROM tbl_salary_daily INNER JOIN tbl_user ON tbl_salary_daily.employee_id = tbl_user.employee_id WHERE `status` = 0 AND DATE(date) BETWEEN "2019-09-05" AND "2019-09-20" GROUP BY tbl_salary_daily.employee_id');*/
			$query = $this->db->query('SELECT DISTINCT tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext, tbl_user.employee_id, Sum(coalesce(t1.salary,0)+coalesce(tbl_overtime_paid.amount_paid,0)) as total FROM tbl_user INNER JOIN (SELECT sum(salary) as salary, employee_id from tbl_salary_daily WHERE `status` = 0 AND tbl_salary_daily.date BETWEEN "'.$start.'" AND "'.$end.'" group by employee_id) as t1 on tbl_user.employee_id = t1.employee_id LEFT JOIN tbl_overtime_paid ON tbl_overtime_paid.employee_id = t1.employee_id AND tbl_overtime_paid.`status` = 0 AND tbl_overtime_paid.date_overtime BETWEEN "'.$start.'" AND "'.$end.'" GROUP BY tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext'); 
			// $query = $this->db->query('SELECT DISTINCT tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext, tbl_user.employee_id, Sum(coalesce(t1.salary,0)+coalesce(tbl_overtime_paid.amount_paid,0)) as total FROM tbl_user INNER JOIN (SELECT sum(salary) as salary, employee_id from tbl_salary_daily WHERE `status` = 0 AND tbl_salary_daily.date BETWEEN "2019-09-05" AND "2019-09-20" group by employee_id) as t1 on tbl_user.employee_id = t1.employee_id LEFT JOIN tbl_overtime_paid ON tbl_overtime_paid.employee_id = t1.employee_id AND tbl_overtime_paid.`status` = 0 AND tbl_overtime_paid.date_overtime BETWEEN "2019-09-05" AND "2019-09-20" GROUP BY tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext');
			return $query->result();
		}
		//fixed
		else if($status == 1){
			$query = $this->db->query('SELECT tbl_user.firstname, tbl_user.middlename, tbl_user.lastname, tbl_user.name_ext, tbl_user.employee_id, tbl_salary.amount as total FROM tbl_salary_fixed INNER JOIN tbl_user ON tbl_salary_fixed.employee_id = tbl_user.employee_id INNER JOIN tbl_position ON tbl_position.position_id = tbl_user.position_id INNER JOIN tbl_salary ON tbl_position.salary_id = tbl_salary.salary_id WHERE tbl_salary_fixed.`status` = 0 AND tbl_salary_fixed.date BETWEEN "'.$start.'" AND "'.$end.'" GROUP BY tbl_user.employee_id '); 
// 			$query = $this->db->query('SELECT
// tbl_user.firstname,
// tbl_user.middlename,
// tbl_user.lastname,
// tbl_user.name_ext,
// tbl_user.employee_id,
// tbl_salary.amount AS total
// FROM
// tbl_salary_fixed
// INNER JOIN tbl_user ON tbl_salary_fixed.employee_id = tbl_user.employee_id
// INNER JOIN tbl_position ON tbl_position.position_id = tbl_user.position_id
// INNER JOIN tbl_salary ON tbl_position.salary_id = tbl_salary.salary_id
// WHERE
// tbl_salary_fixed.`status` = 0 AND
// tbl_salary_fixed.date BETWEEN "2019-09-05" AND "2019-09-20"
// GROUP BY
// tbl_user.employee_id
// ');
			return $query->result();
		}
	}

	public function getDeduction($emp_id) {
		$query = $this->db->query('SELECT Sum(tbl_deduction.amount) AS amount FROM tbl_deduction_employee INNER JOIN tbl_deduction ON tbl_deduction_employee.deduction_id = tbl_deduction.deduction_id INNER JOIN tbl_user ON tbl_deduction_employee.employee_id = tbl_user.user_id WHERE tbl_deduction_employee.`status` = 0 AND tbl_deduction.deduction_status = 1 AND tbl_user.employee_id = "'.$emp_id.'" GROUP BY tbl_deduction_employee.employee_id');
		return $query->row('amount');
	}

	public function getAdditional($emp_id) {
		$query = $this->db->query('SELECT Sum(tbl_additional.amount) AS amount FROM tbl_additional INNER JOIN tbl_additional_employee ON tbl_additional_employee.additional_id = tbl_additional.additional_id INNER JOIN tbl_user ON tbl_additional_employee.employee_id = tbl_user.user_id WHERE tbl_additional_employee.`status` = 0 AND tbl_additional.additional_status = 1 AND tbl_user.employee_id = "'.$emp_id.'" GROUP BY tbl_additional_employee.employee_id');
		return $query->row('amount');
	}

	public function insert_batch_paid_salaries($data) {
		$query = $this->db->insert_batch('tbl_salary_paid',$data);
	}

	public function check_if_paid($status,$start,$end) {
		$query = $this->db->query('SELECT COUNT(tbl_salary_paid.salary_paid_id) AS id FROM tbl_salary_paid WHERE date_start_paid = "'.$start.'" AND date_end_paid = "'.$end.'" AND salary_status = "'.$status.'"');
		return $query->row('id');
	}

	public function get_rest_day($emp_id,$start,$end) {
		$query = $this->db->query('SELECT tbl_restday.rest_day FROM tbl_restday WHERE employee_id = "'.$emp_id.'" AND rest_day BETWEEN "'.$start.'" AND "'.$end.'" AND `status` = 0');
		return $query->result();
	}

	public function get_duty_rest($emp_id,$date_duty) {
		$query = $this->db->query('SELECT tbl_duty_rest.dutyrest_id FROM tbl_duty_rest WHERE tbl_duty_rest.employee_id = "'.$emp_id.'"  AND delete_status = 0 AND date_duty = "'.$date_duty.'"');
		$id = $query->row('dutyrest_id');
		if(!$id) {
			return 0;
		}else {
			return 1;
		}
	}

	public function check_overtime_by_emp_id($emp_id,$overtime_date) {
		$query = $this->db->query('SELECT tbl_overtime.overtime_in, tbl_overtime.overtime_out,tbl_overtime.overtime_duration_min FROM tbl_overtime WHERE tbl_overtime.employee_id = "'.$emp_id.'" AND tbl_overtime.delete_status = 0 AND DATE(tbl_overtime.overtime_in) = "'.$overtime_date.'" ');
		$date_overtime = $query->result();
		if(!$date_overtime) {
			return 0;
		}else {
			return $date_overtime;
		}
	}

	public function check_overtime_duty($emp_id,$date_start,$date_end) {
		$query = $this->db->query('SELECT DISTINCT DATE(t.time) AS time, t.employee_id FROM tbl_attendance AS t JOIN tbl_attendance AS t2 ON DATE(t.time)= DATE (t2.time) AND t.employee_id = t2.employee_id WHERE t.`status` = 3 AND t2.`status` = 4 AND t.employee_id = "'.$emp_id.'" AND DATE(t.time) BETWEEN "'.$date_start.'" AND "'.$date_end.'" ORDER BY t.time');
		$date_overtime_complete = $query->row('time');
		if(!$date_overtime_complete) {
			return 0;
		}else {
			return $date_overtime_complete;
		}
	}

	public function getOvertimeBydate($date,$id) {
		$query = $this->db->query('SELECT DISTINCT t1.time, t1.`status` FROM tbl_attendance t1 INNER JOIN(SELECT status, MIN(Time) AS min_time FROM tbl_attendance WHERE time >= "'.$date.'"  AND employee_id = "'.$id.'" GROUP BY Status) t2 ON t1.Status = t2.Status AND t1.Time = t2.min_time WHERE t1.Time >= "'.$date.'" AND t1.`status` >= 3 ORDER BY t1.Status');
		return $query->result();
	}

	public function overtime_insert($data) {
		$query = $this->db->insert_batch('tbl_overtime_paid',$data);
	}

	public function get_salary_report_daily($start_date,$end_date) {
		$query = $this->db->query('SELECT DISTINCT
tbl_user.firstname,
tbl_user.middlename,
tbl_user.lastname,
tbl_user.name_ext,
tbl_user.employee_id,
Sum(coalesce(t1.salary,0)+coalesce(tbl_overtime_paid.amount_paid,0)) as total
FROM tbl_user
INNER JOIN
  (SELECT sum(salary) as salary, employee_id from tbl_salary_daily
     WHERE `status` = 0 AND tbl_salary_daily.date BETWEEN "'.$start_date.'" AND "'.$end_date.'"
     group by employee_id) as t1 on tbl_user.employee_id = t1.employee_id
LEFT JOIN tbl_overtime_paid ON tbl_overtime_paid.employee_id = t1.employee_id AND tbl_overtime_paid.`status` = 0 AND tbl_overtime_paid.date_overtime BETWEEN "'.$start_date.'" AND "'.$end_date.'"
GROUP BY tbl_user.firstname,
  tbl_user.middlename,
  tbl_user.lastname,
  tbl_user.name_ext
');
		return $query->result();
	}

	public function get_details($emp_id,$start,$end) {
		$query = $this->db->query('SELECT
Count(tbl_salary_daily.dsalary_id) AS number_duty,
Sum(coalesce(tbl_salary_daily.salary,0)) AS total_salary,
Sum(coalesce(tbl_salary_daily.premium_pay,0)) AS premium_pay,
Sum(coalesce(tbl_salary_daily.late_duration,0)) AS late_duration,
Sum(coalesce(tbl_salary_daily.undertime_duration,0)) AS undertime_duration,
Sum(coalesce(tbl_salary_daily.cola_duration,0)) AS cola_duration,
coalesce(t1.amount_paid,0) AS amount_paid,
coalesce(t1.overtime_duration,0) AS overtime_duration,
Sum(coalesce(tbl_salary_daily.late_deduction,0)) AS late_deduction,
Sum(coalesce(tbl_salary_daily.undertime_deduction,0)) AS undertime_deduction,
Sum(coalesce(tbl_salary_daily.cola_rate,0)) AS cola_rate
FROM
tbl_salary_daily
LEFT JOIN ( SELECT SUM(amount_paid) as amount_paid,SUM(duration_mins) as overtime_duration,employee_id from tbl_overtime_paid WHERE 
tbl_overtime_paid.employee_id = "'.$emp_id.'" AND
tbl_overtime_paid.`status` = 0 AND tbl_overtime_paid.date_overtime BETWEEN "'.$start.'" AND "'.$end.'" ) AS t1 ON tbl_salary_daily.employee_id = 
t1.employee_id
WHERE
tbl_salary_daily.`status` = 0 AND
tbl_salary_daily.employee_id = "'.$emp_id.'" AND
tbl_salary_daily.date BETWEEN "'.$start.'" AND "'.$end.'"
');
	return $query->result();

	}

	public function get_details_fixed($emp_id,$start,$end) {
		$query = $this->db->query('SELECT
Count(tbl_salary_fixed.fsalary_id) AS no_days,
tbl_salary.amount
FROM
tbl_salary_fixed
INNER JOIN tbl_user ON tbl_salary_fixed.employee_id = tbl_user.employee_id
INNER JOIN tbl_position ON tbl_user.position_id = tbl_position.position_id
INNER JOIN tbl_salary ON tbl_position.salary_id = tbl_salary.salary_id
WHERE
tbl_salary_fixed.`status` = 0 AND
tbl_salary_fixed.employee_id = "'.$emp_id.'" AND
tbl_salary_fixed.date BETWEEN "'.$start.'" AND "'.$end.'"');
		return $query->result();
	}

	public function get_additionals_employee($emp_id) {
		$query = $this->db->query('SELECT tbl_additional.additional_title, tbl_additional.amount FROM tbl_additional INNER JOIN tbl_additional_employee ON tbl_additional.additional_id = tbl_additional_employee.additional_id INNER JOIN tbl_user ON tbl_user.user_id = tbl_additional_employee.employee_id WHERE tbl_additional.additional_status = 1 AND tbl_additional_employee.`status` = 0 AND tbl_user.employee_id = "'.$emp_id.'" ');
		return $query->result();
	}

	public function get_deductions_employee($emp_id) {
		$query = $this->db->query('SELECT tbl_deduction.deduction_title, tbl_deduction.amount FROM tbl_deduction INNER JOIN tbl_deduction_employee ON tbl_deduction.deduction_id = tbl_deduction_employee.deduction_id INNER JOIN tbl_user ON tbl_deduction_employee.employee_id = tbl_user.user_id WHERE tbl_deduction.deduction_status = 1 AND tbl_deduction_employee.`status` = 0 AND tbl_user.employee_id = "'.$emp_id.'"');
		return $query->result();
	}


}

//return $query->result_array();
/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */