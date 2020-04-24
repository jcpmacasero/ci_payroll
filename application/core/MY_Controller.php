<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Controller class */
require APPPATH."third_party/MX/Controller.php";

class MY_Controller extends MX_Controller {
	public $page = "";
	public $data = array();
	public $template  = array();
	public $middle = "";
	public $ibox = false;
	public $datatable_script = false;
	public $file_drop_script = false;

	// for ibox
	public $ibox_data = array();
	public $ibox_id = "";
	public $ibox_header = "";
	public $ibox_tools = array();

	// public layout
	public function public_layout() {
		$this->data["page"] = $this->page;
		$this->template['header_script'] = $this->load->view('template/public/Header_script', $this->data, true);
		$this->template['middle'] = $this->load->view($this->middle, $this->data, true);
		$this->template['footer_script'] = $this->load->view('template/public/Footer_script', $this->data, true);
		return $this->load->view('template/public/Page', $this->template);
	}

	// admin layout
	public function admin_layout() {
		$this->data["page"] = $this->page;
		$this->data["ibox"] = $this->ibox;
		$this->data["datatable_script"] = $this->datatable_script;
		$this->data["file_drop_script"] = $this->file_drop_script;
		$this->data["ibox_id"] = $this->ibox_id;
		$this->data["ibox_header"] = $this->ibox_header;
		$this->data["ibox_tools"] = $this->ibox_tools;
		$this->template['header_script'] = $this->load->view('template/admin/Header_script', $this->data, true);
		$this->template['side_menu'] = $this->load->view('template/admin/Side_menu', $this->data, true);
		$this->template['header'] = $this->load->view('template/admin/Header', $this->data, true);
		$this->template['middle'] = $this->load->view($this->middle, $this->data, true);
		$this->template['footer'] = $this->load->view('template/admin/Footer', $this->data, true);
		$this->template['footer_script'] = $this->load->view('template/admin/Footer_script', $this->data, true);

		return $this->load->view('template/admin/Page', $this->template);
	}

	// user layout
	public function user_layout() {
		$this->data["page"] = $this->page;
		$this->data["permission"] = $this->setup->check_user_permission($this->page);
		$this->data["ibox"] = $this->ibox;
		$this->data["datatable_script"] = $this->datatable_script;
		$this->data["ibox_id"] = $this->ibox_id;
		$this->data["ibox_header"] = $this->ibox_header;
		$this->data["ibox_tools"] = $this->ibox_tools;
		$this->template['header_script'] = $this->load->view('template/user/Header_script', $this->data, true);
		$this->template['side_menu'] = $this->load->view('template/user/Side_menu', $this->data, true);
		$this->template['header'] = $this->load->view('template/user/Header', $this->data, true);
		$this->template['middle'] = $this->load->view($this->middle, $this->data, true);
		$this->template['footer'] = $this->load->view('template/user/Footer', $this->data, true);
		$this->template['footer_script'] = $this->load->view('template/user/Footer_script', $this->data, true);

		return $this->load->view('template/user/Page', $this->template);
	}
}