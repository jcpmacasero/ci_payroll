<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class addition_tagging_model extends MY_Model {

    public $tbl_name = "tbl_additional_employee";

    public function insert_for_additional($data) {
    	$insert = $this->db->insert($this->tbl_name,$data);
        return $this->db->insert_id();
    }
   
}

/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */