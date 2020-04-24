<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	
		page_redirect();
		
		$this->page = "Dashboard";
	}
	
	public function index() {
		if (admin_login()) {			
			$this->middle = "admin_dashboard/Dashboard";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}
}

/* End of file Dashboard.php */
/* Location: ./application/modules/admin_dashboard/controllers/Dashboard.php */