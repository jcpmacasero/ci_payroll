<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_file_upload extends MY_Controller {

	private $data_array = array();
	
	public function __construct() {
		parent::__construct();
		page_redirect();
		//$this->load->model('Admin_payroll_history_model','history');		
		$this->page = "File Upload";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;
			$this->file_drop_script = true;
			$this->ibox = true;
			$this->ibox_header = "File upload";
			$this->ibox_id = "ibox_view_file_upload";
			$this->middle = "admin_payroll/Admin_file_upload";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}	
}

/* End of file Admin_view_payroll_history.php */
/* Location: ./application/modules/admin_payroll/controllers/Admin_view_payroll_history.php */