<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public $tbl_name;

	public function __construct() {
		parent::__construct();
		$this->load->database("default");
	}
	
	public function select($select = "*", $where = [], $join = [], $order_by = [], $limit = [], $group_by = "") {
		if(is_array($where)) {
			$where = $this->trim($where);
		}

		$join = $this->trim($join);
		$order_by = $this->trim($order_by);
		$limit = $this->trim($limit);

		$this->db->select($select)->from($this->tbl_name);

		if(is_array($where)) {
			if($where !== null) {
				foreach ($where as $key => $value) {
					$this->db->where($key, $value);
				}
			}
		} else {
			$this->db->where($where);
		}

		if($join !== null) {
			foreach ($join as $key => $value) {
				$this->db->join($key, $value, "left");
			}
		}

		if($order_by !== null) {
			foreach ($order_by as $key => $value) {
				$this->db->order_by($key, $value);
			}
		}

		if($limit !== null) {
			foreach ($limit as $key => $value) {
				$this->db->limit($key, $value);
			}
		}

		if($group_by !== null) {
			$this->db->group_by($group_by);
		}

		$query = $this->db->get();

		return $query->result();
	}

	public function insert($data = []){
		$data = $this->trim($data);

		if($this->db->insert($this->tbl_name, $data)){
			return true;
		}

		return false;
	}

	public function update($data, $where) {
		$data = $this->trim($data);
		$where = $this->trim($where);

		if($this->db->where($where)->update($this->tbl_name, $data)){
			return true;
		}

		return false;
	}

	public function delete($data = []){
		$data = $this->trim($data);

		if($this->db->delete($this->tbl_name, $data)){
			return true;
		}

		return false;
	}

	public function query($query = ""){
		$query = $this->db->query($query);
		return $query;
	}
	
	public function trim($data = []){
		foreach ($data as $key => $value) {
			$value = trim($value);
			$value = strip_tags($value);
			$value = stripslashes($value);
			$data[$key] = $value;
		}
		
		return $data;
	}	

}

/* End of file MY_model.php */
/* Location: ./application/core/MY_model.php */