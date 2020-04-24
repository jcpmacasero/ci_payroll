<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_time_attendance extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	
		page_redirect();
		
		$this->page = "Time and Attendance";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;			
			$this->ibox = true;
			$this->ibox_header = "Time and attendance";
			$this->ibox_id = "ibox_files_uploaded";
			$this->middle = "admin_payroll/Admin_time_attendance";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}
}

/* End of file Dashboard.php */
/* Location: ./application/modules/admin_dashboard/controllers/Dashboard.php */