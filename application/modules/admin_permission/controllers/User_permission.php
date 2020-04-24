<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_permission extends MY_Controller {

	public function __construct() {
		parent::__construct();
	
		page_redirect();

		$this->load->model('user_module_model', 'user_module');
		$this->load->model('user_module_button_model', 'user_module_button');
		$this->load->model('user_permission_model', 'user_permission');
		$this->load->model('user_model', 'user');
		
		$this->page = "User Permission";
	}
	
	public function index() {
		if (admin_login()) {
			$this->ibox_id = "ibox_permission";
			$this->ibox_header ="User Permission";
			$this->data = [
				"users" => $this->setup->get_user_select()
			];
			$this->datatable_script = true;
			$this->ibox = true;
			$this->middle = "admin_permission/User_permission";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	//Permissions
	public function get_permission_mods() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		$data = ["data" => []];
		$user_id = $this->input->post("user_id");

		if($user_id != null) {
			foreach ($this->user_module->select() as $key => $value) {
				$id = $value->user_module_id;
				$url = "\"admin_permission/user_permission/delete_module\"";
				$tbl_id = "[tbl_module]";

				$data["data"][$key] = [
					$value->module_name,
					$this->get_permission_mod_btns($id, $user_id)
				];
			}
		}

		echo json_encode($data);
	}

	public function get_permission_mod_btns($user_module_id, $user_id) {
		$is_check_all = $this->is_check_all($user_module_id, $user_id);

		$btn = "<table>
					<tr>
						<td>
							<input class='check' type='checkbox' id='check_all".$user_module_id."' ".( $is_check_all == true ? "checked" : "" )." onchange='change_status_all(this, $user_id, $user_module_id)'>
							<label for='check_all".$user_module_id."'><span></span></label>
						</td>
						<td><b>Select/Deselect All</b></td>
					</tr>";

		foreach ($this->user_module_button->select("*", ["user_module_id" => $user_module_id]) as $key => $value) {
			$status = $this->user_permission($user_id, $value->user_mod_button_id);
			$btn .= "<tr>
						<td>
							<input class='check' type='checkbox' id='check".$value->user_mod_button_id."' ".( $status == 1 ? "checked" : "" )." onchange='change_status(this, $user_id, $value->user_mod_button_id)'>
							<label for='check".$value->user_mod_button_id."'><span></span></label>
						</td>
						<td>".$value->button_name."</td>
					</tr>";
		}

		$btn .= "</table>";

		return $btn;
	}

	public function user_permission($user_id, $user_mod_button_id) {
		$status = 0;

		foreach ($this->user_permission->select("status", ["user_id" => $user_id, "user_mod_button_id" => $user_mod_button_id]) as $key => $value) {
			$status = $value->status;
		}

		return $status;
	}

	public function change_status() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		$this->db->trans_begin();

		$user_permission_id = "";

		foreach ($this->user_permission->select("user_permission_id", ["user_id" => $this->input->post("user_id"), "user_mod_button_id" => $this->input->post("user_mod_button_id")]) as $key => $value) {
			$user_permission_id = $value->user_permission_id;
		}

		$data = ["status" => $this->input->post("status")];

		if ($user_permission_id == null) {
			$data += [
				"user_id"		=> $this->input->post("user_id"),
				"user_mod_button_id" => $this->input->post("user_mod_button_id")
			];
			$this->user_permission->insert($data);
		} else {
			$this->user_permission->update($data, ["user_permission_id" => $user_permission_id]);
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}

	public function change_status_all() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		$this->db->trans_begin();
		
		foreach ($this->user_module_button->select("user_mod_button_id", ["user_module_id" => $this->input->post("user_module_id")]) as $btn_key => $btn_value) {
			$user_permission_id = "";

			foreach ($this->user_permission->select("user_permission_id", ["user_id" => $this->input->post("user_id"), "user_mod_button_id" => $btn_value->user_mod_button_id]) as $key => $value) {
				$user_permission_id = $value->user_permission_id;
			}

			$data = ["status" => $this->input->post("status")];

			if ($user_permission_id == null) {
				$data += [
					"user_id"		=> $this->input->post("user_id"),
					"user_mod_button_id" => $btn_value->user_mod_button_id
				];
				$this->user_permission->insert($data);
			} else {
				$this->user_permission->update($data, ["user_permission_id" => $user_permission_id]);
			}
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}

	public function is_check_all($user_module_id, $user_id) {
		$ret = false;
		$count_btn = 0;
		$count_allowed = 0;

		foreach ($this->user_module_button->select("user_mod_button_id", ["user_module_id" => $user_module_id]) as $btn_key => $btn_value) {
			$count_btn++;

			foreach ($this->user_permission->select("COUNT(*) As count", ["user_id" => $user_id, "user_mod_button_id" => $btn_value->user_mod_button_id, "status" => 1]) as $key => $value) {
				$count_allowed += $value->count;
			}
		}

		if ($count_btn == $count_allowed) {
			$ret = true;
		}

		return $ret;
	}

	public function get_user_select() {
		$data = [];

		foreach ($this->user->select("*", [], [], ["email" => "asc"]) as $key => $value) {
			$data[] = [
				"id" 	=> $value->user_id,
				"value" => $value->email
			];
		}

		return json_encode($data);
	}
	//End

	//Modules
	public function get_modules() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		$data = ["data" => []];

		foreach ($this->user_module->select() as $key => $value) {
			$id = $value->user_module_id;
			$url = "\"admin_permission/User_permission/delete_module\"";
			$tbl_id = "[tbl_module, tbl_permission_mod]";

			$data["data"][] = [
				'<input type="text" value="'.$value->module_name.'" class="form-control input-sm module_name" style="width: 100%;">',
				"<div class='text-center btn-group'>
					<button class='btn btn-xs' onclick='edit_module(this, $id)' title='Save changes'><span class='fa fa-check'></span></button>
					<button class='btn btn-xs' onclick='delete_this($url, $id, $tbl_id)' title='Delete'><span class='fa fa-trash'></span></button>
				</div>",
				$this->get_module_buttons($id)
			];
		}

		echo json_encode($data);
	}

	public function get_module_buttons($user_module_id) {
		$tbl = "<div class='form_mod_btn' style='margin-left: 3px;'>
					<input type='text' placeholder='Button Name' name='button_name' class='form-control input-sm button_name'>
					<input type='text' placeholder='Button Code' name='button_code' class='form-control input-sm button_code' onkeypress='add_button_enter(this, $user_module_id)'>
					<button class='btn btn-xs' id='mod_btn_btnAdd' style='margin-left: 3px;' onclick='add_button(this, $user_module_id)' title='Add'><span class='fa fa-plus'></span></button>
				</div>
				<table>";

		foreach ($this->user_module_button->select("*", ["user_module_id" => $user_module_id]) as $key => $value) {
			$id = $value->user_mod_button_id;
			$url = "\"admin_permission/User_permission/delete_mod_button\"";
			$tbl_id = "[tbl_module, tbl_permission_mod]";

			$tbl .= "<tr>
						<td style='padding: 3px;'>
							<input type='text' value=\"$value->button_name\" class='form-control input-sm button_name' style='width: 49%;'>
							<input type='text' value='$value->button_code' class='form-control input-sm button_code' style='width: 50%;'>
						</td>
						<td style='padding: 3px;'>
							<div class='text-center btn-group'>
								<button class='btn btn-xs' onclick='edit_mod_button(this, $id)' title='Save changes'><span class='fa fa-check'></span></button>
								<button class='btn btn-xs' onclick='delete_this($url, $id, $tbl_id)' title='Delete'><span class='fa fa-trash'></span></button>
							</div>
						</td>
					</tr>";
		}

		$tbl .= "</table>";

		return $tbl;
	}

	public function insert_module() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		$this->db->trans_begin();
		$ret = [
			"success" 	=> false,
			"msg"		=> "<span class='fa fa-warning'></span> Something went wrong"
		];

		$user_module_id = $this->input->post("user_module_id");
		$data = ["module_name" => $this->input->post("module_name")];

		if ($user_module_id == null) {
			$this->user_module->insert($data);

			$user_module_id = $this->db->insert_id();

			$mod_btn_data = [
				"button_name" 		=> "Add",
				"button_code"		=> "btn_add",
				"user_module_id"	=> $user_module_id
			];
			$this->user_module_button->insert($mod_btn_data);

			$mod_btn_data = [
				"button_name" 		=> "Edit",
				"button_code"		=> "btn_edit",
				"user_module_id"	=> $user_module_id
			];
			$this->user_module_button->insert($mod_btn_data);

			$mod_btn_data = [
				"button_name" 		=> "Delete",
				"button_code"		=> "btn_delete",
				"user_module_id"	=> $user_module_id
			];
			$this->user_module_button->insert($mod_btn_data);

			$mod_btn_data = [
				"button_name" 		=> "View",
				"button_code"		=> "view_page",
				"user_module_id"	=> $user_module_id
			];
			$this->user_module_button->insert($mod_btn_data);
		} else {
			$this->user_module->update($data, ["user_module_id" => $user_module_id]);
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$ret = [
				"success" 	=> false,
				"msg"		=> "<span class='fa fa-warning'></span> Something went wrong"
			];
		} else {
		    $this->db->trans_commit();
		    $ret = [
				"success" 	=> true,
				"msg"		=> "<span class='fa fa-check'></span> Success"
			];
		}

		echo json_encode($ret);
	}

	public function delete_module() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		$this->db->trans_begin();

		$ret = [
			"success" 	=> false,
			"msg"		=> "<span class='fa fa-warning'></span> Something went wrong"
		];

		$this->user_module_button->delete(["user_module_id" => $this->input->post("value")]);
		$this->user_module->delete(["user_module_id" => $this->input->post("value")]);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$ret = [
				"success" 	=> false,
				"msg"		=> "<span class='fa fa-warning'></span> Something went wrong"
			];
		} else {
		    $this->db->trans_commit();
		    $ret = [
				"success" 	=> true,
				"msg"		=> "<span class='fa fa-check'></span> Success"
			];
		}

		echo json_encode($ret);
	}

	public function insert_module_btn() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		$this->db->trans_begin();
		$ret = [
			"success" 	=> false,
			"msg"		=> "<span class='fa fa-warning'></span> Something went wrong"
		];

		$user_mod_button_id = $this->input->post("user_mod_button_id");
		$data = [
				"button_name" => addslashes($this->input->post("button_name")),
				"button_code" => $this->input->post("button_code")
			];

		if ($user_mod_button_id == null) {
			$data += [
				"user_module_id" => $this->input->post("user_module_id")
			];
			$this->module_button->insert($data);
		} else {
			$this->module_button->update($data, ["user_mod_button_id" => $user_mod_button_id]);
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$ret = [
				"success" 	=> false,
				"msg"		=> "<span class='fa fa-warning'></span> Something went wrong"
			];
		} else {
		    $this->db->trans_commit();
		    $ret = [
				"success" 	=> true,
				"msg"		=> "<span class='fa fa-check'></span> Success"
			];
		}

		echo json_encode($ret);
	}

	public function delete_mod_button() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}
        
		$this->db->trans_begin();

		$ret = [
			"success" 	=> false,
			"msg"		=> "<span class='fa fa-warning'></span> Something went wrong"
		];


		$this->user_model_permission->delete(["user_mod_button_id" => $this->input->post("value")]);
		$this->user_module_button->delete(["user_mod_button_id" => $this->input->post("value")]);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$ret = [
				"success" 	=> false,
				"msg"		=> "<span class='fa fa-warning'></span> Something went wrong"
			];
		} else {
		    $this->db->trans_commit();
		    $ret = [
				"success" 	=> true,
				"msg"		=> "<span class='fa fa-check'></span> Success"
			];
		}
		
		echo json_encode($ret);
	}
	//End

}

/* End of file User_permission.php */
/* Location: ./application/modules/admin_permission/controllers/User_permission.php */