<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_Apply extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	
		page_redirect();		
		$this->load->model('leave_model','leave');
		$this->load->model('on_leave_model','on_leave');
		$this->load->library('form_validation');
		$this->page = "Apply Leave";
	}
	
	public function index() {
		if (admin_login()) {
			$this->data = [
				"leaves"		=>	$this->leave->get_leave_id_list(),				
			];
			$this->datatable_script = true;
			$this->ibox = true;
			$this->ibox_header = "List of Employee application leaves";
			$this->ibox_id = "ibox_application_leave";
			$this->middle = "admin_leave/Leave_Apply";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	public function apply_leave() {
		if (admin_login()) {			
				if ($this->form_validation->run('apply_leave') == FALSE) {
					$result['status'] = false;
	            	$result['message'] = $this->form_validation->error_array();  
				}else {							            
		            $check_emp_hired = $this->leave->checkDateHired($this->input->post('employee_id'));						
					if($check_emp_hired != NULL) {
						$total_emp_leave = $this->on_leave->getAllLeave($this->input->post('employee_id'),$this->input->post('leave_title'));    	            
						$leave_id_duration = $this->leave->getLeaveDuration($this->input->post('leave_title'));						
						$leave_available = $leave_id_duration - $total_emp_leave;
						$leave_available_after = $leave_id_duration - ($total_emp_leave + $this->input->post('apply_duration'));
						if($leave_available_after <=0 ) {
							$data_error = array (
								'apply_duration'	=>	'Employee have only "'.$leave_available.'" day(s) of leave left',			
								'leave_title'		=> 	'Employee has insufficient available leave',
							);							
							$result['status'] = false;
							$result['message'] = $data_error;	
						}else {
							$data = array (
				                'emp_id' => $this->input->post('employee_id'),                    
				                'leave_id' => $this->input->post('leave_title'),
				                'duration' => $this->input->post('apply_duration'),
				                'leave_start' => check_date($this->input->post('leave_date_start'), "Y-m-d"),
				                'leave_end' => check_date($this->input->post('leave_date_end'), "Y-m-d"),
				                'date_applied' => check_date($this->input->post('leave_date_apply'), "Y-m-d"),
				                'created_by' => admin_id(),
				                'modified_by' => admin_id(),
				            );				            
							$result['status'] = true;							
		           			$insert = $this->on_leave->insert_on_leave($data);
						} 
		           	} else {
		           		$data_error = array (
							'employee_id'	=>	'Employee must have 1 year service to apply leave',					
						);							
						$result['status'] = false;
						$result['message'] = $data_error;
		           	}
				}	
		} else {
			page_not_found();
		}
		echo json_encode($result);
	}

				
}

/* End of file Leave_Apply.php */
/* Location: ./application/modules/admin_dashboard/controllers/Leave_Apply.php */