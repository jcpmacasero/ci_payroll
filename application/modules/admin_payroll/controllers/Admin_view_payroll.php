<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_view_payroll extends MY_Controller {

	private $data_array = array();
	
	public function __construct() {
		parent::__construct();
		page_redirect();
		$this->load->model('Admin_payroll_model','payroll');
		$this->load->library('form_validation');
		$this->page = "View Payroll";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;
			$this->file_drop_script = true;
			$this->ibox = true;
			$this->ibox_header = "View Payroll";
			$this->ibox_id = "ibox_view_payroll";
			$this->middle = "admin_payroll/Admin_view_payroll";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}
	//otrohonnnnnnnnn
	public function get_payroll() {
		if(admin_login()) {
			$status = $this->input->post('salary_status');
			$dates_inc = $this->input->post('date_inclusive');
			// 5 - 20
			if($dates_inc == 0) {
				$start = date('Y-m-05',time());			
				$end = date('Y-m-20',time());
				// $start = "2019-09-05";
				// $end = "2019-09-20";				
				$salaries = $this->payroll->getSalaries($status,$start,$end);				
				foreach ($salaries as $key => $salary) {		
						$deductions = $this->get_deductions($salary->employee_id);
						$additionals = $this->get_additionals($salary->employee_id);
						$name = $salary->firstname . " " . $salary->middlename . " " . $salary->lastname . " " . $salary->name_ext;
						$row = array();  
				            $row[] = $salary->employee_id;
				            $row[] = $name;
				            $row[] = (($salary->total + $additionals) - $deductions);
				            $row[] = '<button onclick="view_payslip('."'".$salary->employee_id."'".','."'".$start."'".','."'".$end."'".','."'".$name."'".','."'".$status."'".','."'".$dates_inc."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="View Payslip" id="btnR2"><i class="fa fa-file"></i></i></button>';	            	           

				            $data[] = $row;

				        array_push($this->data_array,["employee_id" => $salary->employee_id, "salary_status" => $status, "date_start_paid" => $start, "date_end_paid" => $end, "salary_amount" => (($salary->total + $additionals) - $deductions)]);			        
				}	
				$output = array(   
				            "data" => $data,
				);
				$insert_batch = $this->payroll->insert_batch_paid_salaries($this->data_array);
				echo json_encode($output);					
			}
			// 21 - 4
			else if($dates_inc == 1) {
				$start = date('Y-m-21',time());				
				$end = date("Y-m-04", strtotime("+1 month"));

				$salaries = $this->payroll->getSalaries($status,$start,$end);				
				foreach ($salaries as $key => $salary) {								
						$name = $salary->firstname . " " . $salary->middlename . " " . $salary->lastname . " " . $salary->name_ext;
						$row = array();  
				            $row[] = $salary->employee_id;
				            $row[] = $name;
				            $row[] = $salary->total;
				            $row[] = '<button onclick="view_payslip('."'".$salary->employee_id."'".','."'".$start."'".','."'".$end."'".','."'".$name."'".','."'".$status."'".','."'".$dates_inc."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="View Payslip" id="btnR2"><i class="fa fa-file"></i></i></button>';	            	           

				            $data[] = $row;

				        array_push($this->data_array,["employee_id" => $salary->employee_id, "salary_status" => $status, "date_start_paid" => $start, "date_end_paid" => $end, "salary_amount" => $salary->total]);			        
				}	
				$output = array(   
				            "data" => $data,
				);
				$insert_batch = $this->payroll->insert_batch_paid_salaries($this->data_array);
				echo json_encode($output);

			}		
		} else {
			page_not_found();
		}
	}

	private function get_deductions($emp_id) {
		$total_deduction = $this->payroll->getDeduction($emp_id);
		if(!$total_deduction) {
			$total_deduction = 0;
		}
		return $total_deduction;
	}

	private function get_additionals($emp_id) {
		$total_additional = $this->payroll->getAdditional($emp_id);
		if(!$total_additional) {
			$total_additional = 0;
		}
		return $total_additional;
	}

	public function already_paid() {
		if(admin_login()) {
			$status = $this->input->get('salary_status');
			$dates_inc = $this->input->get('date_inclusive');
			if($dates_inc == 0) {
				$start = date('Y-m-05',time());			
				$end = date('Y-m-20',time());
			}else if($dates_inc == 1) {
				$start = date('Y-m-21',time());				
				$end = date("Y-m-04", strtotime("+1 month"));								
			}
			
			$check_if_already_paid = $this->payroll->check_if_paid($status,$start,$end);
			if(!$check_if_already_paid) {
				$check_if_already_paid = 0;
			}
			$json['result'] = $check_if_already_paid;
			echo json_encode($json);
		}
		else {
			page_not_found();
		}
		
	}

	public function get_payslip_daily() {
		if(admin_login()) {
			$employee_id = $this->input->get('employee_id');
			$start_date = $this->input->get('start_date');
			$end_date = $this->input->get('end_date');
			$date_inclusive = $this->input->get('date_inclusive');

			$get_all_details_daily = $this->payroll->get_details($employee_id,$start_date,$end_date);
			if($date_inclusive == 0) {
				$get_additionals_daily = $this->payroll->get_additionals_employee($employee_id);
				$get_deductions_daily = $this->payroll->get_deductions_employee($employee_id);

				$array_data = array (
					"get_all_details_daily" => $get_all_details_daily,
					"get_additionals_daily" => $get_additionals_daily,
					"get_deductions_daily" => $get_deductions_daily
				);	
				echo json_encode($array_data);			
			}else if($date_inclusive == 1) {
				echo json_encode($get_all_details_daily);
			}			
		}else {
			page_not_found();
		}
	}

	public function get_payslip_fixed() {
		if(admin_login()) {
			$employee_id = $this->input->get('employee_id');
			$start_date = $this->input->get('start_date');
			$end_date = $this->input->get('end_date');
			$date_inclusive = $this->input->get('date_inclusive');
			$get_all_details_fixed = $this->payroll->get_details_fixed($employee_id,$start_date,$end_date);
			if($date_inclusive == 0) {
				$get_additionals_fixed = $this->payroll->get_additionals_employee($employee_id);
				$get_deductions_fixed = $this->payroll->get_deductions_employee($employee_id);

				$array_data_fixed = array (
					"get_all_details_fixed" => $get_all_details_fixed,
					"get_additionals_fixed" => $get_additionals_fixed,
					"get_deductions_fixed" => $get_deductions_fixed
				);				
				echo json_encode($array_data_fixed);
			}else if($date_inclusive == 1) {
				echo json_encode($get_all_details_fixed);
			}
			

			
		}else {
			page_not_found();
		}
	}


	
}

/* End of file Admin_view_payroll.php */
/* Location: ./application/modules/admin_payroll/controllers/Admin_view_payroll.php */