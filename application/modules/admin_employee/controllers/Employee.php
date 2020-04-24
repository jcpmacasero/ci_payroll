<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends MY_Controller {

	public function __construct() {
		parent::__construct();
	
		page_redirect();

		$this->load->model('user_model', 'user');
		$this->load->model('family_background_model', 'family_background');
		$this->load->model('spouse_model', 'spouse');
		$this->load->model('educational_background_model', 'educational_background');
		$this->load->model('work_experience_model', 'work_experience');
        $this->load->library('bcrypt');
		
		$this->page = "Employee";
	}
	
	public function index() {
		if (admin_login()) {
			$this->datatable_script = true;
			$this->ibox = true;
			$this->ibox_header = "Employee <small>List</small>";
			$this->ibox_id = "ibox_employee";
			$this->ibox_tools = [
				"<a name='btn_add' id='btn_add' href='".base_url("admin/employee/form")."' class='btn btn-sm btn-primary'><span class='fa fa-plus'></span> Add Employee</a>"
			];
			$this->middle = "admin_employee/Employee";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	public function form() {
		if (admin_login()) {
			$this->data = [
				"employee_info"			=> $this->get_employee_info($this->uri->segment(4)),
				"employee_educ_info"	=> $this->get_employee_educ_info($this->uri->segment(4)),
				"employee_work_info"	=> $this->get_employee_work_info($this->uri->segment(4)),
				"provinces" 			=> $this->setup->get_province_list(),
		        "religions" 			=> $this->setup->get_religion_list(),
		        "citizenships" 			=> $this->setup->get_citizenship_list(),
		        "departments" 			=> $this->setup->get_department_list()
			];

			$this->ibox = true;
			$this->ibox_header = "Employee <small>Form</small>";
			$this->ibox_id = "ibox_employee";
			$this->ibox_tools = [
				"<a name='btn_add' id='btn_add' href='".base_url("admin/employee")."' class='btn btn-sm btn-primary'><span class='fa fa-arrow-left'></span> Back To Employee List</a>"
			];
			$this->middle = "admin_employee/Form";
			$this->admin_layout();
		} else {
			page_not_found();
		}
	}

	function get_employee_list() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		if (admin_login()) {
			$data = ["iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "data" => []]; 
		
			$select = "*";
			$where = "1 = 1 AND tbl_user.delete_status = 0";
			$join =  [
				"tbl_position" => "tbl_user.position_id = tbl_position.position_id",
				"tbl_citizenship" => "tbl_user.citizenship_id = tbl_citizenship.citizenship_id",
				"tbl_religion" => "tbl_user.religion_id = tbl_religion.religion_id",
				"tbl_city" => "tbl_user.city_id = tbl_city.city_id"
			];
			$order_by = [];
			$limit = ["25" => 0];
			
			$aColumns = [
				"",
				"employee_id",
				"gender",
				"email",
				"birthdate",
				"position_title",
				"user_status",
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
				$where .= " CONCAT(lastname, ', ', firstname, ' ', LEFT(middlename, 1), '. ', name_ext) LIKE '%".$search."%'";
				$where .= " OR CONCAT(lastname, ', ', firstname, ' ', middlename, ' ', name_ext) LIKE '%".$search."%'";
				$where .= " OR employee_id LIKE '%".$search."%'";
				$where .= " OR IF(gender='M', 'Male', IF(gender='F', 'Female', 'Not Specified')) LIKE '%".$search."%'";
				$where .= " OR email LIKE '%".$search."%'";
				$where .= " OR ( DATE_FORMAT(birthdate, '%b %d, %Y') ) LIKE '%".$search."%'";
				$where .= " OR position_title LIKE '%".$search."%'";
				$where .= " OR user_status LIKE '%".$search."%'";
				$where .= " )";
			}
			
			//total records
			foreach ($this->user->select("COUNT(*) AS count", $where, $join) as $key => $value) {
				$data["iTotalDisplayRecords"] = $value->count;
				$data["iTotalRecords"] = $value->count;
			}
			
			foreach ($this->user->select($select, $where, $join, $order_by, $limit) as $key => $value) {
				$id = $value->user_id;
			
				$url_edit = "employee/form/$id";
				$url_delete = "\"admin_employee/employee/delete_user\"";
				$form_id = "\"form_user\"";
				$tbl_id = "[tbl_user]";

				$user_status = "";
				if ($value->user_status == "PENDING") {
					$user_status = '<label class="label label-info">PENDING</label>';
				} else if ($value->user_status == "ACTIVATED") {
					$user_status = '<label class="label label-primary">ACTIVATED</label>';
				} 
			
				$data["data"][] = [
					"<img src=".($value->photo ? base_url($value->photo) : default_profile_image())." class='img-thumbnail' style='width: 100px;'>",
					$value->employee_id,
					$this->setup->user_fullname($id),
					$value->gender = "M" ? "Male" : $value->gender = "F" ? "Female" : "Not Specified",
					$value->email,
					check_date($value->birthdate, "M d, Y"),
					$value->position_title,
					$user_status,
					"<button class='btn btn-info btn-circle' name='btn_edit_permission' data-toggle='modal' href='#modal_permission' onclick='get_permission($id)' title='Permission'><span class='fa fa-cog'></span></button>
					<a class='btn btn-success btn-circle btn_edit' name='btn_edit' href='$url_edit' title='Edit'><span class='fa fa-edit'></span></a>
					<button class='btn btn-danger btn-circle' name='btn_delete' onclick='delete_this($url_delete, $id, $tbl_id)' title='Delete'><span class='fa fa-trash'></span></button>"
				];
			}
			
			echo json_encode($data);	
		} else {
			page_not_found();
		}
	}

	function get_employee_info($user_id) {
		if (admin_login()) {
			$data = [];
					
			$select = "*";
			$where = ["user_id" => $user_id];
			$join = [
				"tbl_city" => "tbl_user.city_id = tbl_city.city_id",
				"tbl_position" => "tbl_user.position_id = tbl_position.position_id"
			];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->user->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = [
					"user_id"				=> $value->user_id,
					"photo"					=> $value->photo,
					"department_id"			=> $value->department_id,
					"position_id"			=> $value->position_id,
					"employee_id"			=> $value->employee_id,
					"firstname"				=> $value->firstname,
					"middlename"			=> $value->middlename,
					"lastname"				=> $value->lastname,
					"name_ext"				=> $value->name_ext,
					"gender"				=> $value->gender,
					"contact_no"			=> $value->contact_no,
					"email"					=> $value->email,
					"birthdate"				=> check_date($value->birthdate, "m/d/Y"),
					"citizenship_id"		=> $value->citizenship_id,
					"religion_id"			=> $value->religion_id,
					"civil_status"			=> $value->civil_status,
					"dependent_children"	=> $value->dependent_children,
					"place_of_birth"		=> $value->place_of_birth,
					"street_address"		=> $value->street_address,
					"city_id"				=> $value->city_id,
					"province_id"			=> $value->province_id,
					"tin_no"				=> $value->tin_no,
					"pag_ibig_no"			=> $value->pag_ibig_no,
					"philhealth_no"			=> $value->philhealth_no,
					"sss_no"				=> $value->sss_no,
					"user_status"			=> $value->user_status
				];
			}

			foreach ($this->family_background->select($select, $where) as $key => $value) {
				$data += [
					"family_background_id" 	=> $value->family_background_id,
					"fathers_name" 			=> $value->fathers_name,
					"fathers_occupation" 	=> $value->fathers_occupation,
					"fathers_birthdate" 	=> $value->fathers_birthdate,
					"mothers_name" 			=> $value->mothers_name,
					"mothers_occupation" 	=> $value->mothers_occupation,
					"mothers_birthdate" 	=> $value->mothers_birthdate
				];
			}

			foreach ($this->spouse->select($select, $where) as $key => $value) {
				$data += [
					"spouse_id" 		=> $value->spouse_id,
					"spouse_name" 		=> $value->spouse_name,
					"spouse_occupation" => $value->spouse_occupation,
					"spouse_birthdate" 	=> $value->spouse_birthdate
				];
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

	function get_employee_educ_info($user_id) {
		if (admin_login()) {
			$data = [];

			$select = "*";
			$where = ["user_id" => $user_id, "delete_status" => 0];

			foreach ($this->educational_background->select($select, $where) as $key => $value) {
				$data[] = [
					"educational_background_id"	=> $value->educational_background_id,
					"school_level"				=> $value->school_level,
					"name_of_school"			=> $value->name_of_school,
					"date_attended"				=> check_date($value->date_attended, "m/d/Y"),
					"date_graduated"			=> check_date($value->date_graduated, "m/d/Y")
				];
			}

			return $data;
		} else {
			page_not_found();
		}
	}

	function get_employee_work_info($user_id) {
		if (admin_login()) {
			$data = [];

			$select = "*";
			$where = ["user_id" => $user_id, "delete_status" => 0];

			foreach ($this->work_experience->select($select, $where) as $key => $value) {
				$data[] = [
					"work_exp_id"		=> $value->work_exp_id,
					"position"			=> $value->position,
					"name_of_company"	=> $value->name_of_company,
					"date_attended"		=> $value->date_attended,
					"date_ended"		=> $value->date_ended
				];
			}

			return $data;
		} else {
			page_not_found();
		}
	}

	function delete_user() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		if (admin_login()) {
			$this->db->trans_begin();
			$ret = [
				"success" 	=> false,
				"msg"		=> "Something went wrong"
			];

			$where = ["user_id" => $this->input->post("value")];

			$data = [
				"delete_status" => 1,
				"modified_by"	=> admin_id() 
			];

			$this->user->update($data, $where);

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$ret = [
					'success' 	=> false,
					'msg'		=> '<span class="fa fa-warning"></span> Something went wrong'
				];
			} else {
			    $this->db->trans_commit();
			    $ret = [
					"success" 	=> true,
					"msg"		=> "Deleted"
				];
			}

			echo json_encode($ret);
		} else {
			page_not_found();
		}
	}

	function delete_educ() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		if (admin_login()) {
			$this->db->trans_begin();
			$ret = [
				"success" 	=> false,
				"msg"		=> "Something went wrong"
			];

			$where = ["educational_background_id" => $this->input->post("value")];

			$data = [
				"delete_status" => 1,
				"date_deleted" 	=> now(),
				"delete_by"		=> admin_id() 
			];

			$this->educational_background->update($data, $where);

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$ret = [
					'success' 	=> false,
					'msg'		=> '<span class="fa fa-warning"></span> Something went wrong'
				];
			} else {
			    $this->db->trans_commit();
			    $ret = [
					"success" 	=> true,
					"msg"		=> "Deleted"
				];
			}

			echo json_encode($ret);
		} else {
			page_not_found();
		}
	}

	function delete_work() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		if (admin_login()) {
			$this->db->trans_begin();
			$ret = [
				"success" 	=> false,
				"msg"		=> "Something went wrong"
			];

			$where = ["work_exp_id" => $this->input->post("value")];

			$data = [
				"delete_status" => 1,
				"date_deleted" 	=> now(),
				"delete_by"		=> admin_id() 
			];

			$this->work_experience->update($data, $where);

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$ret = [
					'success' 	=> false,
					'msg'		=> '<span class="fa fa-warning"></span> Something went wrong'
				];
			} else {
			    $this->db->trans_commit();
			    $ret = [
					"success" 	=> true,
					"msg"		=> "Deleted"
				];
			}

			echo json_encode($ret);
		} else {
			page_not_found();
		}
	}

	function insert_employee() {
		if (!$this->input->is_ajax_request()) {
		    echo "Access denied!";
		    exit();
		}

		if (admin_login()) {
			$this->db->trans_begin();
			$ret = [
				"success" 	=> false,
				"msg"		=> "Something went wrong"
			];
			
			$user_id 				= trim_str($this->input->post("user_id"));
			$family_background_id 	= trim_str($this->input->post("family_background_id"));
			$spouse_id 				= trim_str($this->input->post("spouse_id"));

			$employee_id 		= trim_str($this->input->post("employee_id"));
			$firstname 			= trim_str($this->input->post("firstname"));
			$middlename 		= trim_str($this->input->post("middlename"));
			$lastname 			= trim_str($this->input->post("lastname"));
			$name_ext 			= trim_str($this->input->post("name_ext"));
			$email 				= trim_str($this->input->post("email"));
			$password 			= $this->input->post("password");
			$confirm_password 	= $this->input->post("confirm_password");

			$data = [
				'employee_id' 			=> $this->input->post('employee_id'),
	            'position_id' 			=> $this->input->post('position_id'),
	            'citizenship_id' 		=> $this->input->post('citizenship_id'),
	            'religion_id' 			=> $this->input->post('religion_id'),
	            'firstname' 			=> $this->input->post('firstname'),
	            'middlename' 			=> $this->input->post('middlename'),
	            'lastname' 				=> $this->input->post('lastname'),
	            'name_ext' 				=> $this->input->post('name_ext'),
	            'email' 				=> $this->input->post('email'),
	            'birthdate' 			=> check_date($this->input->post('birthdate'), "Y-m-d"),
	            'gender'				=> $this->input->post('gender'),
	            'civil_status' 			=> $this->input->post('civil_status'),
	            'dependent_children' 	=> $this->input->post('dependent_children'),
	            'contact_no' 			=> $this->input->post('contact_no'),
	            'place_of_birth' 		=> $this->input->post('place_of_birth'),
	            'street_address' 		=> $this->input->post('street_name'),
	            'city_id' 				=> $this->input->post('city_id'),
	            'tin_no' 				=> $this->input->post('tin_no'),
	            'pag_ibig_no' 			=> $this->input->post('pag_ibig_no'),
	            'philhealth_no' 		=> $this->input->post('philhealth_no'),
	            'sss_no' 				=> $this->input->post('sss_no'),
	            'user_status' 			=> $this->input->post("user_status"),
	            'delete_status' 		=> 0,
			];

			if ($user_id == null) {
				$data += [
					"created_by" => admin_id()
				];

				if ($this->check_employee_id($employee_id) == 0) {
					if ($this->check_fullname($firstname, $middlename, $lastname, $name_ext) == 0) {
						if ($this->check_email($email) == 0) {
							if ($password == $confirm_password) {
								$data += [
						            "password" => $this->bcrypt->hash_password($this->input->post('password')),
								];

								if(!empty($_FILES["user_picture"]["name"])) {
							    	$allowed_types = ["image/jpeg", "image/jpg", "image/png"];
									if(in_array($_FILES["user_picture"]["type"], $allowed_types)) {
									   
										$input_name = "user_picture";
										$upload_path = "../ci_payroll/assets/img/upload/Users";
										$file_name = (!empty($_FILES[$input_name]["name"]) ? basename($_FILES[$input_name]["name"]) : "");
										$file_name = "user_picture_".date("YmdHis").".".pathinfo($file_name)["extension"];
										$path = do_upload($input_name, $upload_path, $file_name);
										$path = "assets/img/upload/Users/".$path;

										$data  += [
											"photo"	=> $path,
										];

										if ($this->user->insert($data) == true) {
											$user_id = $this->db->insert_id();

											$data_family_back = [
												"user_id" 				=> $user_id,
								                "fathers_name" 			=> $this->input->post('fathers_name'),
								                "fathers_occupation" 	=> $this->input->post('fathers_occupation'),
								                "fathers_birthdate" 	=> check_date($this->input->post('fathers_birthdate'), "Y-m-d"),
								                "mothers_name" 			=> $this->input->post('mothers_name'),
								                "mothers_occupation" 	=> $this->input->post('mothers_occupation'),
								                "mothers_birthdate" 	=> check_date($this->input->post('mothers_birthdate'), "Y-m-d")
											];
											$this->family_background->insert($data_family_back);

											if ($this->input->post("civil_status") == "Married") {
												$data_spouse = [
													"user_id" 			=> $user_id,
							                        "spouse_name" 		=> $this->input->post('spouse_name'),
							                        "spouse_occupation" => $this->input->post('spouse_occupation'),
							                        "spouse_birthdate" 	=> check_date($this->input->post('spouse_birthdate'), "Y-m-d")
												];
												$this->spouse->insert($data_spouse);
											}

											if ($this->input->post("educ_opt") != null) {
												foreach ($this->input->post("educ_opt") as $key => $value) {
													$data_education = [
														"user_id"			=> $user_id,
														"school_level" 		=> $value,
								                        "name_of_school" 	=> $this->input->post('name_of_school')[$key],
								                        "degree" 			=> "WALA",
								                        "date_attended" 	=> check_date($this->input->post('year_attended')[$key], "Y-m-d"),
								                        "date_graduated" 	=> check_date($this->input->post('year_graduated')[$key], "Y-m-d")
													];
													$this->educational_background->insert($data_education);
												}
											}

											if ($this->input->post("position") != null) {
												foreach ($this->input->post("position") as $key => $value) {
													$data_work_exp = [
														"user_id"			=> $user_id,
														"position" 			=> $value,
								                        "name_of_company" 	=> $this->input->post('company_name')[$key],
								                        "date_attended" 	=> check_date($this->input->post('work_year_start')[$key], "Y-m-d"),                
								                        "date_ended" 		=> check_date($this->input->post('work_year_end')[$key], "Y-m-d")
													];
													$this->work_experience->insert($data_work_exp);
												}
											}

											$ret = [
												"success" 	=> true,
												"msg"		=> "Inserted"
											];
										} else {
											$ret = [
												"success" 	=> false,
												"msg"		=> "Something went wrong with the server"
											];
										}
									} else {
										$ret = [
											"success" 	=> false,
											"msg"		=> "Invalid file type"
										];
									}
								} else {
									$ret = [
										"success" 	=> false,
										"msg"		=> "Picture is empty, please upload!"
									];
								}
							} else {
								$ret = [
									"success" 	=> false,
									"msg"		=> "Password did not match"
								];
							}
						} else {
							$ret = [
								"success" 	=> false,
								"msg"		=> "Email \"$email\" already taken"
							];
						}
					} else {
						$ret = [
							"success" 	=> false,
							"msg"		=> "Fullname \"$firstname\" \"$middlename\" \"$lastname\" already taken"
						];
					}
				} else {
					$ret = [
						"success" 	=> false,
						"msg"		=> "User ID \"$user_id\" already taken"
					];
				}	
			} else {
				$data += [
					"modified_by" => admin_id()
				];

				if ($password !== "" && $confirm_password !== "") {
					$data += [
			            "password" => $this->bcrypt->hash_password($this->input->post('password')),
					];
				}

				if ($this->get_cur_employee_id($user_id) == $employee_id) {
					if ($this->get_cur_fullname($user_id) == $firstname.$middlename.$lastname.$name_ext) {
						if ($this->get_cur_email($user_id) == $email) {
							if(!empty($_FILES["user_picture"]["name"])) {
								$allowed_types = ["image/jpeg", "image/jpg", "image/png"];
								if(in_array($_FILES["user_picture"]["type"], $allowed_types)) {
								   
									$input_name = "user_picture";
									$upload_path = "../ci_payroll/assets/img/upload/Users";
									$file_name = (!empty($_FILES[$input_name]["name"]) ? basename($_FILES[$input_name]["name"]) : "");
									$file_name = "user_picture_".date("YmdHis").".".pathinfo($file_name)["extension"];
									$path = do_upload($input_name, $upload_path, $file_name);
									$path = "assets/img/upload/Users/".$path;

									$data  += [
										"photo" => $path,
									];

									$ret = [
										"success" 	=> true,
										"msg"		=> "Success"
									];
								} else {
									$ret = [
										"success" 	=> false,
										"msg"		=> "Invalid file type"
									];
								}
							}

							$this->user->update($data, ["user_id" => $user_id]);

							$data_family_back = [
								"user_id" 				=> $user_id,
				                "fathers_name" 			=> $this->input->post('fathers_name'),
				                "fathers_occupation" 	=> $this->input->post('fathers_occupation'),
				                "fathers_birthdate" 	=> check_date($this->input->post('fathers_birthdate'), "Y-m-d"),
				                "mothers_name" 			=> $this->input->post('mothers_name'),
				                "mothers_occupation" 	=> $this->input->post('mothers_occupation'),
				                "mothers_birthdate" 	=> check_date($this->input->post('mothers_birthdate'), "Y-m-d")
							];

							if ($family_background_id != null) {
								$this->family_background->update($data_family_back, ["family_background_id" => $family_background_id]);
							} else {
								$this->family_background->insert($data_family_back);
							}

							if ($this->input->post("civil_status") == "Married") {
								$data_spouse = [
									"user_id" 			=> $user_id,
			                        "spouse_name" 		=> $this->input->post('spouse_name'),
			                        "spouse_occupation" => $this->input->post('spouse_occupation'),
			                        "spouse_birthdate" 	=> check_date($this->input->post('spouse_birthdate'), "Y-m-d")
								];

								if ($spouse_id != null) {
									$this->spouse->update($data_spouse, ["spouse_id" => $spouse_id]);
								} else {
									$this->spouse->insert($data_spouse);
								}
							}

							if ($this->input->post("educ_opt") != null) {
								foreach ($this->input->post("educ_opt") as $key => $value) {
									$data_education = [
										"user_id"			=> $user_id,
										"school_level" 		=> $value,
				                        "name_of_school" 	=> $this->input->post('name_of_school')[$key],
				                        "degree" 			=> "WALA",
				                        "date_attended" 	=> check_date($this->input->post('year_attended')[$key], "Y-m-d"),
				                        "date_graduated" 	=> check_date($this->input->post('year_graduated')[$key], "Y-m-d")
									];

									if ($this->input->post("educational_background_id")[$key] != null) {
										$this->educational_background->update($data_education, ["educational_background_id" => $this->input->post("educational_background_id")[$key]]);
									} else {
										$this->educational_background->insert($data_education);
									}
								}
							}

							if ($this->input->post("position") != null) {
								foreach ($this->input->post("position") as $key => $value) {
									$data_work_exp = [
										"user_id"			=> $user_id,
										"position" 			=> $value,
				                        "name_of_company" 	=> $this->input->post('company_name')[$key],
				                        "date_attended" 	=> check_date($this->input->post('work_year_start')[$key], "Y-m-d"),                
				                        "date_ended" 		=> check_date($this->input->post('work_year_end')[$key], "Y-m-d")
									];

									if ($this->input->post("work_exp_id")[$key] != null) {
										$this->work_experience->update($data_work_exp, ["work_exp_id" => $this->input->post("work_exp_id")[$key]]);			
									} else {
										$this->work_experience->insert($data_work_exp);
									}
								}
							}

							$ret = [
								"success" 	=> true,
								"msg"		=> "Updated"
							];
						} else {
							if ($this->check_email($email) == 0) {
								if(!empty($_FILES["user_picture"]["name"])) {
									$allowed_types = ["image/jpeg", "image/jpg", "image/png"];
									if(in_array($_FILES["user_picture"]["type"], $allowed_types)) {
									   
										$input_name = "user_picture";
										$upload_path = "../ci_payroll/assets/img/upload/Users";
										$file_name = (!empty($_FILES[$input_name]["name"]) ? basename($_FILES[$input_name]["name"]) : "");
										$file_name = "user_picture_".date("YmdHis").".".pathinfo($file_name)["extension"];
										$path = do_upload($input_name, $upload_path, $file_name);
										$path = "assets/img/upload/Users/".$path;

										$data  += [
											"photo" => $path,
										];

										$ret = [
											"success" 	=> true,
											"msg"		=> "Success"
										];
									} else {
										$ret = [
											"success" 	=> false,
											"msg"		=> "Invalid file type"
										];
									}
								}

								$this->user->update($data, ["user_id" => $user_id]);

								$data_family_back = [
									"user_id" 				=> $user_id,
					                "fathers_name" 			=> $this->input->post('fathers_name'),
					                "fathers_occupation" 	=> $this->input->post('fathers_occupation'),
					                "fathers_birthdate" 	=> check_date($this->input->post('fathers_birthdate'), "Y-m-d"),
					                "mothers_name" 			=> $this->input->post('mothers_name'),
					                "mothers_occupation" 	=> $this->input->post('mothers_occupation'),
					                "mothers_birthdate" 	=> check_date($this->input->post('mothers_birthdate'), "Y-m-d")
								];

								if ($family_background_id != null) {
									$this->family_background->update($data_family_back, ["family_background_id" => $family_background_id]);
								} else {
									$this->family_background->insert($data_family_back);
								}

								if ($this->input->post("civil_status") == "Married") {
									$data_spouse = [
										"user_id" 			=> $user_id,
				                        "spouse_name" 		=> $this->input->post('spouse_name'),
				                        "spouse_occupation" => $this->input->post('spouse_occupation'),
				                        "spouse_birthdate" 	=> check_date($this->input->post('spouse_birthdate'), "Y-m-d")
									];

									if ($spouse_id != null) {
										$this->spouse->update($data_spouse, ["spouse_id" => $spouse_id]);
									} else {
										$this->spouse->insert($data_spouse);
									}
								}

								if ($this->input->post("educ_opt") != null) {
									foreach ($this->input->post("educ_opt") as $key => $value) {
										$data_education = [
											"user_id"			=> $user_id,
											"school_level" 		=> $value,
					                        "name_of_school" 	=> $this->input->post('name_of_school')[$key],
					                        "degree" 			=> "WALA",                
					                        "date_attended" 	=> check_date($this->input->post('year_attended')[$key], "Y-m-d"),
					                        "date_graduated" 	=> check_date($this->input->post('year_graduated')[$key], "Y-m-d")
										];

										if ($this->input->post("educational_background_id")[$key] != null) {
											$this->educational_background->update($data_education, ["educational_background_id" => $this->input->post("educational_background_id")[$key]]);
										} else {
											$this->educational_background->insert($data_education);
										}
									}
								}

								if ($this->input->post("position") != null) {
									foreach ($this->input->post("position") as $key => $value) {
										$data_work_exp = [
											"user_id"			=> $user_id,
											"position" 			=> $value,
					                        "name_of_company" 	=> $this->input->post('company_name')[$key],
					                        "date_attended" 	=> check_date($this->input->post('work_year_start')[$key], "Y-m-d"),                
					                        "date_ended" 		=> check_date($this->input->post('work_year_end')[$key], "Y-m-d")
										];

										if ($this->input->post("work_exp_id")[$key] != null) {
											$this->work_experience->update($data_work_exp, ["work_exp_id" => $this->input->post("work_exp_id")[$key]]);			
										} else {
											$this->work_experience->insert($data_work_exp);
										}
									}
								}

								$ret = [
									"success" 	=> true,
									"msg"		=> "Updated"
								];
							} else {
								$ret = [
									"success" 	=> false,
									"msg"		=> "Email \"$email\" already taken"
								];
							}
						}
					} else {
						if ($this->check_fullname($firstname, $middlename, $lastname, $name_ext) == 0) {
							if(!empty($_FILES["user_picture"]["name"])) {
								$allowed_types = ["image/jpeg", "image/jpg", "image/png"];
								if(in_array($_FILES["user_picture"]["type"], $allowed_types)) {
								   
									$input_name = "user_picture";
									$upload_path = "../ci_payroll/assets/img/upload/Users";
									$file_name = (!empty($_FILES[$input_name]["name"]) ? basename($_FILES[$input_name]["name"]) : "");
									$file_name = "user_picture_".date("YmdHis").".".pathinfo($file_name)["extension"];
									$path = do_upload($input_name, $upload_path, $file_name);
									$path = "assets/img/upload/Users/".$path;

									$data  += [
										"photo" => $path,
									];

									$ret = [
										"success" 	=> true,
										"msg"		=> "Success"
									];
								} else {
									$ret = [
										"success" 	=> false,
										"msg"		=> "Invalid file type"
									];
								}
							}

							$this->user->update($data, ["user_id" => $user_id]);

							$data_family_back = [
								"user_id" 				=> $user_id,
				                "fathers_name" 			=> $this->input->post('fathers_name'),
				                "fathers_occupation" 	=> $this->input->post('fathers_occupation'),
				                "fathers_birthdate" 	=> check_date($this->input->post('fathers_birthdate'), "Y-m-d"),
				                "mothers_name" 			=> $this->input->post('mothers_name'),
				                "mothers_occupation" 	=> $this->input->post('mothers_occupation'),
				                "mothers_birthdate" 	=> check_date($this->input->post('mothers_birthdate'), "Y-m-d")
							];

							if ($family_background_id != null) {
								$this->family_background->update($data_family_back, ["family_background_id" => $family_background_id]);
							} else {
								$this->family_background->insert($data_family_back);
							}

							if ($this->input->post("civil_status") == "Married") {
								$data_spouse = [
									"user_id" 			=> $user_id,
			                        "spouse_name" 		=> $this->input->post('spouse_name'),
			                        "spouse_occupation" => $this->input->post('spouse_occupation'),
			                        "spouse_birthdate" 	=> check_date($this->input->post('spouse_birthdate'), "Y-m-d")
								];

								if ($spouse_id != null) {
									$this->spouse->update($data_spouse, ["spouse_id" => $spouse_id]);
								} else {
									$this->spouse->insert($data_spouse);
								}
							}

							if ($this->input->post("educ_opt") != null) {
								foreach ($this->input->post("educ_opt") as $key => $value) {
									$data_education = [
										"user_id"			=> $user_id,
										"school_level" 		=> $value,
				                        "name_of_school" 	=> $this->input->post('name_of_school')[$key],
				                        "degree" 			=> "WALA",                
				                        "date_attended" 	=> check_date($this->input->post('year_attended')[$key], "Y-m-d"),
				                        "date_graduated" 	=> check_date($this->input->post('year_graduated')[$key], "Y-m-d")
									];

									if ($this->input->post("educational_background_id")[$key] != null) {
										$this->educational_background->update($data_education, ["educational_background_id" => $this->input->post("educational_background_id")[$key]]);
									} else {
										$this->educational_background->insert($data_education);
									}
								}
							}

							if ($this->input->post("position") != null) {
								foreach ($this->input->post("position") as $key => $value) {
									$data_work_exp = [
										"user_id"			=> $user_id,
										"position" 			=> $value,
				                        "name_of_company" 	=> $this->input->post('company_name')[$key],
				                        "date_attended" 	=> check_date($this->input->post('work_year_start')[$key], "Y-m-d"),                
				                        "date_ended" 		=> check_date($this->input->post('work_year_end')[$key], "Y-m-d")
									];

									if ($this->input->post("work_exp_id")[$key] != null) {
										$this->work_experience->update($data_work_exp, ["work_exp_id" => $this->input->post("work_exp_id")[$key]]);			
									} else {
										$this->work_experience->insert($data_work_exp);
									}
								}
							}

							$ret = [
								"success" 	=> true,
								"msg"		=> "Updated"
							];
						} else {
							$ret = [
								"success" 	=> false,
								"msg"		=> "Fullname \"$firstname\" \"$middlename\" \"$lastname\" already taken"
							];
						}
					}
				} else {
					if ($this->check_employee_id($employee_id) == 0) {
						if(!empty($_FILES["user_picture"]["name"])) {
							$allowed_types = ["image/jpeg", "image/jpg", "image/png"];
							if(in_array($_FILES["user_picture"]["type"], $allowed_types)) {
							   
								$input_name = "user_picture";
								$upload_path = "../ci_payroll/assets/img/upload/Users";
								$file_name = (!empty($_FILES[$input_name]["name"]) ? basename($_FILES[$input_name]["name"]) : "");
								$file_name = "user_picture_".date("YmdHis").".".pathinfo($file_name)["extension"];
								$path = do_upload($input_name, $upload_path, $file_name);
								$path = "assets/img/upload/Users/".$path;

								$data  += [
									"photo" => $path,
								];

								$ret = [
									"success" 	=> true,
									"msg"		=> "Success"
								];
							} else {
								$ret = [
									"success" 	=> false,
									"msg"		=> "Invalid file type"
								];
							}
						}

						$this->user->update($data, ["user_id" => $user_id]);

						$data_family_back = [
							"user_id" 				=> $user_id,
			                "fathers_name" 			=> $this->input->post('fathers_name'),
			                "fathers_occupation" 	=> $this->input->post('fathers_occupation'),
			                "fathers_birthdate" 	=> check_date($this->input->post('fathers_birthdate'), "Y-m-d"),
			                "mothers_name" 			=> $this->input->post('mothers_name'),
			                "mothers_occupation" 	=> $this->input->post('mothers_occupation'),
			                "mothers_birthdate" 	=> check_date($this->input->post('mothers_birthdate'), "Y-m-d")
						];

						if ($family_background_id != null) {
							$this->family_background->update($data_family_back, ["family_background_id" => $family_background_id]);
						} else {
							$this->family_background->insert($data_family_back);
						}

						if ($this->input->post("civil_status") == "Married") {
							$data_spouse = [
								"user_id" 			=> $user_id,
		                        "spouse_name" 		=> $this->input->post('spouse_name'),
		                        "spouse_occupation" => $this->input->post('spouse_occu'),
		                        "spouse_birthdate" 	=> check_date($this->input->post('spouse_birthdate'), "Y-m-d")
							];

							if ($spouse_id != null) {
								$this->spouse->update($data_spouse, ["spouse_id" => $spouse_id]);
							} else {
								$this->spouse->insert($data_spouse);
							}
						}

						if ($this->input->post("educ_opt") != null) {
							foreach ($this->input->post("educ_opt") as $key => $value) {
								$data_education = [
									"user_id"			=> $user_id,
									"school_level" 		=> $value,
			                        "name_of_school" 	=> $this->input->post('name_of_school')[$key],
			                        "degree" 			=> "WALA",                
			                        "date_attended" 	=> check_date($this->input->post('year_attended')[$key], "Y-m-d"),
			                        "date_graduated" 	=> check_date($this->input->post('year_graduated')[$key], "Y-m-d")
								];

								if ($this->input->post("educational_background_id")[$key] != null) {
									$this->educational_background->update($data_education, ["educational_background_id" => $this->input->post("educational_background_id")[$key]]);
								} else {
									$this->educational_background->insert($data_education);
								}
							}
						}

						if ($this->input->post("position") != null) {
							foreach ($this->input->post("position") as $key => $value) {
								$data_work_exp = [
									"user_id"			=> $user_id,
									"position" 			=> $value,
			                        "name_of_company" 	=> $this->input->post('company_name')[$key],
			                        "date_attended" 	=> check_date($this->input->post('work_year_start')[$key], "Y-m-d"),                
			                        "date_ended" 		=> check_date($this->input->post('work_year_end')[$key], "Y-m-d")
								];

								if ($this->input->post("work_exp_id")[$key] != null) {
									$this->work_experience->update($data_work_exp, ["work_exp_id" => $this->input->post("work_exp_id")[$key]]);			
								} else {
									$this->work_experience->insert($data_work_exp);
								}
							}
						}

						$ret = [
							"success" 	=> true,
							"msg"		=> "Updated"
						];
					} else {
						$ret = [
							"success" 	=> false,
							"msg"		=> "User ID \"$user_id\" already taken"
						];
					}
				}
			}
			
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
			} else {
			    $this->db->trans_commit();
			}
			
			echo json_encode($ret);
		} else {
			page_not_found();
		}
	}

	function check_employee_id($employee_id) {
		if (admin_login()) {
			$data = 0;
			
			$select = "COUNT(*) AS count";
			$where = [
				"employee_id" 	=> $employee_id
			];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->user->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->count;
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

	function check_fullname($firstname, $middlename, $lastname, $name_ext) {
		if (admin_login()) {
			$data = 0;
			
			$select = "COUNT(*) AS count";
			$where = [
				"firstname" 	=> $firstname, 
				"middlename"	=> $middlename, 
				"lastname" 		=> $lastname,
				"name_ext" 		=> $name_ext
			];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->user->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->count;
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

	function check_email($email) {
		if (admin_login()) {
			$data = 0;
			
			$select = "COUNT(*) AS count";
			$where = [
				"email" => $email
			];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->user->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->count;
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

	function get_cur_employee_id($user_id) {
		if (admin_login()) {
			$data = "";
			
			$select = "employee_id";
			$where = [
				"user_id" => $user_id
			];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->user->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->employee_id;
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

	function get_cur_fullname($user_id) {
		$fullname = "";

		$select = "firstname, middlename, lastname, name_ext";
		$where = ["user_id" => $user_id];

		foreach ($this->user->select($select, $where) as $key => $value) {
			$fullname = $value->firstname.$value->middlename.$value->lastname.$value->name_ext;
			break;
		}

		return $fullname;
	}

	function get_cur_email($user_id) {
		if (admin_login()) {
			$data = "";
			
			$select = "email";
			$where = [
				"user_id" => $user_id
			];
			$join = [];
			$order_by = [];
			$limit = [];
			$group_by = "";
			
			foreach ($this->user->select($select, $where, $join, $order_by, $limit, $group_by) as $key => $value) {
				$data = $value->email;
			}
			
			return $data;
		} else {
			page_not_found();
		}
	}

}

/* End of file Employee.php */
/* Location: ./application/modules/admin_employee/controllers/Employee.php */