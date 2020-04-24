<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12"> 
        <div class="col-sm-10">
            <div class="col-sm-4">
            <form role="form" class="form-inline" id="frm_emp_id">
                <div class="form-group">
                    <label for="exampleInputEmail2" class="sr-only">Employee ID</label>
                    <input type="text" placeholder="Enter Employee ID" id="exampleInputEmail2" name="input_emp_id" 
                           class="form-control">
                     <span class="help-block"></span>
                </div>                                               
            </form>
            </div>
            <button class="btn btn-success" id="btnFetch" onclick="searchempID();">Fetch</button>
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
                <p style="font-size: 180%;color:green;">Additionals</p>
                <a name='btn_add' id='btn_add' class='btn btn-sm btn-primary' style="margin-bottom: 1%; " onclick="tag_deduction();"><span class='fa fa-plus' style="margin-bottom: 1%; "></span> Add Additional</a>
                <div class="table-responsive" id="div_data_table">
                    <table class="table table-striped table-hover table-bordered" id="tbl_employee_additional" style="width: 100%;">
                        <thead class="header-th">
                                <tr>
                                        <th class="header-th">Additional Title</th>
                                        <th class="header-th">Amount</th>                                           
                                        <th class="header-th">Status</th>
                                        <th class="header-th">Date Inserted</th>
                                        <th class="header-th">Action</th>                   
                                </tr>
                        </thead>
                        <tbody></tbody>
                    </table>                
                </div>
            </div>
        </fieldset>
    </div>  
</div>



<div class="modal fade" id="modal_tag_additional" role="dialog">
    <div class="modal-dialog"> 
        <div class="modal-content">
            <div class="modal-header  btn-success">
                             <button type="button" class="close" data-dismiss="modal">&times;</button>                
                             <h3 class="modal-title"></h3>                    
            </div><!-- modal-header -->
                        <div class="modal-body">  
                            <form class="form-horizontal" id="frm_tagging_additional">

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="department_n">Title:</label>
                                    <div class="col-sm-7">
                                        <select name="additional_title" class="form-control">
                                             <option value=""></option>
                                             <?php foreach ($title as $key => $value): ?>
                                                  <option value="<?= $value->additional_id; ?>"><?= html_escape($value->additional_title); ?></option>
                                             <?php endforeach ?>                                                                                    
                                         </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>               

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="department_n">Status:</label>
                                    <div class="col-sm-7">
                                         <select name="status_emp_additional" class="form-control">
                                            <option value=""></option>
                                            <option value="0">ACTIVE</option>
                                            <option value="1">NOT ACTIVE</option>                                          
                                         </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div> 
                            </form>
                                
                        </div><!-- modal-body -->
                        <div class="modal-footer">
                            <button onclick="save()" id="btnTagAdditional" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div><!-- modal-footer -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modaleditpatient -->

<script type="text/javascript">
    var tbl_emp_additional;
    var save_method; 
    var tbl_emp_additional_data;

    function searchempID() {
        $('#btnFetch').text('Fetching...'); 
        $('#btnFetch').attr('disabled',true);

        $.ajax({
            url: base_url+'admin_deduction/deduction_tagging/fetchbyId',
            type: "POST",
            data: $('#frm_emp_id').serialize(),
            dataType: "JSON",
                success: function(data){
                    if(data.status) {
                            $('[name="emp_name"]').val(data.emp.firstname+" "+data.emp.middlename+" "+data.emp.lastname+" "+data.emp.name_ext);
                            $('[name="date_hired"]').val(data.emp.date_hired);
                            $('[name="department_name"]').val(data.emp.department_name);
                            $('[name="emp_position"]').val(data.emp.position_title);
                            $('[name="salary_grade"]').val(data.emp.salary_title);
                            $('[name="user_id"]').val(data.emp.user_id);
                            getadditionals_from_employee(data.emp.user_id);
                            // document.getElementById('btn_add').style.visibility='visible';
                            // document.getElementById('div_data_table').style.visibility='visible';
                    }
                    else{
                            document.getElementById('btn_add').style.visibility='hidden';
                            document.getElementById('div_data_table').style.visibility='hidden';
                            toastr.error('Please enter valid employee id','Error!')
                            $.each(data.message, function(key, value) {
                                console.log(key,value);
                                $('[name="'+key+'"]').parent().parent().addClass('has-error'); 
                                $('[name="'+key+'"]').next().text(value);                      
                            });                    
                    }
                    $('#btnFetch').text('Fetch');
                    $('#btnFetch').attr('disabled',false);
                },
                error: function (jqXHR, textStatus, errorThrown){
                        
                         toastr.error('A process cannot get through!','Error!')
                     
                        $('#btnFetch').text('Fetch'); 
                        $('#btnFetch').attr('disabled',false); 
                }
        });
    }

    function reload_tbl_additional_employee(){
        tbl_emp_additional.ajax.reload(null,false);  
    }

    function getadditionals_from_employee(id) {     
        if( $.fn.DataTable.isDataTable('#tbl_employee_additional')) {
            $('#tbl_employee_additional').DataTable().destroy();
        }
        $('#tbl_employee_additional tbody').empty();

        var culumn_order = [];
        var culumn_center = [];
        var culumn_disable_sort = [];

        tbl_emp_additional_data = [{name: '', value: ''}];

        tbl_emp_additional = $('#tbl_employee_additional').DataTable({
                pageLength: 25,
                responsive: true,
                bProcessing: true,
                bServerSide: true,
                deferRender: true,
                order: culumn_order,
                columnDefs: [
                        { className: 'text-center', targets: culumn_center },
                        { orderable: false, targets: culumn_disable_sort },
                ],
                sServerMethod: 'POST',
                sAjaxSource: base_url+'admin_additional/additional_tagging/get_emp_additional_list/'+id,
                fnServerParams: function(aoData) {
                        $.each(tbl_emp_additional_data, function(i, field) {
                                aoData.push({ name: field.name, value: field.value });
                        });                                             
                },
                fnDrawCallback: function() {

                }
        });
    }

    function tag_deduction() {      
        save_method = 'add';
        $('#frm_tagging_additional')[0].reset(); 
        $('.form-group').removeClass('has-error'); 
        $('.help-block').empty();
        $('#modal_tag_additional').modal('show'); 
        $('.modal-title').text('Add title'); 
    }

    function save(){
        $('#btnTagAdditional').text('Saving...'); 
        $('#btnTagAdditional').attr('disabled',true);
        let url;

        if(save_method == 'add') {
            url = base_url+'admin_additional/additional_tagging/addtag_additional_emp';
        } 
        else if (save_method == 'update') {
            url = base_url+'admin_additional/additional_tagging/edit_position';
        }         

        $.ajax({
            url : url,
            type: "POST",
            data: $('#frm_tagging_additional').serialize()+"&emp_id="+$('[name="user_id"]').val(),
            dataType: "JSON",
            success: function(data){
                if(data.status) {   
                    reload_tbl_additional_employee();                
                    $('#modal_tag_additional').modal('hide');
                    if(save_method == 'add'){
                        toastr.success('Additional added','Success!')
                    }else{
                       toastr.success('Additional updated','Success!')
                    }
                }
                else{
                    toastr.error('Please fill up the form correctly','Error!')
                    $.each(data.message, function(key, value) {                  
                      $('[name="'+key+'"]').parent().parent().addClass('has-error'); 
                      $('[name="'+key+'"]').next().text(value);                      
                    });                    
                }
                $('#btnTagAdditional').text('Save');
                $('#btnTagAdditional').attr('disabled',false);
            },
            error: function (jqXHR, textStatus, errorThrown){
                
                 toastr.error('A process cannot get through!','Error!')
               
                $('#btnTagAdditional').text('Save'); 
                $('#btnTagAdditional').attr('disabled',false); 
            }
        });
    }   
</script>