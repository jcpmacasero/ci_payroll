<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_check_dtr extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		page_redirect();
		$this->load->model('Admin_payroll_model','payroll');
		$this->load->library('form_validation');
		$this->page = "File Upload";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;
			$this->file_drop_script = true;
			$this->ibox = true;
			$this->ibox_header = "Check DTR";
			$this->ibox_id = "ibox_files_uploaded";
			$this->middle = "admin_payroll/Admin_check_dtr";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	public function getdtr($start,$end) {
		if(admin_login()) {		
			$emp_id = $this->input->post('employee_id');						
			$data_check = $this->payroll->check_dtr($emp_id,$start,$end);			
			foreach ($data_check as $value) {								
	            $row = array();  
	            $row[] = check_date($value->time, "m/d/Y h:i:s");
	            if($value->status == 1) {
	            	$row[] = "Time In";
	            }else if($value->status == 2) {
	            	$row[] = "Time Out";
	            }
	            
	            $data[] = $row;
	        }

	        $output = array(   
	            "data" => $data,
	        );
	        echo json_encode($output);
		}else {
			page_not_found();
		}
	}

	public function data_emp($employee_id) {
		if(admin_login()) {	
			$emp_data = $this->payroll->employee_details($employee_id);
			echo json_encode($emp_data);
		}else {
			page_not_found();
		}
	}

	public function addDailyRecord() {
		if(admin_login()) {
			if ($this->form_validation->run('check_dtr') == FALSE) {         
				$result['status'] = false;
	            $result['message'] = $this->form_validation->error_array();
			}else {
				if($this->input->post('emp_id') != NULL) {
					$result['status'] = true;
					$date_rec = check_date($this->input->post('record_date'),'Y-m-d');
					$time_rec = date("H:i",strtotime($this->input->post('time_in')));
					$concat_date_time = date('Y-m-d H:i:s', strtotime("$date_rec $time_rec"));

					$data_record = array (
						'employee_id' => $this->input->post('emp_id'),
						'time' => $concat_date_time,
						'status' => $this->input->post('record_stat')
					);
					$att_insert = $this->payroll->insert_att_record($data_record);
				}else {
					$data_error = array (
							'employee_id'	=>	'Employee ID is required',					
					);							
					$result['status'] = false;
					$result['message'] = $data_error;
				}
				
			}
			echo json_encode($result);
		}else {
			page_not_found();
		}
	}
}

/* End of file Dashboard.php */
/* Location: ./application/modules/admin_dashboard/controllers/Dashboard.php */