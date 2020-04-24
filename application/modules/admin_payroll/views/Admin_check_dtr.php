<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12"> 
        <div class="col-sm-6" style="margin-bottom: 3.5%;">
        <form role="form" name="frm_fetch" id="frm_get_dtr" onsubmit="event.preventDefault(); fetchdtr();">
            <div class="form-group">
                <label>Employee ID</label>
                   <input type="text" name="employee_id" class="form-control input-sm" id="employee_id" required value="<?= !empty($employee_info) ? html_escape($employee_info["employee_id"]) : ""; ?>">
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
            <button class="btn btn-primary" type="submit">Fetch Data</button>
        </form>
        </div>        
</div>

<div class="row" style="padding: 5%;">
    <div class="col-sm-11">
        <fieldset class="fieldset" style="padding: 2%">
            <legend class="legend">Employee Details</legend>
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" name="emp_name" class="form-control input-sm" readonly>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="date" class="control-label">Date Hired</label>
                        <input type="text" name="date_hired" class="form-control input-sm" readonly>
                    </div>
                </div>
                <div class="col-sm-2">
                    <p><a href="#">View Full details</a></p>
                </div>
            </div>
            <div class="row">   
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="department" class="control-label">Department</label>
                        <input type="text" name="department_name" class="form-control input-sm" readonly>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="position" class="control-label">Position</label>
                        <input type="text" name="emp_position" class="form-control input-sm" readonly>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="salary" class="control-label">Salary Grade</label>
                        <input type="text" name="salary_grade" class="form-control input-sm" readonly>
                    </div>
                </div>                
            </div>

            <input type="hidden" name="user_id" class="form-control input-sm" readonly>

            <div class="row" style="padding: 2%;">
                <p style="font-size: 180%;color:red;">Time and Attendance</p>
                <a name='btn_add' id='btn_add' class='btn btn-sm btn-primary' style="margin-bottom: 1%; visibility: hidden; " onclick="add_record();"><span class='fa fa-plus' style="margin-bottom: 1%; "></span> Add Record</a>
                <div class="table-responsive" id="div_data_table">
                    <table class="table table-striped table-hover table-bordered" id="tbl_dtr" style="width: 100%;">
                        <thead class="header-th">
                                <tr>
                                    <th class="header-th">Time</th>
                                    <th class="header-th">Status</th>                                                  
                                </tr>
                        </thead>
                        <tbody></tbody>
                    </table>                
                </div>
            </div>
        </fieldset>
    </div>  
</div>



<div class="modal fade" id="modal_add_record" role="dialog">
    <div class="modal-dialog"> 
        <div class="modal-content">
            <div class="modal-header  btn-success">
                             <button type="button" class="close" data-dismiss="modal">&times;</button>                
                             <h3 class="modal-title"></h3>                    
            </div><!-- modal-header -->
                        <div class="modal-body">  
                            <form class="form-horizontal" id="frm_add_record">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="department_n">Date:</label>
                                    <div class="col-sm-7">
                                         <div class="form-group sys_datepicker">                    
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" name="record_date" id="record_date" class="form-control input-sm" value="<?= !empty($employee_info) ? html_escape($employee_info["date_hired"]) : date("m/d/Y"); ?>" required>
                                            </div>
                                        </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="department_n">Time:</label>
                                    <div class="col-sm-7">
                                         <div class="input-group clockpicker1">
                                            <span class="input-group-addon">
                                                <span class="fa fa-clock-o"></span>
                                            </span>
                                            <input type="text" name="time_in" id="time_in" class="form-control clockpicker" required value="<?= date("g:i:s A"); ?>">
                                        </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="department_n">Status:</label>
                                    <div class="col-sm-7">
                                         <select name="record_stat" class="form-control">
                                            <option value="">Select</option>
                                            <option value="1">Time In</option>
                                            <option value="2">Time Out</option>                                          
                                         </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </form>
                                
                        </div><!-- modal-body -->
                        <div class="modal-footer">
                            <button onclick="save()" id="btnAddRecord" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div><!-- modal-footer -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modaleditpatient -->


<script type="text/javascript">
var save_method;
var tbl_dtr ;
   
function fetchdtr() {
    let emp_id = $('[name="employee_id"]').val();
    let start_date = $('[name="date_start"]').val();
    let start_newdate = start_date.split("/").reverse().join("-");    
    let end_date = $('[name="date_end"]').val();
    let end_newdate = end_date.split("/").reverse().join("-");        

     get_emp_data(emp_id);
     view_data_table(start_newdate,end_newdate,emp_id);
     document.getElementById('btn_add').style.visibility='visible';
}

function view_data_table(start_newdate,end_newdate,emp_id) {
    $("#tbl_dtr").dataTable().fnDestroy();
            tbl_dtr =  $('#tbl_dtr').DataTable({ 
              "ajax": {
                      "url": base_url+"admin_payroll/admin_check_dtr/getdtr/"+start_newdate+"/"+end_newdate,
                      "data": {employee_id:emp_id},
                      "type": "POST",
                  },
                  responsive: true                  
    });    
}

function get_emp_data(emp_id) {
     $.ajax({
            url : base_url+"admin_payroll/admin_check_dtr/data_emp/"+emp_id,
            type: "GET", 
            dataType: "JSON",                   
            success: function(data){                
                let fname = data[0]['firstname'];
                let fullname = fname.concat(" ",data[0]['middlename']," ",data[0]['lastname'], " ", data[0]['name_ext']);
                $('[name="emp_name"]').val(data[0]['middlename']);
                $('[name="date_hired"]').val(data[0]['date_hired']);
                $('[name="department_name"]').val(data[0]['department_name']);
                $('[name="emp_position"]').val(data[0]['position_title']);
                $('[name="salary_grade"]').val(data[0]['salary_title']);
                $('[name="user_id"]').val(data[0]['employee_id']);
            }
     });
}

function add_record() {
    save_method = 'add';
        $('#frm_add_record')[0].reset(); 
        $('.form-group').removeClass('has-error'); 
        $('.help-block').empty();
        $('#modal_add_record').modal('show'); 
        $('.modal-title').text('Add Record'); 
}

function save() {
    $('#btnAddRecord').text('Saving...'); 
    $('#btnAddRecord').attr('disabled',true);
    let url;

    if(save_method == 'add') {
        url = base_url+'admin_payroll/admin_check_dtr/addDailyRecord';
    }     

     $.ajax({
            url : url,
            type: "POST",
            data: $('#frm_add_record').serialize()+"&emp_id="+$('[name="user_id"]').val(),
            dataType: "JSON",
            success: function(data){
                if(data.status) {   
                    reload_tbl_dtr();               
                    $('#modal_add_record').modal('hide');
                    if(save_method == 'add'){
                        toastr.success('Record added','Success!')
                    }else{
                       toastr.success('Record updated','Success!')
                    }
                }
                else{
                    toastr.error('Please fill up the form correctly','Error!')
                    $.each(data.message, function(key, value) {                  
                      $('[name="'+key+'"]').parent().parent().addClass('has-error'); 
                      $('[name="'+key+'"]').next().text(value);                      
                    });                    
                }
                $('#btnAddRecord').text('Save');
                $('#btnAddRecord').attr('disabled',false);
            },
            error: function (jqXHR, textStatus, errorThrown){
                
                 toastr.error('A process cannot get through!','Error!')               
                $('#btnAddRecord').text('Save'); 
                $('#btnAddRecord').attr('disabled',false); 
            }
        });        
}

function reload_tbl_dtr(){
        tbl_dtr.ajax.reload(null,false);  
}

</script>


