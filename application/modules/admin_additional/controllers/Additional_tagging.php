<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Additional_tagging extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	
		page_redirect();		
		$this->load->model('additional_model','additional');
		$this->load->model('addition_tagging_model','addition_tag');		        		
		$this->load->library('form_validation');
		$this->page = "Additional Tagging";
	}
	
	public function index() {
		if (admin_login()) {
			$this->data = [				
				"title" => $this->setup->get_additional_list(),		       
			];
			$this->datatable_script = true;
			$this->ibox = true;
			$this->ibox_header = "Employee Additionals Tagging";			
			$this->middle = "admin_additional/Additional_tagging";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	public function get_emp_additional_list($id) {
		if (admin_login()) {
			$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
			$select = "tbl_additional.additional_title, tbl_additional.amount, tbl_additional_employee.`status`, tbl_additional_employee.created_at,tbl_additional_employee.id";
			$where = "1 = 1 AND tbl_additional.delete_status = 0 AND tbl_additional_employee.employee_id = '".$id."'";
			$join =  [
				"tbl_additional" => "tbl_additional.additional_id = tbl_additional_employee.additional_id"
			];
			$order_by = [];
			$limit = ["25" => 0];
			
			$aColumns = [
				"additional_title",
				"amount",
				"status",
				"created_at",				
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
			// if ($this->input->post('sSearch') != "") {
			// 	$search = trim_str($this->input->post('sSearch'));
			// 	$where .= " AND (";
			// 	$where .= " additional_title LIKE '%".$search."%'";
			// 	$where .= " OR amount LIKE '%".$search."%'";
			// 	$where .= " OR ( DATE_FORMAT(tbl_additional.created_at, '%b %d, %Y') ) LIKE '%".$search."%'";
			// 	$where .= " OR name LIKE '%".$search."%'";
			// 	$where .= " )";
			// }
			
			//total records
			foreach ($this->addition_tag->select("COUNT(*) AS count", $where, $join) as $key => $value) {
				$data["iTotalDisplayRecords"] = $value->count;
				$data["iTotalRecords"] = $value->count;
			}
			
			foreach ($this->addition_tag->select($select, $where, $join, $order_by, $limit) as $key => $value) {
				$id = $value->id;
				if($value->status == 0) {
					$value->status = '<p style="color:green;">ACTIVE</p>';
				}else if($value->status == 1) {
					$value->status = '<p style="color:red;">NOT ACTIVE</p>';
				}

				
				$data["data"][] = [
					$value->additional_title,					
					number_format($value->amount, 2, '.',','),					
					$value->status,
					check_date($value->created_at, "M d, Y"),					
					'<button onclick="edit_deduc('."'".$value->id."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="Edit" id="btnR2"><i class="fa fa-pencil"></i> &nbsp;</i></button>'
				];
			}			
			echo json_encode($data);	
		} else {
			page_not_found();
		}
	}

	public function addtag_additional_emp() {
		if (admin_login()) {
			if ($this->form_validation->run('additional_tagging') == FALSE) {         
				$result['status'] = false;
	            $result['message'] = $this->form_validation->error_array();
			}else {				
				if($this->input->post('emp_id') != NULL) {
					$result['status'] = true;
		            $data = array (
		                'additional_id' => $this->input->post('additional_title'),                    
		                'status' => $this->input->post('status_emp_additional'),	                
		                'employee_id' => $this->input->post('emp_id'),	
		                'created_by' => admin_id(),
		                'modified_by' => admin_id(),
		            );           
		            $insert = $this->addition_tag->insert_for_additional($data);	
				}else {					
					$data_error = array (
							'input_emp_id'	=>	'Employee ID is required',					
					);							
					$result['status'] = false;
					$result['message'] = $data_error;
				}
				
			}
			echo json_encode($result);
		} else {
			page_not_found();
		}
	}		


}

/* End of file Dashboard.php */
/* Location: ./application/modules/admin_dashboard/controllers/Dashboard.php */