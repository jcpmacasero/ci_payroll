<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_Create extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	
		page_redirect();		
		$this->load->model('leave_model','leave');		        		
		$this->load->library('form_validation');
		$this->page = "Create Leave";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;
			$this->ibox = true;
			$this->ibox_header = "List of Employee leaves";
			$this->ibox_id = "ibox_leave";
			$this->middle = "admin_leave/Leave_Create";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	public function add_leave() {
		if (admin_login()) {
			if ($this->form_validation->run('add_leave') == FALSE) {         
				$result['status'] = false;
	            $result['message'] = $this->form_validation->error_array();
			}else {
				$result['status'] = true;
	            $data = array (
	                'leave_title' => $this->input->post('lea_title'),                    
	                'leave_status' => $this->input->post('lea_status'),
	                'status' => $this->input->post('main_status'),
	                'duration' => $this->input->post('lea_duration'),
	                'created_by' => admin_id(),
	                'modified_by' => admin_id(),
	            );           
	           $insert = $this->leave->insert_leave($data);
			}
			echo json_encode($result);
		} else {
			page_not_found();
		}
	}

	public function get_leave_list() {
		if (admin_login()) {
			$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
			$select = "tbl_admin.`name`, tbl_leave.leave_id, tbl_leave.leave_title, tbl_leave.leave_status, tbl_leave.`status`, tbl_leave.duration, tbl_leave.created_at";
			$where = "1 = 1 AND tbl_leave.delete_status = 0";
			$join =  [
				"tbl_admin" => "tbl_admin.admin_id = tbl_leave.created_by"
			];
			$order_by = [];
			$limit = ["25" => 0];
			
			$aColumns = [
				"leave_title",
				"leave_status",
				"status",
				"duration",
				"created_at",
				"created_by",
				"",
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
				$where .= " leave_title LIKE '%".$search."%'";
				$where .= " OR leave_status LIKE '%".$search."%'";
				$where .= " OR status LIKE '%".$search."%'";
				$where .= " OR duration LIKE '%".$search."%'";
				$where .= " OR ( DATE_FORMAT(tbl_leave.created_at, '%b %d, %Y') ) LIKE '%".$search."%'";
				$where .= " OR name LIKE '%".$search."%'";
				$where .= " )";
			}
			
			//total records
			foreach ($this->leave->select("COUNT(*) AS count", $where, $join) as $key => $value) {
				$data["iTotalDisplayRecords"] = $value->count;
				$data["iTotalRecords"] = $value->count;
			}
			
			foreach ($this->leave->select($select, $where, $join, $order_by, $limit) as $key => $value) {
				$id = $value->leave_id;
				if($value->status == 0) {
					$value->status = '<p style="color:red;">DISABLED</p>';
				}else if($value->status == 1) {
					$value->status = '<p style="color:green;">ENABLED</p>';
				}

				if($value->leave_status == 0) {
					$value->leave_status = '<p style="color:green;">With Pay</p>';
				}else if($value->leave_status == 1) {
					$value->leave_status = '<p style="color:red;">Without Pay</p>';
				}
			
				$data["data"][] = [
					$value->leave_title,
					$value->leave_status,									
					$value->status,
					$value->duration ." Day(s)",
					check_date($value->created_at, "M d, Y"),
					$value->name,
					'<button onclick="edit_deduc('."'".$value->leave_id."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="Edit" id="btnR2"><i class="fa fa-pencil"></i> &nbsp;</i></button>'
				];
			}
			
			echo json_encode($data);	
		} else {
			page_not_found();
		}
	}	
	
}

/* End of file Department.php */
/* Location: ./application/modules/admin_dashboard/controllers/Dashboard.php */