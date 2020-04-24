<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Position extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	
		page_redirect();		
		$this->load->model('position_model','position');		        		
		$this->load->model('setup_model','setup');
		$this->load->library('form_validation');
		$this->page = "Position";
	}
	
	public function index() {
		if (admin_login()) {
			$this->data = [				
				"department" => $this->setup->get_department_list(),		       
				"salary" => $this->setup->get_salary_list(),		       
			];
			$this->datatable_script = true;
			$this->ibox = true;
			$this->middle = "admin_company_structure/Position";
			$this->admin_layout();			
		} else {
			page_not_found();
		}
	}

	public function get_position_list() {
		if (admin_login()) {
			$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
			$select = "tbl_position.position_id, tbl_position.position_title, tbl_department.department_name, tbl_position.created_at, tbl_admin.`name`, tbl_salary.salary_title";
			$where = "1 = 1 AND tbl_position.delete_status = 0";
			$join =  [
				"tbl_admin" => "tbl_admin.admin_id = tbl_position.created_by",
				"tbl_department" => "tbl_department.department_id = tbl_position.department_id",
				"tbl_salary" => "tbl_salary.salary_id = tbl_position.salary_id",
			];
			$order_by = [];
			$limit = ["25" => 0];
			
			$aColumns = [
				"position_title",
				"department_name",				
				"salary_title",	
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
				$where .= " position_title LIKE '%".$search."%'";
				$where .= " OR department_name LIKE '%".$search."%'";				
				$where .= " OR ( DATE_FORMAT(tbl_position.created_at, '%b %d, %Y') ) LIKE '%".$search."%'";
				$where .= " OR name LIKE '%".$search."%'";
				$where .= " )";
			}
			
			//total records
			foreach ($this->position->select("COUNT(*) AS count", $where, $join) as $key => $value) {
				$data["iTotalDisplayRecords"] = $value->count;
				$data["iTotalRecords"] = $value->count;
			}
			
			foreach ($this->position->select($select, $where, $join, $order_by, $limit) as $key => $value) {
				$id = $value->position_id;				
			
				$data["data"][] = [
					$value->position_title,
					$value->department_name,										
					$value->salary_title,		
					check_date($value->created_at, "M d, Y"),										
					$value->name,
					'<button onclick="update_pos('."'".$value->position_id."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="Edit" id="btnR2"><i class="fa fa-pencil"></i> &nbsp;</i></button>'
				];
			}
			
			echo json_encode($data);	
		} else {
			page_not_found();
		}			
	}

	public function add_position() {
		if (admin_login()) {
			if ($this->form_validation->run('add_position') == FALSE) {         
				$result['status'] = false;
	            $result['message'] = $this->form_validation->error_array();
			}else {
				$result['status'] = true;
	            $data = array (
	                'position_title' => $this->input->post('position_name'),                    
	                'department_id' => $this->input->post('dept_name'),
	                'salary_id' => $this->input->post('sal_grade'),
	                'created_by' => admin_id(),
	                'modified_by' => admin_id(),
	            );           
	           $insert = $this->position->insert_position($data);
			}
			echo json_encode($result);
		} else {
			page_not_found();
		}
	}

	public function get_specific_position($id) {
		if (admin_login()) {
			$data_position = $this->position->get_position_by_id($id);
        	echo json_encode($data_position);
		}else {
			page_not_found();
		}	
	}

	public function edit_position() {
		if (admin_login()) {
			$data = array(
	            'position_title' => $this->input->post('position_name'),	                        
	            'department_id' => $this->input->post('dept_name'),
	            'salary_id' => $this->input->post('sal_grade'),
	            'modified_by' => admin_id()
	        );
	        $updated_id = $this->position->update_position($data,array('position_id' => $this->input->post('position_id')));
	        if($updated_id > 0) {
	        	$result['status'] = true;	
	        }else {
	        	$result['status'] = false;
	        }	        
	        echo json_encode($result);
		}else {

		}
	}
	
}

/* End of file Position.php */
/* Location: ./application/modules/admin_dashboard/controllers/Dashboard.php */