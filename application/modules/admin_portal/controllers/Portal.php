<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends MY_Controller {

	public function __construct() {
		parent::__construct();

		page_redirect_home();

		$this->page = "Admin Login";
	}

	public function index() {
		$this->middle = "admin_portal/Portal";
		$this->public_layout();
	}

	public function request_login() {
		$email = sanitize(html_purify($this->input->post('email')));
		$password = $this->input->post('password');

		$this->load->model('admin_model', 'admin');
		$this->load->library('bcrypt');

        $select = "admin_id, name, photo, password";
        $where = [
            "email"         => $email,
            "delete_status" => 0
        ];

        $query = $this->admin->select($select, $where);

        if ($this->bcrypt->check_password($password, $query[0]->password)) {
            $this->admin->update(["last_login_date" => now()], ["admin_id" => $query[0]->admin_id]);

            $this->session->set_userdata('admin_login', true);
            $this->session->set_userdata('login_id', md5('a'.$query[0]->admin_id));
            $this->session->set_userdata('admin_name', $query[0]->name);
            $this->session->set_userdata('admin_photo', $query[0]->photo);
            $this->session->set_userdata('admin_id', $query[0]->admin_id);
            $this->session->set_userdata('login_date', now());

            redirect(base_url('admin/dashboard'));
        } else {
            redirect(base_url('admin?login_attempt='.md5(0)));
        }
	}
}

/* End of file Portal.php */
/* Location: ./application/modules/admin_portal/controllers/Portal.php */