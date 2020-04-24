<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Additional extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	
		page_redirect();		
		$this->load->model('additional_model','additional');		        		
		$this->load->library('form_validation');
		$this->page = "Additional";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;
			$this->ibox = true;
			$this->ibox_header = "Additional List";
			$this->ibox_id = "ibox_additional";
			$this->middle = "admin_additional/Additional";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	public function add_additional() {
		if (admin_login()) {
			if ($this->form_validation->run('add_additional') == FALSE) {         
				$result['status'] = false;
	            $result['message'] = $this->form_validation->error_array();
			}else {
				$result['status'] = true;
	            $data = array (
	                'additional_title' => $this->input->post('additional_title'),                    
	                'amount' => $this->input->post('amt_additional'),
	                'additional_status' => $this->input->post('add_stat'),
	                'created_by' => admin_id(),
	                'modified_by' => admin_id(),
	            );           
	           $insert = $this->additional->insert_additional($data);
			}
			echo json_encode($result);
		} else {
			page_not_found();
		}
	}

	public function edit_additional() {
		if (admin_login()) {
			$data = array(
	            'additional_title' => $this->input->post('additional_title'),	                        
	            'amount' => $this->input->post('amt_additional'),
	            'additional_status' => $this->input->post('add_stat'),
	            'created_by' => admin_id(),
	            'modified_by' => admin_id(),
	        );
	        $updated_id = $this->additional->update_additional($data,array('additional_id' => $this->input->post('add_id')));	        	       
	        if($updated_id > 0) {
	        	$result['status'] = true;	
	        }else {
	        	$result['status'] = false;
	        }	        
	        echo json_encode($result);
		}else {

		}
	}

	public function get_specific_addt($id) {
		if (admin_login()) {
			$data_add = $this->additional->get_addt_by_id($id);
        	echo json_encode($data_add);
		}else {
			page_not_found();
		}	
	}

	public function get_additional_list() {
		if (admin_login()) {
			$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
			$select = "tbl_admin.`name`, tbl_additional.additional_id, tbl_additional.additional_title, tbl_additional.amount, tbl_additional.additional_status, tbl_additional.created_at";
			$where = "1 = 1 AND tbl_additional.delete_status = 0";
			$join =  [
				"tbl_admin" => "tbl_admin.admin_id = tbl_additional.created_by"
			];
			$order_by = [];
			$limit = ["25" => 0];
			
			$aColumns = [
				"additional_title",
				"amount",
				"additional_status",
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
				$where .= " additional_title LIKE '%".$search."%'";
				$where .= " OR amount LIKE '%".$search."%'";
				$where .= " OR ( DATE_FORMAT(tbl_deduction.created_at, '%b %d, %Y') ) LIKE '%".$search."%'";
				$where .= " OR name LIKE '%".$search."%'";
				$where .= " )";
			}
			
			//total records
			foreach ($this->additional->select("COUNT(*) AS count", $where, $join) as $key => $value) {
				$data["iTotalDisplayRecords"] = $value->count;
				$data["iTotalRecords"] = $value->count;
			}
			
			foreach ($this->additional->select($select, $where, $join, $order_by, $limit) as $key => $value) {
				$id = $value->additional_id;
				if($value->additional_status == 0) {
					$value->additional_status = '<p style="color:red;">DISABLED</p>';
				}else if($value->additional_status == 1) {
					$value->additional_status = '<p style="color:green;">ENABLED</p>';
				}
			
				$data["data"][] = [
					$value->additional_title,
					number_format($value->amount, 2, '.',','),					
					$value->additional_status,
					check_date($value->created_at, "M d, Y"),
					$value->name,
					'<button onclick="edit_additional('."'".$value->additional_id."'".');" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="right" title="Edit" id="btnR2"><i class="fa fa-pencil"></i> &nbsp;</i></button>'
				];
			}
			
			echo json_encode($data);	
		} else {
			page_not_found();
		}
	}


}

/* End of file Dashboard.php */
/* Location: ./application/modules/admin_dashboard/controllers/Dashboard.php */