<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="col-sm-12"> 

<div class="row">
    <div class="col-sm-4">
        <form role="form" name="frm_calc" id="frm_salary_calculate" onsubmit="event.preventDefault(); calculate();">
            <div class="form-group">
                <label>Category</label>
                    <select name="salary_status" id="status_value" class="form-control" required>
                        <option value="">Select</option>
                        <option value="0">Per Day</option>
                        <option value="1">Fixed</option>
                    </select>
                 <span class="help-block"></span>
            </div>            
            <div class="form-group">
                <label>Date Start</label>
                    <div class="form-group sys_datepicker">                    
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="date_start" id="date_start" class="form-control input-sm" value="<?= !empty($employee_info) ? html_escape($employee_info["date_hired"]) : date("m/d/Y"); ?>" required>
                        </div>
                    </div>
                 <span class="help-block"></span>
            </div>
            <div class="form-group">
                <label>Date End</label>
                    <div class="form-group sys_datepicker">                    
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="date_end" id="date_end" class="form-control input-sm" value="<?= !empty($employee_info) ? html_escape($employee_info["date_hired"]) : date("m/d/Y"); ?>" required>
                        </div>
                    </div>
                 <span class="help-block"></span>
            </div>                        
            <button class="btn btn-primary" type="submit">Calculate payroll</button>
        </form>                
    </div>
    <!-- <div class="col-sm-8">                            
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Striped Table </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>                       
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div> -->
</div>

<div class="row" style="margin-top: 3%;">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" id="table_salary" style="width: 100%;">            
                <thead class="header-th">
                    <tr>
                        <th class="header-th">Employee ID</th>
                        <th class="header-th">Name</th> 
                        <th class="header-th">Salary</th>                         
                        <th class="header-th">Action</th>                   
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>


<div class="modal fade" id="modal_details" role="dialog">
    <div class="modal-dialog"> 
        <div class="modal-content">
            <div class="modal-header  btn-success">
                             <button type="button" class="close" data-dismiss="modal">&times;</button>                
                             <h3 class="modal-title"></h3>                    
            </div><!-- modal-header -->
                        <div class="modal-body">  
                            <div class="pay_slip">
                                <div class="header_payslip">                                    
                                    <p>Date Covered : <label id="date_covered"></label></p>
                                    <p>Employee ID : <label id="emp_id"></label></p>
                                    <p>Employee Name : <label id="name"></label></p>
                                </div>

                                <div class="body_payslip">
                                    <table class="table table-bordered">                               
                                        <tbody>
                                         <tr>
                                            <td>No. of duty</td>
                                            
                                            <td>&#8369;<label id="amount_duty">5000</label></td>
                                            <td><label id="duty_days">5</label> (day/s)</td>
                                        </tr>
                                        <tr>
                                            <td>Duty rest Premium pay</td>
                                            <td>&#8369;<label id="amount_premium">5000</label></td>
                                        </tr>
                                        <tr>
                                            <td>Cola Duration</td>
                                            
                                            <td>&#8369;<label id="amount_cola">5000</label></td>
                                            <td><label id="cola_duration">5</label> (min/s)</td>
                                        </tr>
                                        <tr>
                                            <td>Overtime Duration</td>
                                            
                                            <td>&#8369;<label id="amount_ot">5000</label></td>
                                            <td><label id="ot_mins">5</label> (min/s)</td>
                                        </tr>
                                        <tr>
                                            <td>Late Duration</td>
                                            
                                            <td>&#8369;-<label id="amount_late">5000</label></td>
                                            <td><label id="late_mins">5</label> (min/s)</td>
                                        </tr>
                                        <tr>
                                            <td>Undertime Duration</td>
                                            
                                            <td>&#8369;-<label id="amount_under">5000</label></td>
                                            <td><label id="under_mins">5</label> (min/s)</td>
                                        </tr>

                                        <tr>
                                            <td><b>Total</b></td>
                                            <td colspan="2">&#8369;<label id="total">5000</label></td>                                    
                                        </tr>
                                        </tbody>
                                    </table>                                    
                                </div>

                                <div class="footer_payslip">

                                </div>
                            </div>
                        </div><!-- modal-body -->                        
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modaleditpatient -->

<div class="modal fade" id="modal_details_fixed" role="dialog">
    <div class="modal-dialog"> 
        <div class="modal-content">
            <div class="modal-header  btn-success">
                             <button type="button" class="close" data-dismiss="modal">&times;</button>                
                             <h3 class="modal-title"></h3>                    
            </div><!-- modal-header -->
                        <div class="modal-body">  
                            <div class="pay_slip">
                                <div class="header_payslip">                                    
                                    <p>Date Covered : <label id="date_covered_fixed"></label></p>
                                    <p>Employee ID : <label id="emp_id_fixed"></label></p>
                                    <p>Employee Name : <label id="name_fixed"></label></p>
                                </div>

                                <div class="body_payslip">
                                    <table class="table table-bordered">                               
                                        <tbody>
                                         <tr>
                                            <td>No. of duty</td>                                            
                                            <td>&#8369;<label id="amount_duty_fixed"></label></td>
                                            <td><label id="duty_days_fixed"></label> (day/s)</td>
                                        </tr>                                       
                                        </tbody>
                                    </table>                                    
                                </div>

                                <div class="footer_payslip">

                                </div>
                            </div>
                        </div><!-- modal-body -->                        
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modaleditpatient -->

<script type="text/javascript">
var table;

function view_details(emp_id,start,end,name,salary_stat) {
    var date_covered = start + " - " +end;    
    
    if(salary_stat == 0) {
        $('#modal_details').modal('show'); 
        $('.modal-title').text('Details');

        document.getElementById('date_covered').innerHTML = date_covered;
        document.getElementById('emp_id').innerHTML = emp_id;
        document.getElementById('name').innerHTML = name;

        $.ajax({
            url : base_url+"admin_payroll/admin_calculate_payroll/get_details_calculate",
            type: "GET",
            data: {employee_id:emp_id, start_date:start, end_date:end},
            dataType: "json",
            success:function(data) {
                document.getElementById('amount_duty').innerHTML = ((parseFloat(data[0].total_salary)) - (parseFloat(data[0].premium_pay) + parseFloat(data[0].cola_rate))) ;
                document.getElementById('duty_days').innerHTML = data[0].number_duty;
                document.getElementById('amount_premium').innerHTML = data[0].premium_pay;
                document.getElementById('amount_cola').innerHTML = data[0].cola_rate;
                document.getElementById('cola_duration').innerHTML = data[0].cola_duration;
                document.getElementById('amount_ot').innerHTML = data[0].amount_paid;
                document.getElementById('ot_mins').innerHTML = data[0].overtime_duration;
                document.getElementById('amount_late').innerHTML = data[0].late_deduction;
                document.getElementById('late_mins').innerHTML = data[0].late_duration;  
                document.getElementById('amount_under').innerHTML = data[0].undertime_deduction;
                document.getElementById('under_mins').innerHTML = data[0].undertime_duration;            
                document.getElementById('total').innerHTML = (parseFloat(data[0].total_salary)+parseFloat(data[0].amount_paid));  
            }
        });    
    }else if(salary_stat == 1) {
        $('#modal_details_fixed').modal('show'); 
        $('.modal-title').text('Details');

        document.getElementById('date_covered_fixed').innerHTML = date_covered;
        document.getElementById('emp_id_fixed').innerHTML = emp_id;
        document.getElementById('name_fixed').innerHTML = name;

        $.ajax({
            url : base_url+"admin_payroll/admin_calculate_payroll/get_details_calculate_fixed",
            type: "GET",
            data: {employee_id:emp_id, start_date:start, end_date:end},
            dataType: "json",
            success:function(data) {
                document.getElementById('amount_duty_fixed').innerHTML = data[0].amount;
                document.getElementById('duty_days_fixed').innerHTML = data[0].no_days;
            }
        });
    }
    
}

function calculate() {                      
    var calc_again = 0;  
    let start_date = $('[name="date_start"]').val();
    let start_newdate = start_date.split("/").reverse().join("-"); 
    let end_date = $('[name="date_end"]').val(); 
    let end_newdate = end_date.split("/").reverse().join("-");
    let status = $('[name="salary_status"]').val(); 

    let check_data = check_calculate_if_exist();    
    
    if(check_data == 0) {
        $("#table_salary").find('tbody').empty();
        $("#table_salary").find('tbody').append('<tr><td colspan="4"><center><b>No data available</b></center></td></tr>');
    }else {   
        let check_calc = check_if_already_calculated();         
        if(check_calc == -1) {                        
            view_data_table(check_calc);
        }else {
            if (confirm('The data that you want to calculate is already available, Are you sure you want to calculate again? ')) {
                view_data_table(check_calc);
            } else {
                view_data_table_calculated();
            }
        }
    }   
}

function check_calculate_if_exist() {
    let result = 0;
        $.ajax({
            url : base_url+"admin_payroll/admin_calculate_payroll/check_data",
            type: "GET",
            data: $('#frm_salary_calculate').serialize(),
            dataType: "json",
            async: false,                
            success: function(response){                              
                if(response.result == false) {
                    result = 0;
                }else {
                    result = 1;
                }
            }
        });
        return result;
}


function check_if_already_calculated() {    
    let check = 0;
        $.ajax({
                url : base_url+"admin_payroll/admin_calculate_payroll/check_calculate",
                type: "GET",
                data: $('#frm_salary_calculate').serialize(),
                dataType: "json",
                async: false,                
                success: function(response){              
                    if(response.result == null) {
                         check = -1;
                    }else {
                         check = response.result;
                    }   
                }
        });
        return check;
}

function view_data_table(calc_again) {
    $("#table_salary").dataTable().fnDestroy();
        table =  $('#table_salary').DataTable({ 
          "ajax": {
                  "url": base_url+"admin_payroll/admin_calculate_payroll/compute_payroll/"+calc_again,
                  "data": function(d) {
                    let frm_data = $('#frm_salary_calculate').serializeArray();
                    $.each(frm_data, function(key,val) {
                        d[val.name] = val.value;
                    });
                   },
                  "type": "POST",
              },
              responsive: true,
              'bInfo': false
    });    
}

function view_data_table_calculated() {
    $("#table_salary").dataTable().fnDestroy();
        table =  $('#table_salary').DataTable({ 
          "ajax": {
                  "url": base_url+"admin_payroll/admin_calculate_payroll/show_already_calculated/",
                  "data": function(d) {
                    let frm_data = $('#frm_salary_calculate').serializeArray();
                    $.each(frm_data, function(key,val) {
                        d[val.name] = val.value;
                    });
                   },
                  "type": "POST",
              },
              responsive: true,
              'bInfo': false
    });      
}


// $(document).ready(function() {                 

//         /* initialize the calendar
//          -----------------------------------------------------------------*/
//         var date = new Date();
//         var d = date.getDate();
//         var m = date.getMonth();
//         var y = date.getFullYear();

//         $('#calendar').fullCalendar({
//             header: {
//                 left: 'prev,next today',
//                 center: 'title',
//                 right: 'month'
//             },
//             editable: false,
//             droppable: true, // this allows things to be dropped onto the calendar                       
//             // eventSources:[
//             //     {
//             //         events: function(start,end,timezone,callback) {
//             //             $('.fc-event').remove();
//             //             $.ajax({
//             //                 url: base_url+'admin_payroll/admin_calculate_payroll/getAllCalculated',
//             //                 dataType: 'JSON',
//             //                 data: {
//             //                     start:start.format("YYYY-MM-DD"),
//             //                     end:end.format("YYYY-MM-DD"),
//             //                 },
//             //                 success: function(msg) {                                
//             //                     let events = msg.events;
//             //                     callback(events);
//             //                 }
//             //             });
//             //         }
//             //     }
//             // ]
//             events: [                
//                 {
//                     title: 'Per Day',
//                     start: new Date(y, m, d-5),
//                     end: new Date(y, m, d-2)
//                 }, 
//                 {
//                     title: 'Fixed',
//                     start: new Date(y, m, d-5),
//                     end: new Date(y, m, d-2)
//                 },                
//             ]
//         });


// });


</script>

<!-- 

     1. check kung sa tbl_attendance naay data nga inside sa date range
     2. check kung na calculate na ba daan ang date range
        if true (meaning na calculate na)
            - Calculate again / Get ang calculate history        
        if false (calculate dritso) 

-->
