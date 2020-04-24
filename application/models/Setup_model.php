<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Setup_model extends CI_Model {

    function check_user_permission($module_name) {
      $this->load->model('user_permission_model', 'permission');
      $this->load->model('user_module_button_model', 'module_button');
      $data = [0 => [], 1 => []];
      foreach ($this->module_button->select("*", [
          "tbl_user_module.module_name"   => $module_name
        ], 
        ["tbl_user_module" => "tbl_user_module_button.user_module_id = tbl_user_module.user_module_id"]) as $key => $value) {

        $status = 0;
        foreach ($this->permission->select("status", [
            "user_id"             => user_id(),
            "user_mod_button_id"  => $value->user_mod_button_id
          ]) as $key2 => $value2) {
          $status = $value2->status;
        }

        $data[0][] = [
          "button_code"   => $value->button_code,
          "status"        => $status
        ];
      }
      
      foreach ($this->module_button->select("*", [
            "tbl_user_module_button.button_code" => "view_page"
          ], 
          ["tbl_user_module"  => "tbl_user_module_button.user_module_id = tbl_user_module.user_module_id"]) as $key => $value) {

        $status = 0;
        foreach ($this->permission->select("status", [
            "user_id"     => user_id(),
            "user_mod_button_id" => $value->user_mod_button_id
          ]) as $key2 => $value2) {
          $status = $value2->status;
        }

        $data[1][] = [
          "module_name" => str_replace([" ", "(", ")", "."], "", $value->module_name),
          "status"      => $status
        ];
      }
      return json_encode($data);
    }

    function get_province_list() {
        $select = "province_id, province_name";

        $where = [
            "delete_status" => 0
        ];

        $query = $this->db->select($select)
                          ->from("tbl_province")
                          ->where($where)
                          ->get()
                          ->result();

        return $query;
    }

    function get_city_by_province_list($province_id = "") {
        $select = "city_id, city_name";

        $where = [
            "delete_status" => 0
        ];

        if (!empty($province_id)) {
            $where += [
                "province_id" => $province_id
            ];
        }

        $query = $this->db->select($select)
                          ->from("tbl_city")
                          ->where($where)
                          ->get()
                          ->result();

        return $query;
    }

    function get_religion_list() {
        $select = "religion_id, religion_name";

        $where = [
            "delete_status" => 0
        ];

        $query = $this->db->select($select)
                          ->from("tbl_religion")
                          ->where($where)
                          ->get()
                          ->result();

        return $query;
    }

    function get_citizenship_list() {
        $select = "citizenship_id, citizenship_name";

        $where = [
            "delete_status" => 0
        ];

        $query = $this->db->select($select)
                          ->from("tbl_citizenship")
                          ->where($where)
                          ->get()
                          ->result();

        return $query;
    }

    function get_department_list() {
        $select = "department_id, department_name";
        
        $where = [
            "delete_status" => 0
        ];

        $query = $this->db->select($select)
                          ->from("tbl_department")
                          ->where($where)
                          ->get()
                          ->result();

        return $query;
    }

    function get_deduction_list() {
        $select = "deduction_id, deduction_title";
        
        $where = [
            "delete_status" => 0,
            "deduction_status" => 1
        ];

        $query = $this->db->select($select)
                          ->from("tbl_deduction")
                          ->where($where)
                          ->get()
                          ->result();

        return $query;
    }

     function get_additional_list() {
        $select = "additional_id, additional_title";
        
        $where = [
            "delete_status" => 0,
            "additional_status" => 1
        ];

        $query = $this->db->select($select)
                          ->from("tbl_additional")
                          ->where($where)
                          ->get()
                          ->result();

        return $query;
    }

    function get_position_by_department_list($department_id = "") {
        $select = "position_id, position_title";

        $where = [
            "delete_status" => 0
        ];

        if (!empty($department_id)) {
            $where += [
                "department_id" => $department_id
            ];
        }

        $query = $this->db->select($select)
                          ->from("tbl_position")
                          ->where($where)
                          ->get()
                          ->result();

        return $query;
    }

    function get_user_select() {
        $data = [];
        
        $select = "user_id";
        
        $where = [
            "delete_status" => 0
        ];

        $query = $this->db->select($select)
                          ->from("tbl_user")
                          ->where($where)
                          ->get()
                          ->result();

        foreach ($query as $key => $value) {
            $data[] = [
                "id"    => $value->user_id,
                "text"  => $this->user_fullname($value->user_id)
            ];
        }

        return json_encode($data);
    }

    function get_user_by_department_select($department_id) {
        $data = [];
        
        $select = "user_id, employee_id";
        
        $where = [
            "department_id" => trim_str($department_id),
            "tbl_user.delete_status" => 0
        ];

        $query = $this->db->select($select)
                          ->from("tbl_user")
                          ->join('tbl_position', 'tbl_user.position_id = tbl_position.position_id', 'left')
                          ->where($where)
                          ->get()
                          ->result();

        foreach ($query as $key => $value) {
            $data[] = [
                "id"    => $value->user_id,
                "text"  => $this->user_fullname($value->user_id),
                "col1"  => $value->employee_id,
                "col2"  => $this->user_fullname($value->user_id)
            ];
        }

        return json_encode($data);
    }
   
    function user_fullname($user_id) {
        $fn = ""; $mn = ""; $ln = ""; $ext = "";

        $select = "firstname, middlename, lastname, name_ext";
        $where = ["user_id" => $user_id];

        $query = $this->db->select($select)
                          ->from("tbl_user")
                          ->where($where)
                          ->get()
                          ->result();

        foreach ($query as $key => $value) {
            $fn = $value->firstname;
            $mn = $value->middlename;
            $ln = $value->lastname;
            $ext = $value->name_ext;
        }

        if(!empty($mn)) {
            $mn = $mn[0].". ";
        }

        return ucfirst($ln).", ".ucfirst($fn)." ".ucfirst($mn).ucfirst($ext);
    }

    function get_salary_list() {
        $select = "salary_id, salary_title, amount, salary_status";

        $where = [
            "delete_status" => 0
        ];

        if (!empty($salary_id)) {
            $where += [
                "salary_id" => $salary_id
            ];
        }

        $query = $this->db->select($select)
                          ->from("tbl_salary")
                          ->where($where)
                          ->get()
                          ->result();

        return $query;       
    }

    function user_id($employee_id) {
        $select = "user_id";
        $where = ["employee_id" => $employee_id];

        $query = $this->db->select($select)
                          ->from("tbl_user")
                          ->where($where)
                          ->get()
                          ->result()[0]->user_id;

        return $query;
    }

    function user_employee_id($user_id) {
        $fn = ""; $mn = ""; $ln = ""; $ext = "";

        $select = "employee_id";
        $where = ["user_id" => $user_id];

        $query = $this->db->select($select)
                          ->from("tbl_user")
                          ->where($where)
                          ->get()
                          ->result()[0]->employee_id;

        return $query;
    }

    function get_rest_day_select() {
      $data = [
        [
          "id"    => "Sunday",
          "text"  => "Sunday"
        ],
        [
          "id"    => "Monday",
          "text"  => "Monday"
        ],
        [
          "id"    => "Tuesday",
          "text"  => "Tuesday"
        ],
        [
          "id"    => "Wednesday",
          "text"  => "Wednesday"
        ],
        [
          "id"    => "Thursday",
          "text"  => "Thursday"
        ],
        [
          "id"    => "Friday",
          "text"  => "Friday"
        ],
        [
          "id"    => "Saturday",
          "text"  => "Saturday"
        ]
      ];

      return json_encode($data);
    }
}