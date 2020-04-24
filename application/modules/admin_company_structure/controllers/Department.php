<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	
		page_redirect();		
		$this->load->model('department_model','department');		        		
		$this->load->library('form_validation');
		$this->page = "Department";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;
			$this->ibox = true;
			$this->ibox_header = "Department List";
			$this->ibox_id = "ibox_department";
			$this->middle = "admin_company_structure/Department";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	public function add_department() {
		if (admin_login()) {
			if ($this->form_validation->run('add_department') == FALSE) {         
				$result['status'] = false;
	            $result['message'] = $this->form_validation->error_array();
			}else {
				$result['status'] = true;
	            $data = array (
	                'department_name' => $this->input->post('dept_name'),                    
	                'created_by' => admin_id(),
	                'modified_by' => admin_id(),
	            );           
	           $insert = $this->department->insert_department($data);
			}
			echo json_encode($result);
		} else {
			page_not_found();
		}
	}

	public function edit_department() {
		if (admin_login()) {
			$data = array(
	            'department_name' => $this->input->post('dept_name'),	                        
	            'modified_by' => admin_id(),
	        );
	        $updated_id = $this->department->update_department($data,array('department_id' => $this->input->post('dept_id')));	        
	        if($updated_id > 0) {
	        	$result['status'] = true;	
	        }else {
	        	$result['status'] = false;
	        }	        
	        echo json_encode($result);	       
		}else {

		}
	}

	public function get_specific_dept($id) {
		if (admin_login()) {
			$data_dept = $this->department->get_dept_by_id($id);
        	echo json_encode($data_dept);
		}else {
			page_not_found();
		}	
	}

	public function get_department_list() {		
		if (admin_login()) {
			$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
			$select = "tbl_department.department_id, tbl_department.department_name, tbl_department.created_at, tbl_admin.`name`";
			$where = "1 = 1 AND tbl_department.delete_status = 0";
			$join =  [
				"tbl_admin" => "tbl_admin.admin_id = tbl_department.created_by"
			];
			$order_by = [];
			$limit = ["25" => 0];
			
			$aColumns = [
				"department_name",
				"created_at",
				"created_by",
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
				$where .= " department_name LIKE '%".$search."%'";
				$where .= " OR ( DATE_FORMAT(tbl_department.created_at, '%b %d, %Y') ) LIKE '%".$search."%'";
				$where .= " OR name LIKE '%".$search."%'";
				$where .= " )";
			}
			
			//total records
			foreach ($this->department->select("COUNT(*) AS count", $where, $join) as $key => $value) {
				$data["iTotalDisplayRecords"] = $value->count;
				$data["iTotalRecords"] = $value->count;
			}
			
			foreach ($this->department->select($select, $where, $join, $order_by, $limit) as $key => $value) {
				$id = $value->department_id;
			
				$data["data"][] = [
					$value->department_name,
					check_date($value->created_at, "M d, Y"),
					$value->name,
					'<button onclick="update_dept('."'".$value->department_id."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="Edit" id="btnR2"><i class="fa fa-pencil"></i> &nbsp;</i></button>'
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