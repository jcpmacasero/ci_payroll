<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends MY_Controller {

	public function __construct() {
		parent::__construct();

		page_redirect_home();

		$this->page = "Login";
	}

	public function index() {
		$this->middle = "user_portal/Portal";
		$this->public_layout();
	}

	public function request_login() {
		$email = sanitize(html_purify($this->input->post('email')));
		$password = $this->input->post('password');

		$this->load->model('user_model', 'user');
		$this->load->library('bcrypt');

        $select = "user_id,firstname,lastname,photo,password,department_id,tbl_user.position_id";
        $where = [
            "email"                     => $email,
            "user_status"               => "Activated",
            "tbl_user.delete_status"    => 0
        ];
        $join = [
            "tbl_position" => "tbl_user.position_id = tbl_position.position_id"
        ];

        $query = $this->user->select($select, $where, $join);

        if ($this->bcrypt->check_password($password, $query[0]->password)) {
            $this->user->update(["last_login_date" => now()], ["user_id" => $query[0]->user_id]);

            $this->session->set_userdata('user_login', true);
            $this->session->set_userdata('login_id', md5('u'.$query[0]->user_id));
            $this->session->set_userdata('user_name', $query[0]->firstname);
            $this->session->set_userdata('user_photo', $query[0]->photo);
            $this->session->set_userdata('user_id', $query[0]->user_id);
            $this->session->set_userdata('department_id', $query[0]->department_id);
            $this->session->set_userdata('position_id', $query[0]->position_id);
            $this->session->set_userdata('login_date', now());

            redirect(base_url('user/dashboard'));
        } else {
            redirect(base_url('?login_attempt='.md5(0)));
        }
	}
}

/* End of file Portal.php */
/* Location: ./application/modules/user_portal/controllers/Portal.php */