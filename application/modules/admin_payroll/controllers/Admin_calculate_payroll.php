<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_calculate_payroll extends MY_Controller {

	private $schedules = array();
	private $attendance = array();
	private $salary_details = array();
	private $rest_day = array();
	private $salary = 0;
	private $cola_duration = 0;
	private $total_cola_salary = 0;

	//data array consist of (employee_id,salary,late_duration[minutes],undertime_duration[minutes],cola_duration,daily_date) for per date salary
	private $data_array = array();
	//data array consist of (employee_id,daily_date) for fixed salary	
	private $data_emp = array();
	private $data_overtime = array();
	private $overtime_ready = 0;


	
	public function __construct() {
		parent::__construct();	
		page_redirect();
		$this->load->model('Admin_payroll_model','payroll');
		$this->page = "Calculate Payroll";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;			
			$this->ibox = true;
			$this->ibox_header = "Calculate payroll";
			$this->ibox_id = "ibox_files_uploaded";
			$this->middle = "admin_payroll/Admin_calculate_payroll";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	/*holidays
		returns {
			2 - if double pay
			1 - if 30% pay			
			0 - Normal
		}
	*/
	private function get_calendar_rate($date_duty) {
		$date_holiday = $this->payroll->get_calendar_event($date_duty);		
		if($date_holiday == 2) {
			return 2;
		}else if($date_holiday == 1) {
			return 1;
		}else {
			return 0;
		}
	}

	//returns late in minutes
	private function compute_late($time_in,$late_time) {
		$duty = new DateTime(date("H:i",strtotime($time_in)));
		$late = new DateTime(date("H:i",strtotime($late_time)));		
		$interval = $duty->diff($late);
		$hours   = $interval->format('%h'); 
		$minutes = $interval->format('%i');
		$diff = ($hours * 60 + $minutes);

		return $diff; 
	}	

	//returns undertime in minutes
	private function compute_undertime($time_in,$undertime_out) {
		$duty = new DateTime(date("H:i",strtotime($time_in)));
		$undertime = new DateTime(date("H:i",strtotime($undertime_out)));
		$interval = $undertime->diff($duty);
		$hours   = $interval->format('%h'); 
		$minutes = $interval->format('%i');
		$diff = ($hours * 60 + $minutes);

		return $diff;
	}


	//status
	// 1 - rest day
	// 0 - regular day	
	private function if_exist_in_rest_day(array $myArray, $day) {
		foreach ($myArray as $element) {
			if($element->rest_day == $day) {
				return 1;
			}			
		}
		return 0;		
	}

	//compute time in ug time out karon
	private function timein_now_timeout_now($id,$start_date,$end_date,$time_in,$time_out,$cola_duration,$calculate) {					
		if($cola_duration != 0) {
			$this->total_cola_salary = (($this->salary * 0.1)/8)*($cola_duration);			
		}		
		$this->attendance = $this->payroll->get_attendance_same_now_end($id,$start_date,$end_date);
		$this->rest_day = $this->payroll->get_rest_day($id,$start_date,$end_date);		
				
		foreach ($this->attendance as $key => $perdate) {						
			$rest = $this->if_exist_in_rest_day($this->rest_day,$perdate->time);
			if($rest == 1) {
				$check_duty_rest = $this->payroll->get_duty_rest($id,$perdate->time);
				//duty rest/premiun-pay
				if($check_duty_rest == 1) {
					$premium_pay = $this->salary * 0.3;
						$date_status = $this->get_calendar_rate($perdate->time);			
						$time_duty = $this->payroll->getTimeinByDate($perdate->time,$id);						
						//kung late sya			
						if(date("H:i",strtotime($time_duty[0]->time)) > date("H:i",strtotime($time_in)) ) {				
							$late_diff = $this->compute_late($time_duty[0]->time,$time_in);
							$deduct_late = ($this->salary/8)/60;
							$total_deduct_late = $deduct_late * $late_diff;
							//if late sya unya undertime pa gyod
							$total_deduct_undertime = 0;
							$undertime_diff = 0;
							if(date("H:i:s",strtotime($time_duty[1]->time)) < date("H:i",strtotime($time_out))) {
								$undertime_diff = $this->compute_undertime($time_duty[1]->time,$time_out);
								$deduct_undertime = ($this->salary/8)/60;
								$total_deduct_undertime = $deduct_undertime * $undertime_diff;
							}								
							switch ($date_status) {
								case 0:
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
									break;						
								case 1:
									$this->salary = ($this->salary * 0.3) + $this->salary;
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
									break;
								case 2:
									$this->salary = ($this->salary * 2) + $this->salary;
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
									break;
							}				
						}
						//kung undertime sya
						else if(date("H:i:s",strtotime($time_duty[1]->time)) < date("H:i",strtotime($time_out))) {
							//print_r("hello undertime");				
							$undertime_diff = $this->compute_undertime($time_duty[1]->time,$time_out);
							$deduct = ($this->salary/8)/60;
							$total_deduct = $deduct * $undertime_diff;
							switch ($date_status) {
								case 0:
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
									break;						
								case 1:
									$this->salary = ($this->salary * 0.3) + $this->salary;
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
									break;
								case 2:
									$this->salary = ($this->salary * 2) + $this->salary;
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
									break;
							}
						}
						//kung wlay late
						else {				
							// print_r("hello walay late");
							switch ($date_status) {
								case 0:
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary + $premium_pay), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
									break;						
								case 1:
									$this->salary = ($this->salary * 0.3) + $this->salary;
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary + $premium_pay), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
									break;
								case 2:
									$this->salary = ($this->salary * 2) + $this->salary;
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary + $premium_pay), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
									break;
							}
						}
				}
			}else if($rest == 0) {
					$date_status = $this->get_calendar_rate($perdate->time);			
					$time_duty = $this->payroll->getTimeinByDate($perdate->time,$id);						
					//kung late sya			
					if(date("H:i",strtotime($time_duty[0]->time)) > date("H:i",strtotime($time_in)) ) {				
						$late_diff = $this->compute_late($time_duty[0]->time,$time_in);
						$deduct_late = ($this->salary/8)/60;
						$total_deduct_late = $deduct_late * $late_diff;
						//if late sya unya undertime pa gyod
						$total_deduct_undertime = 0;
						$undertime_diff = 0;
						if(date("H:i:s",strtotime($time_duty[1]->time)) < date("H:i",strtotime($time_out))) {
							$undertime_diff = $this->compute_undertime($time_duty[1]->time,$time_out);
							$deduct_undertime = ($this->salary/8)/60;
							$total_deduct_undertime = $deduct_undertime * $undertime_diff;
						}								
						switch ($date_status) {
							case 0:
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => 0, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
								break;						
							case 1:
								$this->salary = ($this->salary * 0.3) + $this->salary;
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => 0, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
								break;
							case 2:
								$this->salary = ($this->salary * 2) + $this->salary;
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => 0, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
								break;
						}				
					}
					//kung undertime sya
					else if(date("H:i:s",strtotime($time_duty[1]->time)) < date("H:i",strtotime($time_out))) {
						//print_r("hello undertime");				
						$undertime_diff = $this->compute_undertime($time_duty[1]->time,$time_out);
						$total_deduct_late = 0;
						$deduct = ($this->salary/8)/60;
						$total_deduct = $deduct * $undertime_diff;
						switch ($date_status) {
							case 0:
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct)), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct, "cola_rate" => $this->total_cola_salary]);
								break;						
							case 1:
								$this->salary = ($this->salary * 0.3) + $this->salary;
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct)), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct, "cola_rate" => $this->total_cola_salary]);
								break;
							case 2:
								$this->salary = ($this->salary * 2) + $this->salary;
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct)), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct, "cola_rate" => $this->total_cola_salary]);
								break;
						}
					}
					//kung wlay late
					else {				
						// print_r("hello walay late");
						switch ($date_status) {
							case 0:
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
								break;						
							case 1:
								$this->salary = ($this->salary * 0.3) + $this->salary;
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
								break;
							case 2:
								$this->salary = ($this->salary * 2) + $this->salary;
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
								break;
						}
					}
			}		
		}
	}

	private function timein_now_timeout_ugma($id,$start_date,$end_date,$time_in,$time_out,$cola_duration,$calculate) {				
		if($cola_duration != 0) {
			$this->total_cola_salary = (($this->salary * 0.1)/8)*($cola_duration);			
		}	
		$this->attendance = $this->payroll->get_attendance_not_same_now_end($id,$start_date,$end_date);
		$this->rest_day = $this->payroll->get_rest_day($id,$start_date,$end_date);	

		foreach ($this->attendance as $key => $perdate) {
			$rest = $this->if_exist_in_rest_day($this->rest_day,$perdate->time);
			if($rest == 1) {
				$check_duty_rest = $this->payroll->get_duty_rest($id,$perdate->time);
				if($check_duty_rest == 1) {
					$premium_pay = $this->salary * 0.3;

					$date_status = $this->get_calendar_rate($perdate->time);			
					$time_duty = $this->payroll->getTimeinByDate($perdate->time,$id);						

					//kung late sya			
					if(date("H:i",strtotime($time_duty[0]->time)) > date("H:i",strtotime($time_in)) ) {								
						$late_diff = $this->compute_late($time_duty[0]->time,$time_in);
						$deduct_late = ($this->salary/8)/60;
						$total_deduct_late = $deduct_late * $late_diff;
						$total_deduct_undertime = 0;
						//kung late sya unya undertime pa gyod
						if(date("H:i:s",strtotime($time_duty[1]->time)) < date("H:i",strtotime($time_out))) {
							$undertime_diff = $this->compute_undertime($time_duty[1]->time,$time_out);
							$deduct_undertime = ($this->salary/8)/60;
							$total_deduct_undertime = $deduct_undertime * $undertime_diff;
						}								
						switch ($date_status) {
							case 0:
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
								break;						
							case 1:
								$this->salary = ($this->salary * 0.3) + $this->salary;
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
								break;
							case 2:
								$this->salary = ($this->salary * 2) + $this->salary;
								array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
								break;
						}					
						}
						//kung undertime
						else if(date("H:i:s",strtotime($time_duty[1]->time)) < date("H:i",strtotime($time_out))) {				
							$undertime_diff = $this->compute_undertime($time_duty[1]->time,$time_out);
							$deduct = ($this->salary/8)/60;
							$total_deduct = $deduct * $undertime_diff;
							switch ($date_status) {
								case 0:
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
									break;						
								case 1:
									$this->salary = ($this->salary * 0.3) + $this->salary;
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
									break;
								case 2:
									$this->salary = ($this->salary * 2) + $this->salary;
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary + $premium_pay) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
									break;
							}
						}
						//kung wla syay late ug undertime
						else {								
							switch ($date_status) {
								case 0:
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary + $premium_pay), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
									break;						
								case 1:
									$this->salary = ($this->salary * 0.3) + $this->salary;
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary + $premium_pay), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
									break;
								case 2:
									$this->salary = ($this->salary * 2) + $this->salary;
									array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary + $premium_pay), "premium_pay" => $premium_pay, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
									break;
							}
					}
				}
			}else if($rest == 0) {
				$date_status = $this->get_calendar_rate($perdate->time);			
				$time_duty = $this->payroll->getTimeinByDate($perdate->time,$id);						

				//kung late sya			
				if(date("H:i",strtotime($time_duty[0]->time)) > date("H:i",strtotime($time_in)) ) {								
					$late_diff = $this->compute_late($time_duty[0]->time,$time_in);
					$deduct_late = ($this->salary/8)/60;
					$total_deduct_late = $deduct_late * $late_diff;
					$total_deduct_undertime = 0;
					//kung late sya unya undertime pa gyod
					if(date("H:i:s",strtotime($time_duty[1]->time)) < date("H:i",strtotime($time_out))) {
						$undertime_diff = $this->compute_undertime($time_duty[1]->time,$time_out);
						$deduct_undertime = ($this->salary/8)/60;
						$total_deduct_undertime = $deduct_undertime * $undertime_diff;
					}								
					switch ($date_status) {
						case 0:
							array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => 0, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
							break;						
						case 1:
							$this->salary = ($this->salary * 0.3) + $this->salary;
							array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => 0, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
							break;
						case 2:
							$this->salary = ($this->salary * 2) + $this->salary;
							array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => 0, "late_duration" => $late_diff, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
							break;
					}					
				}
				//kung undertime
				else if(date("H:i:s",strtotime($time_duty[1]->time)) < date("H:i",strtotime($time_out))) {				
					$undertime_diff = $this->compute_undertime($time_duty[1]->time,$time_out);
					$deduct = ($this->salary/8)/60;
					$total_deduct = $deduct * $undertime_diff;
					switch ($date_status) {
						case 0:
							array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
							break;						
						case 1:
							$this->salary = ($this->salary * 0.3) + $this->salary;
							array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
							break;
						case 2:
							$this->salary = ($this->salary * 2) + $this->salary;
							array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => (($this->total_cola_salary + $this->salary) - ($total_deduct_late + $total_deduct_undertime)), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => $undertime_diff ,"cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => $total_deduct_late, "undertime_deduction" => $total_deduct_undertime, "cola_rate" => $this->total_cola_salary]);
							break;
					}
				}
				//kung wla syay late ug undertime
				else {								
					switch ($date_status) {
						case 0:
							array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
							break;						
						case 1:
							$this->salary = ($this->salary * 0.3) + $this->salary;
							array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
							break;
						case 2:
							$this->salary = ($this->salary * 2) + $this->salary;
							array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "salary" => ($this->total_cola_salary + $this->salary), "premium_pay" => 0, "late_duration" => 0, "undertime_duration" => 0, "cola_duration" => $this->cola_duration, "date" => $perdate->time, "late_deduction" => 0, "undertime_deduction" => 0, "cola_rate" => $this->total_cola_salary]);
							break;
					}
				}
			}
		}
		

	}

	//return cola boolean
	private function isBetween($from, $till, $input) {
        $f = DateTime::createFromFormat('!H:i', $from);
        $t = DateTime::createFromFormat('!H:i', $till);
        $i = DateTime::createFromFormat('!H:i', $input);
        if ($f > $t) $t->modify('+1 day');
        return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
	}

	//compare late and undertime and get the deductions from late and undertime.
	private function check_duty($id,$start_date,$end_date,$calculate) {
		// 0  = perday
		// 1 = fixed
		$this->salary_details = $this->payroll->get_salary_details($id);		
		$this->salary = $this->salary_details[0]->amount;							
		$this->schedules = $this->payroll->get_schedule($id,$start_date,$end_date);					
		$time = '17:00';		
		$cola_in = "22:00";
		$cola_out = "6:00";		

		//per day type salary
		if($this->salary_details[0]->salary_status == 0) {
			foreach ($this->schedules as $key => $schedule) {	
				//kung iyang schedule time in karon ug time out karon			
				if($schedule->time_in < $time  ) {										
					$sample = date("H:i",strtotime($schedule->time_in));
					$has_cola = $this->isBetween($cola_in,$cola_out,$sample);
					if($has_cola == 1) {
						$time_in_cola = new DateTime(date("H:i",strtotime($schedule->time_in)));
						$time_out_cola = new DateTime(date("H:i",strtotime($cola_out)));		
						$cola_hrs = $time_in_cola->diff($time_out_cola);
						$hours   = $cola_hrs->format('%h');
						$this->cola_duration = $hours;
					}
					$this->total_cola_salary = 0;
					$this->timein_now_timeout_now($id,$start_date,$end_date,$schedule->time_in,$schedule->time_out,$this->cola_duration,$calculate);
				}
				//kung iyang schedule time in karon ug time out ugma			
				else if($schedule->time_in > $time) {
					$sample = date("H:i",strtotime($schedule->time_in));
					$has_cola = $this->isBetween($cola_in,$cola_out,$sample);
					if($has_cola == 1) {
						$midnight = "23:00";
						$midnight1 = "24:00";
						$time_mid_cola = new DateTime(date("H:i",strtotime($midnight)));
						$time_in_cola = new DateTime(date("H:i",strtotime($schedule->time_in)));
						$time_out_cola = new DateTime(date("H:i",strtotime($cola_out)));		
						$cola_hrs = $time_in_cola->diff($time_mid_cola);
						$hours = $cola_hrs->format('%h') + 1;						
						$time_mid1_cola = new DateTime(date("H:i",strtotime($midnight1)));
						$cola_hrs1 = $time_mid1_cola->diff($time_out_cola);
						$hours1 = $cola_hrs1->format('%h');						
						$this->cola_duration = $hours + $hours1;											
					}
					$this->total_cola_salary = 0;
					$this->timein_now_timeout_ugma($id,$start_date,$end_date,$schedule->time_in,$schedule->time_out,$this->cola_duration,$calculate);
				}
			}

		//fixed type salary		
		}else if($this->salary_details[0]->salary_status == 1) {						
			foreach ($this->schedules as $key => $schedule) {				
				//kung iyang schedule time in karon ug time out karon			
				if($schedule->time_in < $time  ) {	
					$this->attendance = $this->payroll->get_attendance_same_now_end($id,$start_date,$end_date);				
					foreach ($this->attendance as $key => $perdate) {
						array_push($this->data_array,["employee_id" => $id, "calculate_id" => $calculate, "date" => $perdate->time]);
					}
					// $emp_name_by_id = array_column($this->data_emp, 'name', 'employee_id');
					foreach ($this->data_emp as &$row) {
						if($row['employee_id'] == $id) {
							 $row['salary'] = $this->salary_details[0]->amount;
						}					    
					}					
				}
			} 
		} 
			
	}

	public function compute_payroll($calc_status) {
	$emp = array();
		if (admin_login()) {
			$start_date = check_date($this->input->post('date_start'), "Y-m-d");
			$end_date = check_date($this->input->post('date_end'), "Y-m-d");
			$salary_status = $this->input->post('salary_status');		
			$calc_stat = $calc_status;				
			
			$emp = $this->payroll->get_all_emp($salary_status);
			if($calc_stat == -1) {
				$calculate = $this->payroll->record_calculate_date($start_date,$end_date,$salary_status);				
			}			
			else if($calc_stat >= 0) {
				$update_calculate = $this->payroll->update_calculate_date($calc_stat,$salary_status,$start_date,$end_date);
				$calculate = $this->payroll->record_calculate_date($start_date,$end_date,$salary_status);					
			}
			
			foreach ($emp as $key => $value) {
				$name = $value->firstname . " " . $value->middlename . " " . $value->lastname . " " . $value->name_ext;		
				array_push($this->data_emp, ["employee_id" => $value->employee_id, "name" => $name]);				
				$this->check_duty($value->employee_id,$start_date,$end_date,$calculate);				
			}

			//overtime
			$cola_in = "22:00";
			$cola_out = "6:00";		
			$cola_maxout = "8:00";
			foreach ($this->data_array as $key => $value) {							
				$overtime_status = $this->payroll->check_overtime_by_emp_id($value['employee_id'],$value['date']);			
				if($overtime_status!= 0) {
					//check if completo timein ug timeout					
					$check_if_complete = $this->payroll->check_overtime_duty($value['employee_id'],check_date($overtime_status[0]->overtime_in, "Y-m-d"),check_date($overtime_status[0]->overtime_out, "Y-m-d"));					
					if($check_if_complete != 0) {
						$time_in_overtime = date("H:i",strtotime($overtime_status[0]->overtime_in));
						$time_out_overtime = date("H:i",strtotime($overtime_status[0]->overtime_out));
						if($time_in_overtime >= date("H:i",strtotime($cola_in)) || $time_in_overtime < date("H:i",strtotime($cola_out)))  {
							if($time_out_overtime > date("H:i",strtotime($cola_out)) && $time_out_overtime <= date("H:i",strtotime($cola_maxout)) ) {
								$this->overtime_ready = 1;
								$time_out_cola = new DateTime(date("H:i",strtotime($cola_out)));
								$time_in_overtime = new DateTime(date("H:i",strtotime($overtime_status[0]->overtime_in)));
								$with_cola = $time_in_overtime->diff($time_out_cola);
								$hours_with_cola = $with_cola->format('%h');
								$cola_minutes_duration = $hours_with_cola * 60;
								$overtime_rate_per_minute = (($this->salary_details[0]->amount/8)*0.25) + ($this->salary_details[0]->amount/8);
								$overtime_rate_per_minute = ($overtime_rate_per_minute/60);
								$overtime_rate_partial = $overtime_rate_per_minute * $cola_minutes_duration;
								
								$without_cola = $overtime_status[0]->overtime_duration_min - $cola_minutes_duration;
								$overtime_rate_per_minute1 = ($this->salary_details[0]->amount/8)/60;	
								$overtime_rate_partial1 = $overtime_rate_per_minute1 * $without_cola;

								$overtime_rate = $overtime_rate_partial + $overtime_rate_partial1;
								$temp_salary = $overtime_rate + $value['salary'];
								$value['salary'] = $temp_salary;
								array_push($this->data_overtime,["employee_id" => $value['employee_id'], "calculate_id" => $value['calculate_id'], "date_overtime" => check_date($overtime_status[0]->overtime_in, "Y-m-d"), "duration_mins" => $without_cola, "duration_mins_cola" => $cola_minutes_duration, "amount_paid" => $overtime_rate]);
							}else {
								$this->overtime_ready = 1;
								$overtime_rate_per_minute = (($this->salary_details[0]->amount/8)*0.25) + ($this->salary_details[0]->amount/8);
								$overtime_rate_per_minute = ($overtime_rate_per_minute/60);
								$overtime_rate = $overtime_rate_per_minute * $overtime_status[0]->overtime_duration_min;
								$overtime_rate = $overtime_rate_partial + $overtime_rate_partial1;
								array_push($this->data_overtime,["employee_id" => $value['employee_id'], "calculate_id" => $value['calculate_id'], "date_overtime" => check_date($overtime_status[0]->overtime_in, "Y-m-d"), "duration_mins" => 0, "duration_mins_cola" => $overtime_status[0]->overtime_duration_min, "amount_paid" => $overtime_rate]);
							}
						}else {
							$this->overtime_ready = 1;
							$overtime_rate_per_minute = ($this->salary_details[0]->amount/8)/60 ;
							$overtime_rate = $overtime_rate_per_minute * $overtime_status[0]->overtime_duration_min;
							array_push($this->data_overtime,["employee_id" => $value['employee_id'], "calculate_id" => $value['calculate_id'], "date_overtime" => check_date($overtime_status[0]->overtime_in, "Y-m-d"), "duration_mins" => $overtime_status[0]->overtime_duration_min, "duration_mins_cola" => 0, "amount_paid" => $overtime_rate]);
							
						}
					}
				}
			}

			switch ($salary_status) {
				case 0:										
					$insert_daily = $this->payroll->insert_daily_salary_per_day($this->data_array);	
					if($this->overtime_ready == 1) {
						$insert_overtime = $this->payroll->overtime_insert($this->data_overtime);
					}
					$display_data = $this->payroll->get_salary_report_daily($start_date,$end_date);					
					foreach ($display_data as $emp) {
						$name = $emp->firstname . " " . $emp->middlename . " " . $emp->lastname . " " . $emp->name_ext;
						$row = array();  
			            $row[] = $emp->employee_id;
			            $row[] = $name;
			            $row[] = $emp->total;
			            $row[] = '<button onclick="view_details('."'".$emp->employee_id."'".','."'".$start_date."'".','."'".$end_date."'".','."'".$name."'".','."'".$salary_status."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="View details" id="btnR2"><i class="fa fa-file"></i></i></button>';	            	           
			            $data[] = $row;
					}

					$output = array(   
			            "data" => $data,
			        );
			        echo json_encode($output); 
					break;

				case 1:
					// print_r($this->data_array);
					$insert_daily = $this->payroll->insert_fixed_salary($this->data_array);					
					foreach ($this->data_emp as $emp) {				
			            $row = array();  
			            $row[] = $emp['employee_id'];
			            $row[] = $emp['name'];
			            $row[] = $emp['salary'];
			            $row[] = '<button onclick="view_details('."'".$emp['employee_id']."'".','."'".$start_date."'".','."'".$end_date."'".','."'".$name."'".','."'".$salary_status."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="View details" id="btnR2"><i class="fa fa-file"></i></i></button>';	            	           

			            $data[] = $row;
			        }

			        $output = array(   
			            "data" => $data,
			        );
			        echo json_encode($output);
					break;
			}	
		}else {
			page_not_found();
		}

	}


	//true - kung naay data
	//false - kung walay data
	public function check_data() {
		if (admin_login()) { 
			$json = array();
			$nstart_date = check_date($this->input->get('date_start'), "Y-m-d");
			$nend_date = check_date($this->input->get('date_end'), "Y-m-d");
			$data_check = $this->payroll->check_data_between_date($nstart_date,$nend_date);		
			if($data_check <= 0) {
				$json['result'] = false;
			}else {
				$json['result'] = true;
			}
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($json);
		} else {
			page_not_found();
		}		
	}

	//false kung wala, true kung exist
	public function check_calculate() {
		if (admin_login()) {
			$json = array();
			$check_status = $this->input->get('salary_status');
			$calc_date_start = check_date($this->input->get('date_start'), "Y-m-d");
			$calc_date_end = check_date($this->input->get('date_end'), "Y-m-d");				
			$check = $this->payroll->check_if_already_calculated($calc_date_start,$calc_date_end,$check_status);			
			$json['result'] = $check;
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($json);
		} else {
			page_not_found();
		}
	}

	public function show_already_calculated() {
		if (admin_login()) {
			$json = array();
			$already_stat = $this->input->post('status_salary');
			$already_start = check_date($this->input->post('date_start'), "Y-m-d");
			$already_end = check_date($this->input->post('date_end'), "Y-m-d");			
			$getCalculated = $this->payroll->already_calculated($already_start,$already_end,$already_stat);
			if(!$getCalculated) {
				echo "No data available";
			}else {
				foreach ($getCalculated as $emp) {										
						$name = $emp->firstname . " " . $emp->middlename . " " . $emp->lastname . " " . $emp->name_ext;
			            $row = array();			              
			            $row[] = $emp->employee_id;
			            $row[] = $name;
			            $row[] = $emp->salary;
			            $row[] = '<button onclick="view_details('."'".$emp->employee_id."'".','."'".$already_start."'".','."'".$already_end."'".','."'".$name."'".','."'".$already_stat."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="View Details" id="btnR2"><i class="fa fa-file"></i></i></button>';	            	           
			            $data[] = $row;
			        }

			        $output = array(   
			            "data" => $data,
			        );
			        echo json_encode($output);
			}
		} else {
			page_not_found();
		}
	}

	public function getAllCalculated() {
		if (admin_login()) {
			$start = $this->input->get('start');
			$end = $this->input->get('end');
			$events = $this->payroll->getCalculatedDates($start,$end);			
		}else {
			page_not_found();
		}
	}

	public function get_details_calculate() {
		if(admin_login()) {
			$employee_id = $this->input->get('employee_id');
			$start_date = $this->input->get('start_date');
			$end_date = $this->input->get('end_date');
			$get_all_details = $this->payroll->get_details($employee_id,$start_date,$end_date);			
			echo json_encode($get_all_details);
		}else {
			page_not_found();
		}
	}

	public function get_details_calculate_fixed() {
		if(admin_login()) {
			$employee_id = $this->input->get('employee_id');
			$start_date = $this->input->get('start_date');
			$end_date = $this->input->get('end_date');
			$get_all_details_fixed = $this->payroll->get_details_fixed($employee_id,$start_date,$end_date);			
			echo json_encode($get_all_details_fixed);
		}else {
			page_not_found();
		}
	}
}

/* End of file Admin_view_payroll.php */
/* Location: ./application/modules/admin_dashboard/controllers/Admin_view_payroll.php */

//http://localhost/ci_payroll/admin_payroll/Admin_view_payroll/compute_payroll/2019-09-08/2019-09-21

/*
algo
1. Kung iyang time-in between sa schedule - ok
2. Date sa time-in nga status 1 pangitaon niya ang status 2
	-if time-in is 5:00pm upwards ,then plus 1 date ang status 2
	-if time-in is 5:00pm below ,then equal date ang status 1 and 2
	-if time-in ra ang naa, then walay add sa number of duty ug salary.
	-if time-out ra ang naa, then walay add sa number of duty ug salary.
3. Check if naay late ug undertime
	- allowance nga 15 minutes sa time-in
4. Insert database ang undertime/late.
5. Check if ang date range naay holiday x2 salary or 30% sa salary.
6. Insert sa database ang final na salary per day.
7. Reset tanan.


questions
1. if fixed iyang salary then deductions nalang ang e compute ?
2. pila ang dugang sa salary if gabii ang duty ?
3. unsa nga mga schedules ang naay time-in date now unya time-out date ugma ?

*/


/*

to do list:
time in karon - time out karon
2. deductions 
3. additionals

*/

/*

1. tbl_duty_rest
2. tbl_overtime

*/