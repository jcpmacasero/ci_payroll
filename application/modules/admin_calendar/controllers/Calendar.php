<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('calendar_model','calendar');
		page_redirect();
		
		$this->page = "Calendar";
	}
	
	public function index() {
		if (admin_login()) {
			$this->middle = "admin_calendar/Calendar";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	public function editCalendar() {
		if (admin_login()) {
			$update_event = array (
				'event_date' => $this->input->post('eventUpdate'),
				'modified_by' => admin_id(),
			);
			$update_id = $this->calendar->updateEvent($this->input->post('id'),$update_event);
			echo json_encode($update_id);
		} else {
			page_not_found();
		}
	}

	public function addCalendar() {
		if (admin_login()) {
			if($this->input->post('title') == "No Operation") {
					$this->event_status = 0;		
			}else if($this->input->post('title') == "Holiday +30% Pay") {
					$this->event_status = 1;
			}else if($this->input->post('title') == "Holiday Double Pay") {
					$this->event_status = 2;
			}

			$insert_event = array (
				'event_date' => $this->input->post('eventDate'),			
				'event_status' => $this->event_status,
				'created_by' => admin_id(),
	            'modified_by' => admin_id(),
			);

			$insert_id = $this->calendar->saveEvent($insert_event);		
			echo json_encode($insert_id);
		} else {
			page_not_found();
		}
	}

	public function getAllEvents() {
		if (admin_login()) {
			$start = $this->input->get('start');
			$end = $this->input->get('end');				

			$events = $this->calendar->getEvents($start,$end);		
			$data_events = array();
			foreach($events as $result) {
				if($result->event_status == 0) {
					$result->event_status = "No Operation";		
					}else if($result->event_status == 1) {
					$result->event_status = "Holiday +30% Pay";
					}else if($result->event_status == 2) {
					$result->event_status = "Holiday Double Pay";
				}
				$data_events[] = array (
					'id' => $result->calendar_id,			
					'title' => $result->event_status,
					'start' => $result->event_date
				);
			}
			echo json_encode(array("events" => $data_events));
			
		}else {
			page_not_found();
		}
	}
}

/* End of file Calendar.php */
/* Location: ./application/modules/admin_dashboard/controllers/Calendar.php */