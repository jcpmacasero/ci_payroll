<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_model extends MY_Model {

	public $tbl_name = "tbl_calendar";	

    function saveEvent($data) {
		$insert = $this->db->insert($this->tbl_name,$data);
        return $this->db->insert_id();
    }

    function updateEvent($id,$data) {
        $this->db->where('calendar_id', $id);
        $this->db->update($this->tbl_name, $data);
        return $this->db->affected_rows();
    }

    function getEvents($startdate,$enddate) {                
        $query = $this->db->query('SELECT tbl_calendar.calendar_id, tbl_calendar.event_date, tbl_calendar.event_status FROM tbl_calendar WHERE tbl_calendar.delete_status = 0 AND (tbl_calendar.event_date BETWEEN "'.$startdate.'" AND "'.$enddate.'")');
        return $query->result();
    }

}

/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */