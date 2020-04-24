<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Overtime extends MY_Controller {

	public function __construct() {
		parent::__construct();
	
		page_redirect();

		$this->load->model('overtime_model', 'overtime');

		$this->page = "Overtime";
	}
	
	public function index() {
		if (user_login()) {
			$this->data["users"] = $this->setup->get_user_by_department_select(department_id());

			$this->ibox = true;
			$this->ibox_id = "ibox_overtime";
			$this->ibox_header = "Overtime <small>List</small>";
			$this->ibox_tools = [
				"<button type='button' name='btn_add' id='btn_add' onclick='clear_form(\"form_overtime\")' data-toggle='modal' href='#modal_overtime_form' class='btn btn-sm btn-primary'><span class='fa fa-plus'></span> Add Overtime</button>"
			];
			$this->datatable_script = true;
			$this->middle = "user_overtime/Overtime";
			$this->user_layout();
		} else {
			page_not_found();
		}
	}

	function get_overtime_list() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}
		
		$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
		$select = "tbl_overtime.*";
		$where = "1 = 1 AND tbl_overtime.delete_status=0";
		$join =  [
			"tbl_user" => "tbl_overtime.employee_id = tbl_user.employee_id"
		];
		$order_by = [];
		$limit = ["25" => 0];
		
		$aColumns = [
			"tbl_overtime.employee_id",
			"lastname",
			"overtime_in, overtime_out",
			"overtime_duration_min",
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
			$where .= " tbl_overtime.employee_id LIKE '%".$search."%'";
			$where .= " OR CONCAT(lastname, ', ', firstname, ' ', LEFT(middlename, 1), '. ', name_ext) LIKE '%".$search."%'";
			$where .= " OR CONCAT(lastname, ', ', firstname, ' ', middlename, ' ', name_ext) LIKE '%".$search."%'";
			$where .= " OR CONCAT(DATE_FORMAT(overtime_in, '%b %d, %Y %I:%i:%s %p'),' - ',DATE_FORMAT(overtime_out, '%b %d, %Y %I:%i:%s %p')) LIKE '%".$search."%'";
			$where .= " OR overtime_duration_min LIKE '%".$search."%'";
			$where .= " )";
		}
		
		//total records
		foreach ($this->overtime->select("COUNT(*) AS count", $where, $join) as $key => $value) {
			$data["iTotalDisplayRecords"] = $value->count;
			$data["iTotalRecords"] = $value->count;
		}
		
		foreach ($this->overtime->select($select, $where, $join, $order_by, $limit) as $key => $value) {
			$id = $value->overtime_id;
		
			$url_edit = "\"user_overtime/overtime/get_overtime_info\"";
			$url_delete = "\"user_overtime/overtime/delete_overtime\"";
			$form_id = "\"form_overtime\"";
			$tbl_id = "[tbl_overtime]";
			$modal = "modal_overtime_form";
		
			$data["data"][] = [
				$value->employee_id,
				$this->setup->user_fullname($this->setup->user_id($value->employee_id)),
				check_datetime($value->overtime_in, "M d, Y h:i:s A").' - '.check_datetime($value->overtime_out, "M d, Y h:i:s A"),
				$value->overtime_duration_min,
				"<button class='btn btn-success btn-circle btn_edit' name='btn_edit' data-toggle='modal' href='#$modal' onclick='get_overtime_info($id);' title='Edit'><span class='fa fa-edit'></span></button>
				<button class='btn btn-danger btn-circle' name='btn_delete' onclick='delete_this($url_delete, $id, $tbl_id)' title='Delete'><span class='fa fa-trash'></span></button>"
			];
		}
		
		echo json_encode($data);
	}

	function get_overtime_info() {
		$data = [];
					
		$select = "*";
		$where = ["overtime_id" => $this->input->post("value")];
		$join = [];
		$order_by = [];
		$limit = [];
		$group_by = "";
		
		foreach ($this->overtime->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
			$data = [
				"overtime_id"			=> $value->overtime_id,
				"user_id"				=> $this->setup->user_id($value->employee_id),
				"overtime_in_date"		=> check_date($value->overtime_in, "m/d/Y"),
				"overtime_in_time"		=> check_date($value->overtime_in, "h:i:s A"),
				"overtime_out_date"		=> check_date($value->overtime_out, "m/d/Y"),
				"overtime_out_time"		=> check_date($value->overtime_out, "h:i:s A")
			];
		}
		
		echo json_encode($data);
	}

	function delete_overtime() {
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

			$where = ["overtime_id" => $this->input->post("value")];

			$data = [
				"delete_status"	=> 1
			];

			$this->overtime->update($data, $where);

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

	function insert_overtime(){
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
			
			$overtime_id 			= $this->input->post("overtime_id");
			$user_id 				= $this->input->post("user_id");
			$employee_id 			= $this->setup->user_employee_id($user_id);
			$overtime_in_date 		= $this->input->post("overtime_in_date");
			$overtime_in_time 		= $this->input->post("overtime_in_time");
			$overtime_out_date 		= $this->input->post("overtime_out_date");
			$overtime_out_time 		= $this->input->post("overtime_out_time");

			$overtime_in = $overtime_in_date.' '.$overtime_in_time;
			$overtime_out = $overtime_out_date.' '.$overtime_out_time;

			$data = [
				"employee_id"			=> $employee_id,
				"overtime_in"			=> check_datetime($overtime_in, 'Y-m-d H:i:s A'),
				"overtime_out"			=> check_datetime($overtime_out, 'Y-m-d H:i:s A')
			];

			if ($overtime_id == null) {
				$data += [
					"created_by" => user_id()
				];

				if ($this->check_overtime($employee_id, check_datetime($overtime_in, 'Y-m-d H:i:s'), check_datetime($overtime_out, 'Y-m-d H:i:s')) == 0) {
					$this->overtime->insert($data);
					$overtime_id = $this->db->insert_id();

					$this->update_overtime_duration_min($overtime_id);

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
				if ($this->get_overtime($overtime_id) == $employee_id.check_datetime($overtime_in, 'Y-m-d H:i:s').check_datetime($overtime_out, 'Y-m-d H:i:s')) {
					$this->overtime->update($data, ["overtime_id" => $overtime_id]);

					$this->update_overtime_duration_min($overtime_id);

					$ret = [
						"success" 		=> true,
						"msg"			=> "Updated"
					];
				} else {
					if ($this->check_overtime($employee_id, check_datetime($overtime_in, 'Y-m-d H:i:s'), check_datetime($overtime_out, 'Y-m-d H:i:s')) == 0) {
						$this->overtime->update($data, ["overtime_id" => $overtime_id]);

						$this->update_overtime_duration_min($overtime_id);

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

	function check_overtime($employee_id, $overtime_in, $overtime_out) {
		if (user_login()) {
			$data = 0;
						
			$select = "COUNT(*) count";
			$where = [
				"employee_id"	=> $employee_id,
				"overtime_in"	=> $overtime_in,
				"overtime_out"	=> $overtime_out,
				"delete_status"	=> 0
			];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->overtime->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->count;
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

	function get_overtime($overtime_id) {
		if (user_login()) {
			$data = "";
						
			$select = "*";
			$where = ["overtime_id" => $overtime_id];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->overtime->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->employee_id.check_datetime($value->overtime_in, "Y-m-d H:i:s").check_datetime($value->overtime_out, "Y-m-d H:i:s");
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

	function update_overtime_duration_min($overtime_id) {
		$totalHrs = (int)$this->overtime->select('(
									SEC_TO_TIME(
										TIME_TO_SEC(
											DATE_FORMAT(
												overtime_out,
												\'%Y-%m-%d %H:%i:%s\'
											)
										) - TIME_TO_SEC(
											DATE_FORMAT(
												overtime_in,
												\'%Y-%m-%d %H:%i:%s\'
											)
										)
									)
								) totalHrs', ["overtime_id" => $overtime_id])[0]->totalHrs*60;

		$this->overtime->update(["overtime_duration_min" => str_replace('-', '', $totalHrs)], ["overtime_id" => $overtime_id]);
	}

}

/* End of file Overtime.php */
/* Location: ./application/modules/user_overtime/Overtime.php */