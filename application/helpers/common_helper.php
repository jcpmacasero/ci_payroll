<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
	function sanitize($string) {
	    return htmlentities(strip_tags($string), ENT_COMPAT, 'UTF-8');
	}

	function delete_tag_content($start, $end, $string) {
		$startPos = strpos($string, $start);
		$endPos = strpos($string, $end);

		if (!$startPos || !$endPos) {
			return $string;
		}

		$textToDelete = substr($string, $startPos, ($endPos + strlen($end)) - $startPos);
		return str_replace($textToDelete, '', $string);
	}

	function delete_script_tag($string) {
		return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $string);
	}

	function clean_string($string) {
		return sanitize(html_escape(delete_script_tag($string)));
	}

	function clean_input_string($string) {
		return html_purify(delete_script_tag($string));
	}

	function clean_long_text($string) {
		return nl2br(html_escape(delete_script_tag($string)));
	}

	function clean_textarea($string) {
		return html_purify(delete_script_tag($string));
	}

	function clean_latin($value) {
		$value = str_replace('å', '', $value);
	    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
	    return $value;
	}

	function trim_str($str) {
		$str = trim($str);
		$str = strip_tags($str);
		$str = stripslashes($str);
		$str = str_replace("'", "\'", $str);
		return $str;
	}

	function trim_array($data = []){
		foreach ($data as $key => $value) {
			$value = trim($value);
			$value = strip_tags($value);
			$value = stripslashes($value);
			$data[$key] = $value;
		}
		
		return $data;
	}

	function pprint($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}

	function check_date($date, $format) {
		if ($date != null && $date != "0000-00-00") {
			$date = date($format, strtotime($date));
		} else {
			$date = "";
		}
		
		return $date;
	}

	function check_time($time, $format) {
		if ($time != null && $time != "00:00:00") {
			$time = date($format, strtotime($time));
		} else {
			$time = "";
		}
		
		return $time;
	}

	function check_datetime($datetime, $format) {
		if ($datetime != null && $datetime != "0000-00-00 00:00:00") {
			$datetime = date($format, strtotime($datetime));
		} else {
			$datetime = "";
		}
		
		return $datetime;
	}

	function now() {
		date_default_timezone_set("Asia/Manila");
		return $date = date('Y-m-d H:i:s');
	}

	function date_now() {
		date_default_timezone_set("Asia/Manila");
		return $date = date('Y-m-d');
	}

	function access_denied() {
		echo 'Access denied!';
	}

	function page_not_found() {
		$ci =& get_instance();
		$ci->load->view('common/Page_not_found');
	}

	// get common session data
	function login_id() {
		$ci =& get_instance();
		$ci->load->library('session');

		return $ci->session->userdata("login_id");
	}

	function login_date() {
		$ci =& get_instance();
		$ci->load->library('session');

		return $ci->session->userdata("login_date");
	}

	function login_name() {
		if (user_login()) {
			return user_name();
		} else if (admin_login()) {
			return admin_name();
		}
	}

	function login_photo() {
		if (user_login()) {
			return user_photo();
		} else if (admin_login()) {
			return admin_photo();
		}
	}
	// end get common session data

	// get user session data
	function user_login() {
		$ci =& get_instance();
		$ci->load->library('session');

		return $ci->session->userdata("user_login");
	}

	function user_name() {
		$ci =& get_instance();
		$ci->load->library('session');

		return $ci->session->userdata("user_name");
	}

	function user_photo() {
		$ci =& get_instance();
		$ci->load->library('session');

		$profile_image = clean_string($ci->session->userdata('user_photo'));
	    return $profile_image ? base_url($profile_image) : default_profile_image();
	}

	function department_id() {
		$ci =& get_instance();
		$ci->load->library('session');

		return $ci->session->userdata("department_id");
	}

	function position_id() {
		$ci =& get_instance();
		$ci->load->library('session');

		return $ci->session->userdata("position_id");
	}

	function user_id() {
		$ci =& get_instance();
		$ci->load->library('session');

		return $ci->session->userdata("user_id");
	}
	// end get user session data

	// get admin session data
	function admin_login() {
		$ci =& get_instance();
		$ci->load->library('session');

		return $ci->session->userdata("admin_login");
	}

	function admin_name() {
		$ci =& get_instance();
		$ci->load->library('session');

		return $ci->session->userdata("admin_name");
	}

	function admin_photo() {
		$ci =& get_instance();
		$ci->load->library('session');

		$profile_image = clean_string($ci->session->userdata('admin_photo'));
	    return $profile_image ? base_url($profile_image) : default_profile_image();
	}

	function admin_id() {
		$ci =& get_instance();
		$ci->load->library('session');

		return $ci->session->userdata("admin_id");
	}
	// end get admin session data
	
	function default_profile_image() {
	    return base_url('assets/img/common/default.png');
	}

	function page_redirect() {	
		$ci =& get_instance();

		if (!login_id()) {
			if ($ci->uri->segment(1) == "admin") {
				redirect(base_url('/admin'));
			} else {
				redirect(base_url());
			}
		}
	}

	function page_redirect_home() {
		$ci =& get_instance();
		$ci->load->library('session');

		if ($ci->session->has_userdata("login_id")) {
			if (user_login()) {
				redirect(base_url('user/dashboard'));
			} else if (admin_login()) {
				redirect(base_url('admin/dashboard'));
			}
		}
	}

	function do_upload($input_name, $upload_path, $file_name) {
		$ci =& get_instance();

		$path = "";
		// $num = mt_rand(1, 1000000);

		$config['upload_path'] 		= $upload_path;
		$config['allowed_types'] 	= 'pdf|docx|xls|ppt|jpg|png|jpeg|txt';
		$config['max_size']     	= '100000';
		$config['overwrite'] 		= true;
		$config['file_name'] 		= $file_name;
		// $config['max_width'] 		= '5000';
		// $config['max_height'] 		= '5000';

		$ci->load->library('upload', $config);
		$ci->upload->initialize($config);
		
		$upload = $ci->upload->do_upload($input_name);
		if($upload) {
			$path = $file_name;
		}
		return $path;
    }

    function multiple_upload($input_name, $upload_path, $prefix) {
		$ci =& get_instance();
		
    	$path = [];

	    $config['upload_path'] 		=  $upload_path;
		$config['allowed_types'] 	= 'pdf|docx|xls|xlsx|csv|ppt|jpg|png|jpeg|txt';
		$config['max_size']     	= '100000';
		$config['overwrite'] 		= true;
		$config['max_width'] 		= '5000';
		$config['max_height'] 		= '5000';

        $ci->load->library('upload', $config);
        if(array_key_exists($input_name, $_FILES)) {
	        foreach($_FILES[$input_name]['name'] as $key => $value) {

	        	$file_name = (!empty($_FILES[$input_name]['name'][$key]) ? basename($_FILES[$input_name]['name'][$key]) : "");
				$file_name = $prefix."_".$file_name;

	            $_FILES[$input_name.'[]']['name'] 	= $_FILES[$input_name]['name'][$key];
	            $_FILES[$input_name.'[]']['type'] 	= $_FILES[$input_name]['type'][$key];
	            $_FILES[$input_name.'[]']['tmp_name'] = $_FILES[$input_name]['tmp_name'][$key];
	            $_FILES[$input_name.'[]']['error'] 	= $_FILES[$input_name]['error'][$key];
	            $_FILES[$input_name.'[]']['size'] 	= $_FILES[$input_name]['size'][$key];

	            $config['file_name'] = $file_name;

	            $ci->upload->initialize($config);

	           	$upload = $ci->upload->do_upload($input_name."[]");
				if($upload) {
					$path[] = ["name" => $file_name, "type" => $_FILES[$input_name]['type'][$key]]; 
				}
	        }
	    }
        return $path;
	}
 ?>