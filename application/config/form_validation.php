<?php 

$config = array(
	'add_department' => array (
			array (
				'field'	=>	'dept_name',
				'label' =>	'Department name',				
				'rules' =>	'trim|required|is_unique[tbl_department.department_name]'
			),								
	),

	'add_position' => array (
			array (
				'field'	=>	'position_name',
				'label' =>	'Position name',				
				'rules' =>	'trim|required'
			),
			array (
				'field'	=>	'dept_name',
				'label' =>	'Department name',
				'rules' =>	'trim|required'
			),			
			array (
				'field'	=>	'sal_grade',
				'label' =>	'Salary Grade',
				'rules' =>	'trim|required'
			),

	),

	'add_salary' => array (
			array (
				'field'	=>	'salary_name',
				'label' =>	'Salary Title',				
				'rules' =>	'trim|required|is_unique[tbl_salary.salary_title]'
			),
			array (
				'field'	=>	'sal_stat',
				'label' =>	'Salary Status',
				'rules' =>	'trim|required'
			),			
			array (
				'field'	=>	'salary_amt',
				'label' =>	'Salary Amount',
				'rules' =>	'trim|required|numeric'
			),					
	),

	'add_deduction' => array (
			array (
				'field'	=>	'deduction_title',
				'label' =>	'Title',				
				'rules' =>	'trim|required|is_unique[tbl_deduction.deduction_title]'
			),
			array (
				'field'	=>	'amt_deduction',
				'label' =>	'Amount',
				'rules' =>	'trim|required|numeric'
			),						
	),

	'add_additional' => array (
			array (
				'field'	=>	'additional_title',
				'label' =>	'Title',				
				'rules' =>	'trim|required|is_unique[tbl_additional.additional_title]'
			),
			array (
				'field'	=>	'amt_additional',
				'label' =>	'Amount',
				'rules' =>	'trim|required|numeric'
			),						
	),

	'add_leave' => array (
			array (
				'field'	=>	'lea_title',
				'label' =>	'Title',				
				'rules' =>	'trim|required|is_unique[tbl_leave.leave_title]'
			),
			array (
				'field'	=>	'lea_status',
				'label' =>	'Leave Status',
				'rules' =>	'trim|required'
			),
			array (
				'field'	=>	'main_status',
				'label' =>	'Status',
				'rules' =>	'trim|required'
			),
			array (
				'field'	=>	'lea_duration',
				'label' =>	'Duration',
				'rules' =>	'trim|required|numeric'
			),						
	),

	'apply_leave' => array (
			array (
				'field'	=>	'employee_id',
				'label' =>	'Employee ID',				
				'rules' =>	'trim|required'
			),
			array (
				'field'	=>	'leave_title',
				'label' =>	'Leave Title',
				'rules' =>	'trim|required'
			),
			array (
				'field'	=>	'apply_duration',
				'label' =>	'Duration',
				'rules' =>	'trim|required|numeric'
			),
			array (
				'field'	=>	'leave_date_apply',
				'label' =>	'Leave Start',
				'rules' =>	'trim|required'
			),	
			array (
				'field'	=>	'leave_date_start',
				'label' =>	'Leave Start',
				'rules' =>	'trim|required'
			),	
			array (
				'field'	=>	'leave_date_end',
				'label' =>	'Leave End',
				'rules' =>	'trim|required'
			),						
	),

	'fetch_emp_id' => array (
			array (
				'field'	=>	'input_emp_id',
				'label' =>	'Employee ID',				
				'rules' =>	'trim|required'
			),
	),

	'deduction_tagging' => array (			
			array (
				'field'	=>	'deduction_title',
				'label' =>	'Deduction Title',				
				'rules' =>	'trim|required'
			),
			array (
				'field'	=>	'status_emp_deduction',
				'label' =>	'Status',				
				'rules' =>	'trim|required'
			),
	),

	'additional_tagging' => array (			
			array (
				'field'	=>	'additional_title',
				'label' =>	'Additional Title',				
				'rules' =>	'trim|required'
			),
			array (
				'field'	=>	'status_emp_additional',
				'label' =>	'Status',				
				'rules' =>	'trim|required'
			),
	),
	'check_dtr' => array (			
			array (
				'field'	=>	'record_date',
				'label' =>	'Record Date',				
				'rules' =>	'trim|required'
			),
			array (
				'field'	=>	'time_in',
				'label' =>	'Time',				
				'rules' =>	'trim|required'
			),
			array (
				'field'	=>	'record_stat',
				'label' =>	'Status',				
				'rules' =>	'trim|required'
			),
	),

);


?>