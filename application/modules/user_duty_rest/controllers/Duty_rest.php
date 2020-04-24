<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Duty_rest extends MY_Controller {

	public function __construct() {
		parent::__construct();
	
		page_redirect();

		$this->load->model('duty_rest_model', 'duty_rest');

		$this->page = "Duty Rest";
	}
	
	public function index() {
		if (user_login()) {
			$this->data["users"] = $this->setup->get_user_by_department_select(department_id());

			$this->ibox = true;
			$this->ibox_id = "ibox_duty_rest";
			$this->ibox_header = "Duty Rest <small>List</small>";
			$this->ibox_tools = [
				"<button type='button' name='btn_add' id='btn_add' onclick='clear_form(\"form_duty_rest\")' data-toggle='modal' href='#modal_duty_rest_form' class='btn btn-sm btn-primary'><span class='fa fa-plus'></span> Add Duty Rest</button>"
			];
			$this->datatable_script = true;
			$this->middle = "user_duty_rest/Duty_rest";
			$this->user_layout();
		} else {
			page_not_found();
		}
	}

	function get_duty_rest_list() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}
		
		$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
		$select = "tbl_duty_rest.*";
		$where = "1 = 1 AND tbl_duty_rest.delete_status=0";
		$join =  [
			"tbl_user" => "tbl_duty_rest.employee_id = tbl_user.employee_id"
		];
		$order_by = [];
		$limit = ["25" => 0];
		
		$aColumns = [
			"tbl_schedule.employee_id",
			"lastname",
			"date_duty",
			""
		];
		
		//sort
		if ($this->input->post('iSortCol_0') != null) {
			for($i = 0; $i < $this->input->post('iSortingCols') ; $i++) {
				if ($this->input->post('bSortable_'.$this->input->post('iSortCol_'.$i)) == true) {
					$order_by = [ $aColumns[$this->input->post('iSortCol_'.$i)] => $this->input->post('sSortDir_'.$i) ];
				}
			}
		}
		
		//limit
		if ($this->input->post('iDisplayLength') != null) {
			$limit = [$this->input->post('iDisplayLength') => 0];
		}
		
		//paginate
		if ($this->input->post('iDisplayStart') != 0) {
			$limit = [$this->input->post('iDisplayLength') => $this->input->post('iDisplayStart')];
		}
		
		//search
		if ($this->input->post('sSearch') != "") {
			$search = trim_str($this->input->post('sSearch'));
			$where .= " AND (";
			$where .= " tbl_schedule.employee_id LIKE '%".$search."%'";
			$where .= " OR CONCAT(lastname, ', ', firstname, ' ', LEFT(middlename, 1), '. ', name_ext) LIKE '%".$search."%'";
			$where .= " OR CONCAT(lastname, ', ', firstname, ' ', middlename, ' ', name_ext) LIKE '%".$search."%'";
			$where .= " OR DATE_FORMAT(date_duty, '%b %d, %Y') LIKE '%".$search."%'";
			$where .= " )";
		}
		
		//total records
		foreach ($this->duty_rest->select("COUNT(*) AS count", $where, $join) as $key => $value) {
			$data["iTotalDisplayRecords"] = $value->count;
			$data["iTotalRecords"] = $value->count;
		}
		
		foreach ($this->duty_rest->select($select, $where, $join, $order_by, $limit) as $key => $value) {
			$id = $value->dutyrest_id;
		
			$url_edit = "\"user_duty_rest/duty_rest/get_duty_rest_info\"";
			$url_delete = "\"user_duty_rest/duty_rest/delete_duty_rest\"";
			$form_id = "\"form_duty_rest\"";
			$tbl_id = "[tbl_duty_rest]";
			$modal = "modal_duty_rest_form";
		
			$data["data"][] = [
				$value->employee_id,
				$this->setup->user_fullname($this->setup->user_id($value->employee_id)),
				check_date($value->date_duty, "M d, Y"),
				"<button class='btn btn-success btn-circle btn_edit' name='btn_edit' data-toggle='modal' href='#$modal' onclick='get_duty_rest_info($id);' title='Edit'><span class='fa fa-edit'></span></button>
				<button class='btn btn-danger btn-circle' name='btn_delete' onclick='delete_this($url_delete, $id, $tbl_id)' title='Delete'><span class='fa fa-trash'></span></button>"
			];
		}
		
		echo json_encode($data);
	}

	function get_duty_rest_info() {
		$data = [];
					
		$select = "*";
		$where = ["dutyrest_id" => $this->input->post("value")];
		$join = [];
		$order_by = [];
		$limit = [];
		$group_by = "";
		
		foreach ($this->duty_rest->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
			$data = [
				"dutyrest_id"	=> $value->dutyrest_id,
				"user_id"		=> $this->setup->user_id($value->employee_id),
				"date_duty"		=> check_date($value->date_duty, "m/d/Y")
			];
		}
		
		echo json_encode($data);
	}

	function delete_duty_rest() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		if (user_login()) {
			$this->db->trans_begin();
			$ret = [
				"success" 	=> false,
				"msg"		=> "Something went wrong"
			];

			$where = ["dutyrest_id" => $this->input->post("value")];

			$data = [
				"delete_status"	=> 1
			];

			$this->duty_rest->update($data, $where);

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$ret = [
					'success' 	=> false,
					'msg'		=> '<span class="fa fa-warning"></span> Something went wrong'
				];
			} else {
			    $this->db->trans_commit();
			    $ret = [
					"success" 	=> true,
					"msg"		=> "Deleted"
				];
			}

			echo json_encode($ret);
		} else {
			page_not_found();
		}
	}

	function insert_duty_rest(){
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}
		
		if (user_login()) {
			$this->db->trans_begin();
			$ret = [
				"success" 	=> false,
				"msg"		=> "Something went wrong"
			];
			
			$dutyrest_id 	= $this->input->post("dutyrest_id");
			$user_id 		= $this->input->post("user_id");
			$employee_id 	= $this->setup->user_employee_id($user_id);
			$date_duty 		= check_date($this->input->post("date_duty"), "Y-m-d");

			$data = [
				"employee_id"	=> $employee_id,
				"date_duty"		=> $date_duty
			];

			if ($dutyrest_id == null) {
				$data += [
					"created_by" => user_id()
				];

				if ($this->check_duty_rest($employee_id, $date_duty) == 0) {
					$this->duty_rest->insert($data);

					$ret = [
						"success" 		=> true,
						"msg"			=> "Inserted"
					];
				} else {
					$ret = [
						"success" 	=> false,
						"msg"		=> "Already Exist"
					];
				}
			} else {
				if ($this->get_duty_rest($dutyrest_id) == $employee_id.$date_duty) {
					$this->duty_rest->update($data, ["dutyrest_id" => $dutyrest_id]);

					$ret = [
						"success" 		=> true,
						"msg"			=> "Updated"
					];
				} else {
					if ($this->check_duty_rest($employee_id, $date_duty) == 0) {
						$this->duty_rest->update($data, ["dutyrest_id" => $dutyrest_id]);

						$ret = [
							"success" 		=> true,
							"msg"			=> "Updated"
						];
					} else {
						$ret = [
							"success" 	=> false,
							"msg"		=> "Already Exist"
						];
					}
				}
			}
			
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
			} else {
			    $this->db->trans_commit();
			}
			
			echo json_encode($ret);
		} else {
			page_not_found();
		}
	}

	function check_duty_rest($employee_id, $date_duty) {
		if (user_login()) {
			$data = 0;
						
			$select = "COUNT(*) count";
			$where = [
				"employee_id"	=> $employee_id,
				"date_duty"		=> $date_duty,
				"delete_status"	=> 0
			];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->duty_rest->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->count;
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

	function get_duty_rest($dutyrest_id) {
		if (user_login()) {
			$data = "";
						
			$select = "*";
			$where = ["dutyrest_id" => $dutyrest_id];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->duty_rest->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->employee_id.check_date($value->date_duty, "Y-m-d");
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

}

/* End of file Duty_rest.php */
/* Location: ./application/modules/user_duty_rest/Duty_rest.php */