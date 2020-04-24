<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] 		= 'user_portal/Portal';
$route['404_override'] 				= 'common/Page_error';
$route['translate_uri_dashes'] 		= FALSE;

// admin
$route['admin'] 					= 'admin_portal/Portal';
$route['admin_request_login'] 		= 'admin_portal/Portal/request_login';
$route['admin_request_logout'] 		= 'request_logout/Request_logout/admin_request_logout';

$route['admin/dashboard'] 				= 'admin_dashboard/Dashboard';

$route['admin/employee'] 				= 'admin_employee/Employee';
$route['admin/employee/form'] 			= 'admin_employee/Employee/form';
$route['admin/employee/form/(:any)'] 	= 'admin_employee/Employee/form/$1';

$route['admin/calendar']				= 'admin_calendar/Calendar';

$route['admin/additionals']				= 'admin_additional/Additional';
$route['admin/additionals-tagging']		= 'admin_additional/Additional_tagging';

$route['admin/deductions']				= 'admin_deduction/Deduction';
$route['admin/deductions-tagging']		= 'admin_deduction/Deduction_tagging';

$route['admin/structure-dept']			= 'admin_company_structure/Department';
$route['admin/structure-position']		= 'admin_company_structure/Position';
$route['admin/structure-salary']		= 'admin_company_structure/Salary';

$route['admin/leave-create']			= 'admin_leave/Leave_Create';
$route['admin/leave-apply']				= 'admin_leave/Leave_Apply';

$route['admin/user_permission'] 		= 'admin_permission/User_permission';
$route['admin/admin_permission'] 		= 'admin_permission/Admin_permission';

$route['admin/dtr'] 					= 'admin_payroll/Admin_check_dtr';
$route['admin/time_attendance'] 		= 'admin_payroll/Admin_time_attendance';
$route['admin/calculate_payroll'] 		= 'admin_payroll/Admin_calculate_payroll';
$route['admin/view_payroll'] 			= 'admin_payroll/Admin_view_payroll';
$route['admin/history_payroll'] 		= 'admin_payroll/Admin_view_payroll_history';
$route['admin/file_upload'] 		= 'admin_payroll/Admin_file_upload';


// end admin

// user
$route['user_request_login'] 				= 'user_portal/Portal/request_login';
$route['user_request_logout'] 				= 'request_logout/Request_logout/user_request_logout';

$route['user/dashboard'] 					= 'user_dashboard/Dashboard';

$route['user/schedule'] 					= 'user_schedule/Schedule';
$route['user/duty_rest'] 					= 'user_duty_rest/Duty_rest';
$route['user/overtime'] 					= 'user_overtime/Overtime';
// end user