<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends MY_Controller {

	public function __construct() {
		parent::__construct();
	
		page_redirect();

		$this->load->model('schedule_model', 'schedule');
		$this->load->model('restday_model', 'restday');

		$this->page = "Schedules";
	}
	
	public function index() {
		if (user_login()) {
			$this->data["users"] = $this->setup->get_user_by_department_select(department_id());

			$this->ibox = true;
			$this->ibox_id = "ibox_schedule";
			$this->ibox_header = "Schedule <small>List</small>";
			$this->ibox_tools = [
				"<button type='button' name='btn_add' id='btn_add' data-toggle='modal' href='#modal_schedule_form' class='btn btn-sm btn-primary'><span class='fa fa-plus'></span> Add Schedule</button>"
			];
			$this->datatable_script = true;
			$this->middle = "user_schedule/Schedule";
			$this->user_layout();
		} else {
			page_not_found();
		}
	}

	function get_schedule_list() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}
		
		$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
		$select = "tbl_schedule.*";
		$where = "1 = 1 AND tbl_schedule.delete_status=0";
		$join =  [
			"tbl_user" => "tbl_schedule.user_id = tbl_user.user_id"
		];
		$order_by = [];
		$limit = ["25" => 0];
		
		$aColumns = [
			"tbl_schedule.employee_id",
			"lastname",
			"time_in, time_out",
			"date_from, date_to",
			"",
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
			$where .= " OR CONCAT(time_in, ' - ', time_out) LIKE '%".$search."%'";
			$where .= " OR CONCAT(DATE_FORMAT(date_from, '%b %d, %Y'),' - ',DATE_FORMAT(date_to, '%b %d, %Y')) LIKE '%".$search."%'";
			$where .= " )";
		}
		
		//total records
		foreach ($this->schedule->select("COUNT(*) AS count", $where, $join) as $key => $value) {
			$data["iTotalDisplayRecords"] = $value->count;
			$data["iTotalRecords"] = $value->count;
		}
		
		foreach ($this->schedule->select($select, $where, $join, $order_by, $limit) as $key => $value) {
			$id = $value->schedule_id;
		
			$url_edit = "\"user_schedule/schedule/get_schedule_info\"";
			$url_delete = "\"user_schedule/schedule/delete_schedule\"";
			$form_id = "\"form_schedule\"";
			$tbl_id = "[tbl_schedule]";
			$modal = "modal_schedule_form";
		
			$data["data"][] = [
				$this->setup->user_employee_id($value->user_id),
				$this->setup->user_fullname($value->user_id),
				check_time($value->time_in, "h:i:s A")." - ".check_time($value->time_out, "h:i:s A"),
				check_date($value->date_from, "M d, Y")." - ".check_date($value->date_to, "M d, Y"),
				$this->get_resday($id),
				"<button class='btn btn-success btn-circle btn_edit' name='btn_edit' data-toggle='modal' href='#$modal' onclick='get_schedule_info($id);' title='Edit'><span class='fa fa-edit'></span></button>
				<button class='btn btn-danger btn-circle' name='btn_delete' onclick='delete_this($url_delete, $id, $tbl_id)' title='Delete'><span class='fa fa-trash'></span></button>"
			];
		}
		
		echo json_encode($data);
	}

	function get_resday($schedule_id) {		
		$data = "";
		
		$select = "*";
		$where = [
			"schedule_id" 	=> $schedule_id
		];
		$join = [];
		$order_by = [];
		$limit = [];
		$group_by = "";
		
		foreach ($this->restday->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
			$data .= date("M, d, Y", strtotime($value->rest_day))."<br>";
		}
		
		return $data;
	}

	function get_schedule_info() {
		$data = [];
		$data_info = [];
		$rest_day = [];
					
		$select = "*";
		$where = ["schedule_id" => $this->input->post("value")];
		$join = [];
		$order_by = [];
		$limit = [];
		$group_by = "";
		
		foreach ($this->schedule->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
			$data_info = [
				"schedule_id"	=> $value->schedule_id,
				"user_id"		=> $value->user_id,
				"time_in"		=> check_date($value->time_in, "h:i:s A"),
				"time_out"		=> check_date($value->time_out, "h:i:s A"),
				"date_from"		=> check_date($value->date_from, "m/d/Y"),
				"date_to"		=> check_date($value->date_to, "m/d/Y")
			];
		}

		foreach ($this->restday->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
			$rest_day[] = [
				"restday_id"	=> $value->restday_id,
				"rest_day"		=> date("m/d/Y", strtotime($value->rest_day))
			];
		}

		$data = [
			"data_info"	=> $data_info,
			"rest_day"	=> $rest_day
		];
		
		echo json_encode($data);
	}

	function delete_schedule() {
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

			$where = ["schedule_id" => $this->input->post("value")];

			$data = [
				"modified_by"	=> user_id(),
				"delete_status"	=> 1
			];

			$this->schedule->update($data, $where);

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

	function delete_rest_day() {
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

			$where = ["restday_id" => $this->input->post("value")];

			$this->restday->delete($where);

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

	function insert_schedule(){
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
			
			$schedule_id 	= $this->input->post("schedule_id");
			$user_id 		= $this->input->post("user_id");
			$time_in 		= check_time($this->input->post("time_in"), "h:i:s");
			$time_out 		= check_time($this->input->post("time_out"), "h:i:s");
			$date_from 		= check_date($this->input->post("date_from"), "Y-m-d");
			$date_to 		= check_date($this->input->post("date_to"), "Y-m-d");

			$data = [
				"user_id"		=> $user_id,
				"employee_id"	=> $this->setup->user_employee_id($user_id),
				"time_in"		=> $time_in,
				"time_out"		=> $time_out,
				"date_from"		=> $date_from,
				"date_to"		=> $date_to
			];

			if ($schedule_id == null) {
				$data += [
					"created_by" => user_id()
				];

				if ($this->check_schedule($user_id, $time_in, $time_out, $date_from, $date_to) == 0) {
					$this->schedule->insert($data);
					$schedule_id = $this->db->insert_id();

					if ($this->input->post("rest_day") != null) {
						foreach ($this->input->post("rest_day") as $key => $value) {
							$data_restday = [
								"schedule_id"	=> $schedule_id,
								"employee_id"	=> $this->setup->user_employee_id($user_id),
								"rest_day"		=> date("Y-m-d", strtotime($value))
							];

							if ($value != null) {
								$this->restday->insert($data_restday);
							}
						}
					}

					$ret = [
						"success" 		=> true,
						"msg"			=> "Inserted"
					];
				} else {
					$ret = [
						"success" 	=> false,
						"msg"		=> "Conflict schedule"
					];
				}
			} else {
				$data += [
					"modified_by" => user_id()
				];

				if ($this->get_schedule($schedule_id) == $user_id.$time_in.$time_out.$date_from.$date_to) {
					$this->schedule->update($data, ["schedule_id" => $schedule_id]);

					if ($this->input->post("rest_day") != null) {
						foreach ($this->input->post("rest_day") as $key => $value) {
							$data_restday = [
								"schedule_id"	=> $schedule_id,
								"employee_id"	=> $this->setup->user_employee_id($user_id),
								"rest_day"		=> date("Y-m-d", strtotime($value))
							];

							if ($this->input->post("restday_id")[$key] == null) {
								$this->restday->insert($data_restday);
							} else {
								$this->restday->update($data_restday, ["restday_id" => $this->input->post("restday_id")[$key]]);
							}
						}
					}

					$ret = [
						"success" 		=> true,
						"msg"			=> "Updated"
					];
				} else {
					if ($this->check_schedule($user_id, $time_in, $time_out, $date_from, $date_to) == 0) {
						$this->schedule->update($data, ["schedule_id" => $schedule_id]);

						if ($this->input->post("rest_day") != null) {
							foreach ($this->input->post("rest_day") as $key => $value) {
								$data_restday = [
									"schedule_id"	=> $schedule_id,
									"employee_id"	=> $this->setup->user_employee_id($user_id),
									"rest_day"		=> date("Y-m-d", strtotime($value))
								];

								if ($this->input->post("restday_id")[$key] == null) {
									$this->restday->insert($data_restday);
								} else {
									$this->restday->update($data_restday, ["restday_id" => $this->input->post("restday_id")[$key]]);
								}
							}
						}

						$ret = [
							"success" 		=> true,
							"msg"			=> "Updated"
						];
					} else {
						$ret = [
							"success" 	=> false,
							"msg"		=> "Conflict schedule"
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

	function check_schedule($user_id, $time_in, $time_out, $date_from, $date_to) {
		if (user_login()) {
			$data = 0;
						
			$select = "COUNT(*) count";
			$where = [
				"user_id"		=> $user_id,
				"time_in"		=> $time_in,
				"time_out"		=> $time_out,
				"date_from"		=> $date_from,
				"date_to"		=> $date_to,
				"delete_status"	=> 0
			];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->schedule->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->count;
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

	function get_schedule($schedule_id) {
		if (user_login()) {
			$data = "";
						
			$select = "*";
			$where = ["schedule_id" => $schedule_id];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->schedule->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->user_id.$value->time_in.$value->time_out.check_date($value->date_from, "Y-m-d").check_date($value->date_to, "Y-m-d");
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

}

/* End of file Schedule.php */
/* Location: ./application/modules/user_schedule/controllers/Schedule.php */