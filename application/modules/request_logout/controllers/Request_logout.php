<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_logout extends MY_Controller {

	public function admin_request_logout() {
		$array_logout = [
		    'login_id',
	        'admin_name',
	        'admin_photo',
			'admin_id',
	        'login_date',
	        'admin_login',
		];
		$this->session->unset_userdata($array_logout);
		$this->session->sess_destroy();
		redirect(base_url('/admin'));
	}

	public function user_request_logout() {
		$array_logout = [
		    'login_id',
	        'user_name',
	        'user_photo',
	        'department_id',
	        'position_id',
			'user_id',
	        'login_date',
	        'user_login',
		];
		$this->session->unset_userdata($array_logout);
		$this->session->sess_destroy();
		redirect(base_url('/'));
	}

}

/* End of file Request_logout.php */
/* Location: ./application/modules/request_logout/controllers/Request_logout.php */