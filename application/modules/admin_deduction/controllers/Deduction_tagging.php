<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deduction_tagging extends MY_Controller {
	private $total = 0;

	public function __construct() {
		parent::__construct();
	
		page_redirect();		
		$this->load->model('deduction_tagging_model','deduction_tag');		        		
		$this->load->library('form_validation');
		$this->page = "Deduction Tagging";
	}
	
	public function index() {
		if (admin_login()) {
			$this->data = [				
				"title" => $this->setup->get_deduction_list(),		       
			];
			$this->datatable_script = true;
			$this->ibox = true;
			$this->ibox_header = "Employee Deductions Tagging";			
			$this->middle = "admin_deduction/Deduction_tagging";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	public function fetchbyId() {
		if (admin_login()) {
			if ($this->form_validation->run('fetch_emp_id') == FALSE) {         
					$result['status'] = false;
		            $result['message'] = $this->form_validation->error_array();
			}else {
				$check = $this->deduction_tag->checkEmpIfExist($this->input->post('input_emp_id'));						
				if($check != NULL) {					
					$result['status'] = true;
	            	$result['emp'] = $this->deduction_tag->getEmployeeDetails($this->input->post('input_emp_id'));	            	
				}else {
		           		$data_error = array (
							'input_emp_id'	=>	'Employee ID does not exist.',					
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

	public function get_emp_deduction_list($id) {
		if (admin_login()) {
			$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
			$select = "tbl_deduction.deduction_title, tbl_deduction.amount, tbl_deduction_employee.`status`, tbl_deduction_employee.created_at,tbl_deduction_employee.id";
			$where = "1 = 1 AND tbl_deduction.delete_status = 0 AND tbl_deduction_employee.employee_id = '".$id."'";
			$join =  [
				"tbl_deduction" => "tbl_deduction.deduction_id = tbl_deduction_employee.deduction_id"
			];
			$order_by = [];
			$limit = ["25" => 0];
			
			$aColumns = [
				"deduction_title",
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
			// 	$where .= " deduction_title LIKE '%".$search."%'";
			// 	$where .= " OR amount LIKE '%".$search."%'";
			// 	$where .= " OR ( DATE_FORMAT(tbl_deduction.created_at, '%b %d, %Y') ) LIKE '%".$search."%'";
			// 	$where .= " OR name LIKE '%".$search."%'";
			// 	$where .= " )";
			// }
			
			//total records
			foreach ($this->deduction_tag->select("COUNT(*) AS count", $where, $join) as $key => $value) {
				$data["iTotalDisplayRecords"] = $value->count;
				$data["iTotalRecords"] = $value->count;
			}
			
			foreach ($this->deduction_tag->select($select, $where, $join, $order_by, $limit) as $key => $value) {
				$id = $value->id;
				if($value->status == 0) {
					$value->status = '<p style="color:green;">ACTIVE</p>';
				}else if($value->status == 1) {
					$value->status = '<p style="color:red;">NOT ACTIVE</p>';
				}

				$this->total = $this->total + $value->amount;
				$data["data"][] = [
					$value->deduction_title,					
					number_format($value->amount, 2, '.',','),					
					$value->status,
					check_date($value->created_at, "M d, Y"),					
					'<button onclick="edit_deduc('."'".$value->id."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="Edit" id="btnR2"><i class="fa fa-pencil"></i> &nbsp;</i></button>'
				];
			}
			$data['totaldeductions'] = $this->total;
			echo json_encode($data);	
		} else {
			page_not_found();
		}
	}

	public function addtag_deduction_emp() {
		if (admin_login()) {
			if ($this->form_validation->run('deduction_tagging') == FALSE) {         
				$result['status'] = false;
	            $result['message'] = $this->form_validation->error_array();
			}else {				
				if($this->input->post('emp_id') != NULL) {
					$result['status'] = true;
		            $data = array (
		                'deduction_id' => $this->input->post('deduction_title'),                    
		                'status' => $this->input->post('status_emp_deduction'),	                
		                'employee_id' => $this->input->post('emp_id'),	
		                'created_by' => admin_id(),
		                'modified_by' => admin_id(),
		            );           
		            $insert = $this->deduction_tag->insert_for_deduction($data);	
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