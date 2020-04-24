<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="col-sm-12"> 

<div class="row">
    <div class="col-sm-6">
        <form role="form" name="frm_calc" id="frm_view_salary" onsubmit="event.preventDefault(); calculate();">
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
                <label>Inclusive Dates</label>
                    <select name="date_inclusive" id="inclusive_value" class="form-control" required>
                        <option value="">Select</option>
                        <option value="0">1st Term (Date inclusive from 5 - 20)</option>
                        <option value="1">2nd Term (Date inclusive from 21 - 4)</option>
                    </select>
                 <span class="help-block"></span>
            </div> 
            <button class="btn btn-primary" type="submit">Show payroll</button>
        </form>                
    </div>
    <div class="col-sm-6">
        
    </div>                     
</div>

<div class="row" style="margin-top: 3%;">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" id="table_salary_view" style="width: 100%;">            
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


<div class="modal fade" id="modal_payslip_daily" role="dialog">
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
                                            <td colspan="3">Additionals</td>
                                        </tr>
                                        <tr id="additionals">

                                        </tr>
                                        <tr>
                                            <td colspan="3">Deductions</td>
                                        </tr>
                                        <tr id="deductions">

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

<div class="modal fade" id="modal_payslip_fixed" role="dialog">
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

function calculate() {
    $.ajax({
            url : base_url+"admin_payroll/admin_view_payroll/already_paid",
            type: "GET",
            data: $('#frm_view_salary').serialize(),
            dataType: "json",
            success: function(response){  
                if(response.result > 0) {
                    alert("Already Calculated, Please view Payroll History");
                }else {
                    view_data_table();
                }                                        
            }
        });
}

function view_data_table() {
    $("#table_salary_view").dataTable().fnDestroy();
        table =  $('#table_salary_view').DataTable({ 
          "ajax": {
                  "url": base_url+"admin_payroll/admin_view_payroll/get_payroll",
                  "data": function(d) {
                    let frm_data = $('#frm_view_salary').serializeArray();
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

function view_payslip(emp_id,start_date,end_date,name_emp,sal_status,date_inc) {
    var date_covered = start_date + " - " +end_date; 
    let total_deductions = 0;  
    let total_additionals = 0;
    if(sal_status == 0)  {
        $('#modal_payslip_daily').modal('show'); 
        $('.modal-title').text('Payslip for Daily type salary');

        document.getElementById('date_covered').innerHTML = date_covered;
        document.getElementById('emp_id').innerHTML = emp_id;
        document.getElementById('name').innerHTML = name_emp;        
        $('#additionals').empty();
        $('#deductions').empty();
        $.ajax({
            url : base_url+"admin_payroll/admin_view_payroll/get_payslip_daily",
            type: "GET",
            data: {employee_id:emp_id, start_date:start_date, end_date:end_date, date_inclusive:date_inc},
            dataType: "json",
            success:function(data) {
                if(date_inc == 0) {                    
                    for(let i=0;i<data['get_additionals_daily'].length;i++) {
                    $('#additionals').append('<tr><td>'+data['get_additionals_daily'][i]['additional_title']+'</td><td>'+data['get_additionals_daily'][i]['amount']+'</td></tr>');
                    total_additionals = total_additionals + parseFloat(data['get_additionals_daily'][i]['amount']);
                    }
                    for(let i=0;i<data['get_deductions_daily'].length;i++) {
                        $('#deductions').append('<tr><td>'+data['get_deductions_daily'][i]['deduction_title']+'</td><td>'+data['get_deductions_daily'][i]['amount']+'</td></tr>');
                        total_deductions = total_deductions + parseFloat(data['get_deductions_daily'][i]['amount']);
                    }
                    document.getElementById('amount_duty').innerHTML = ((parseFloat(data['get_all_details_daily'][0].total_salary)) - (parseFloat(data['get_all_details_daily'][0].premium_pay) + parseFloat(data['get_all_details_daily'][0].cola_rate))) ;
                    document.getElementById('duty_days').innerHTML = data['get_all_details_daily'][0].number_duty;
                    document.getElementById('amount_premium').innerHTML = data['get_all_details_daily'][0].premium_pay;
                    document.getElementById('amount_cola').innerHTML = data['get_all_details_daily'][0].cola_rate;
                    document.getElementById('cola_duration').innerHTML = data['get_all_details_daily'][0].cola_duration;
                    document.getElementById('amount_ot').innerHTML = data['get_all_details_daily'][0].amount_paid;
                    document.getElementById('ot_mins').innerHTML = data['get_all_details_daily'][0].overtime_duration;
                    document.getElementById('amount_late').innerHTML = data['get_all_details_daily'][0].late_deduction;
                    document.getElementById('late_mins').innerHTML = data['get_all_details_daily'][0].late_duration;  
                    document.getElementById('amount_under').innerHTML = data['get_all_details_daily'][0].undertime_deduction;
                    document.getElementById('under_mins').innerHTML = data['get_all_details_daily'][0].undertime_duration;            
                    document.getElementById('total').innerHTML = (parseFloat(data['get_all_details_daily'][0].total_salary)+parseFloat(data['get_all_details_daily'][0].amount_paid) + total_additionals - total_deductions);
                }else if(date_inc == 1) {                    
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
            }
        });    

    }else if(sal_status == 1) {
        $('#modal_payslip_fixed').modal('show'); 
        $('.modal-title').text('Payslip for Fixed type salary');

        document.getElementById('date_covered_fixed').innerHTML = date_covered;
        document.getElementById('emp_id_fixed').innerHTML = emp_id;
        document.getElementById('name_fixed').innerHTML = name_emp;
        $('#additionals').empty();
        $('#deductions').empty();

        $.ajax({
            url : base_url+"admin_payroll/admin_view_payroll/get_payslip_fixed",
            type: "GET",
            data: {employee_id:emp_id, start_date:start_date, end_date:end_date, date_inclusive:date_inc},
            dataType: "json",
            success:function(data) {
                if(date_inc == 0) {
                    for(let i=0;i<data['get_additionals_daily'].length;i++) {
                    $('#additionals').append('<tr><td>'+data['get_additionals_daily'][i]['additional_title']+'</td><td>'+data['get_additionals_daily'][i]['amount']+'</td></tr>');
                    total_additionals = total_additionals + parseFloat(data['get_additionals_daily'][i]['amount']);
                    }
                    for(let i=0;i<data['get_deductions_daily'].length;i++) {
                        $('#deductions').append('<tr><td>'+data['get_deductions_daily'][i]['deduction_title']+'</td><td>'+data['get_deductions_daily'][i]['amount']+'</td></tr>');
                        total_deductions = total_deductions + parseFloat(data['get_deductions_daily'][i]['amount']);
                    }
                    document.getElementById('amount_duty_fixed').innerHTML = (parseFloat(data['get_all_details_fixed'][0].amount)+total_additionals - $total_deductions);
                    document.getElementById('duty_days_fixed').innerHTML = data['get_all_details_fixed'][0].no_days;
                }else if(date_inc == 1) {
                    document.getElementById('amount_duty_fixed').innerHTML = parseFloat(data[0].amount);
                    document.getElementById('duty_days_fixed').innerHTML = data[0].no_days;
                }  
            }
        });

    }
}

</script>

<!-- 

     1. check kung sa tbl_attendance naay data nga inside sa date range
     2. check kung na calculate na ba daan ang date range
        if true (meaning na calculate na)
            - Calculate again / Get ang calculate history        
        if false (calculate dritso) 

-->
