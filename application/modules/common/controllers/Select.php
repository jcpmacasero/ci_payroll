<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Select extends MY_Controller {

	public function __construct() {
		parent::__construct();
	
		page_redirect();

		$this->load->model('user_model', 'user');
		$this->load->model('family_background_model', 'family_background');
		$this->load->model('spouse_model', 'spouse');
		$this->load->model('educational_background_model', 'educational_background');
		$this->load->model('work_experience_model', 'work_experience');
		$this->load->model('setup_model', 'setup');
		
		$this->page = "Employee";
	}

	public function get_city_by_province_select() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		$data = [];

		$data = $this->setup->get_city_by_province_list(clean_string($this->input->post("id")));
		
		echo json_encode($data);
	}

	public function get_position_by_department_select() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		$data = [];

		$data = $this->setup->get_position_by_department_list(clean_string($this->input->post("id")));
		
		echo json_encode($data);
	}

}

/* End of file Select.php */
/* Location: ./application/modules/common/controllers/Select.php */