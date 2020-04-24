<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	
		page_redirect();		
		$this->load->model('salary_model','salary');		        				
		$this->load->library('form_validation');
		$this->page = "Salary";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;
			$this->ibox = true;		
			$this->middle = "admin_company_structure/Salary";
			$this->admin_layout();			
		} else {
			page_not_found();
		}
	}

	public function get_salary_list() {
		if (admin_login()) {
			$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
			$select = "tbl_salary.salary_id, tbl_salary.salary_title, tbl_salary.salary_status, tbl_salary.amount, tbl_admin.`name`, tbl_salary.created_at";
			$where = "1 = 1 AND tbl_salary.delete_status = 0";
			$join =  [
				"tbl_admin" => "tbl_admin.admin_id = tbl_salary.created_by"
			];
			$order_by = [];
			$limit = ["25" => 0];
			
			$aColumns = [
				"salary_title",
				"amount",
				"salary_status",
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
				$where .= " salary_title LIKE '%".$search."%'";
				$where .= " OR amount LIKE '%".$search."%'";
				$where .= " OR salary_status LIKE '%".$search."%'";
				$where .= " OR ( DATE_FORMAT(tbl_salary.created_at, '%b %d, %Y') ) LIKE '%".$search."%'";
				$where .= " OR name LIKE '%".$search."%'";
				$where .= " )";
			}
			
			//total records
			foreach ($this->salary->select("COUNT(*) AS count", $where, $join) as $key => $value) {
				$data["iTotalDisplayRecords"] = $value->count;
				$data["iTotalRecords"] = $value->count;
			}
			
			foreach ($this->salary->select($select, $where, $join, $order_by, $limit) as $key => $value) {
				$id = $value->salary_id;
				if($value->salary_status == 0) {
					$value->salary_status = "Per Day";
				}else if($value->salary_status == 1) {
					$value->salary_status = "Fixed";
				}
			
				$data["data"][] = [
					$value->salary_title,
					number_format($value->amount, 2, '.',','),					
					$value->salary_status,
					check_date($value->created_at, "M d, Y"),										
					$value->name,
					'<button onclick="update_sal('."'".$value->salary_id."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="Edit" id="btnR2"><i class="fa fa-pencil"></i> &nbsp;</i></button>'
				];
			}
			
			echo json_encode($data);	
		} else {
			page_not_found();
		}		
	}

	public function add_salary() {
		if (admin_login()) {
			if ($this->form_validation->run('add_salary') == FALSE) {         
				$result['status'] = false;
	            $result['message'] = $this->form_validation->error_array();
			}else {
				$result['status'] = true;
	            $data = array (
	                'salary_title' => $this->input->post('salary_name'),                    
	                'amount' => $this->input->post('salary_amt'),
	                'salary_status' => $this->input->post('sal_stat'),
	                'created_by' => admin_id(),
	                'modified_by' => admin_id(),
	            );           
	           $insert = $this->salary->insert_salary($data);
			}
			echo json_encode($result);
		} else {
			page_not_found();
		}
	}

	public function get_specific_salary($id) {
		if (admin_login()) {
			$data_salary = $this->salary->get_salary_by_id($id);
        	echo json_encode($data_salary);
		}else {
			page_not_found();
		}	
	}

	public function edit_salary() {
		if (admin_login()) {
			$data = array(
	            'salary_title' => $this->input->post('salary_name'),	                        
	            'salary_status' => $this->input->post('sal_stat'),
	            'amount' => $this->input->post('salary_amt'),
	            'modified_by' => admin_id()
	        );
	        $updated_id = $this->salary->update_salary($data,array('salary_id' => $this->input->post('salary_id')));
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

/* End of file Salary.php */
/* Location: ./application/modules/admin_dashboard/controllers/Dashboard.php */