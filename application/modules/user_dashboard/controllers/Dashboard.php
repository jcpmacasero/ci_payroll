<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	
		page_redirect();

		$this->page = "Dashboard";
	}
	
	public function index() {
		if (user_login()) {
			$this->middle = "user_dashboard/Dashboard";
			$this->user_layout();
		} else {
			page_not_found();
		}
	}

}

/* End of file Dashboard.php */
/* Location: ./application/modules/user_dashboard/controllers/Dashboard.php */