<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_view_payroll_history extends MY_Controller {

	private $data_array = array();
	
	public function __construct() {
		parent::__construct();
		page_redirect();
		$this->load->model('Admin_payroll_history_model','history');		
		$this->page = "View Payroll History";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;
			$this->file_drop_script = true;
			$this->ibox = true;
			$this->ibox_header = "View Payroll History";
			$this->ibox_id = "ibox_view_payroll_history";
			$this->middle = "admin_payroll/Admin_view_payroll_history";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	public function get_payroll_history_list() {		
		if (admin_login()) {
			$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
			$select = "tbl_salary_paid.salary_paid_id,tbl_salary_paid.salary_status, tbl_salary_paid.date_start_paid, tbl_salary_paid.date_end_paid, tbl_salary_paid.paid_date, tbl_salary_paid.salary_amount";									
			$where = "1=1";
			$join =  [];
			$order_by = [];
			$limit = ["25" => 0];
			
			$aColumns = [
				"paid_date",
				"salary_status",
				"datestart_paid",
				"salary_amount"
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
				$where .= " paid_date LIKE '%".$search."%'";
				$where .= " OR ( DATE_FORMAT(paid_date.created_at, '%b %d, %Y') ) LIKE '%".$search."%'";
				$where .= " OR name LIKE '%".$search."%'";
				$where .= " )";
			}
			
			//total records
			foreach ($this->history->select("COUNT(*) AS count", $where, $join) as $key => $value) {
				$data["iTotalDisplayRecords"] = $value->count;
				$data["iTotalRecords"] = $value->count;
			}
			
			foreach ($this->history->select($select, $where, $join, $order_by, $limit) as $key => $value) {
				$id = $value->salary_paid_id;
				if($value->salary_status == 0) {
					$value->salary_status = "Per Day";
				}else if($value->salary_status == 1) {
					$value->salary_status = "Fixed";
				}
			
				$data["data"][] = [
					$value->paid_date,
					$value->salary_status,
					check_date($value->date_start_paid, "M d, Y") ." - ". check_date($value->date_end_paid, "M d, Y"),
					$value->salary_amount					
				];
			}
			
			echo json_encode($data);	
		} else {
			page_not_found();
		}
	}
}

/* End of file Admin_view_payroll_history.php */
/* Location: ./application/modules/admin_payroll/controllers/Admin_view_payroll_history.php */