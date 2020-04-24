<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
		<div class="table-responsive">
				<table class="table table-striped table-hover table-bordered" id="tbl_on_leave" style="width: 100%;">
						<a name='btn_add' id='btn_add' class='btn btn-sm btn-primary' onclick="apply_leave_click();"><span class='fa fa-plus'></span> Apply Leave</a>
						<thead class="header-th">
								<tr>
										<th class="header-th">Employee ID</th>
										<th class="header-th">Leave Title</th> 
										<th class="header-th">Duration</th>
										<th class="header-th">Inclusive Dates</th>									
										<th class="header-th">Date Applied</th>
										<th class="header-th">Created by</th>
										<th class="header-th">Actions</th>													
								</tr>
						</thead>
						<tbody></tbody>
				</table>
		</div>
</div>

<div class="modal fade" id="modal_apply_leave" role="dialog">
    <div class="modal-dialog"> 
        <div class="modal-content">
            <div class="modal-header  btn-success">
                             <button type="button" class="close" data-dismiss="modal">&times;</button>                
                             <h3 class="modal-title"></h3>                    
            </div><!-- modal-header -->
                        <div class="modal-body">                             
                            <form class="form-horizontal" id="frm_apply_leave">

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="addtnl_title">Employee ID:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="employee_id" style="text-transform: capitalize;" placeholder="Employee ID" required>
                                        <span class="help-block"></span>
                                    </div>
                                </div>                                              

                                <div class="form-group">
                                  <label class="control-label col-sm-3" for="additional_stat">Leave Title:</label>
                                  <div class="col-sm-7">
                                     <select name="leave_title" class="form-control">
                             			<option value="">Select</option>
                                        <?php foreach ($leaves as $key => $value): ?>
                                            <option value="<?= $value->leave_id; ?>"><?= html_escape($value->leave_title); ?></option>
                                        <?php endforeach ?>                                        
                                     </select>
                                    <span class="help-block"></span>
                                  </div>
                                </div>

                                <div class="form-group sys_datepicker">
                                    <label class="control-label col-sm-3" for="addtnl_title">Date Applied:</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" name="leave_date_apply" class="form-control input-sm" required>
                                            <span class="help-block"></span>
                                        </div>                                        
                                    </div>                                    
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="addtnl_title">Duration:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="apply_duration" style="text-transform: capitalize;" placeholder="How many days ?" required>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group sys_datepicker">
                                    <label class="control-label col-sm-3" for="addtnl_title">Leave Start:</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
		                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
		                                    <input type="text" name="leave_date_start" id="leave_date_begin" class="form-control input-sm" required>
                                            <span class="help-block"></span>
		                                </div>                                        
                                    </div>                                    
                                </div>

                                <div class="form-group sys_datepicker">
                                    <label class="control-label col-sm-3" for="addtnl_title">Leave End:</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
		                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
		                                    <input type="text" name="leave_date_end" id="leave_date_ending" class="form-control input-sm" required>
                                            <span class="help-block"></span>
		                                </div>                                        
                                    </div>                                    
                                </div>    

                                <div class="form-group">                                                                  	
                                	<input type="hidden" class="form-control" name="on_leave_id" required> 
                                </div>
                            </form>
                                
                        </div><!-- modal-body -->
                        <div class="modal-footer">
                            <button onclick="save()" id="btnApplySave" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div><!-- modal-footer -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modaleditpatient -->

<script type="text/javascript">
var tbl_on_leave;
var save_method; 
var tbl_on_leave_data;

$(document).ready(function() {          
        // showall_additionals();           

        $("input").change(function(){
                    $(this).parent().parent().removeClass('has-error');
                    $(this).next().empty();
        });
});

function apply_leave_click(){
    save_method = 'add';
    $('#frm_apply_leave')[0].reset(); 
    $('.form-group').removeClass('has-error'); 
    $('.help-block').empty();
    $('#modal_apply_leave').modal('show'); 
    $('.modal-title').text('Apply Leave');     
    $('.sys_datepicker .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
    });
}

function save(){
    $('#btnApplySave').text('Saving...'); 
    $('#btnApplySave').attr('disabled',true);
    let url;

    if(save_method == 'add') {
            url = base_url+'admin_leave/leave_apply/apply_leave';
    } 
    else if (save_method == 'update') {
            url = base_url+'admin_leave/leave_apply/edit_apply_leave';
    }         

    $.ajax({
            url : url,
            type: "POST",
            data: $('#frm_apply_leave').serialize(),
            dataType: "JSON",
            success: function(data){
                    if(data.status) {                            
                            $('#modal_apply_leave').modal('hide');
                            if(save_method == 'add'){
                                    toastr.success('Leave application approved','Success!')
                            }else{
                                 toastr.success('Leave application updated','Success!')
                            }
                    }
                    else{
                            toastr.error('Please fill up the form correctly','Error!')
                            $.each(data.message, function(key, value) {
                                console.log(key,value);
                                $('[name="'+key+'"]').parent().parent().addClass('has-error'); 
                                $('[name="'+key+'"]').next().text(value);                      
                            });                    
                    }
                    $('#btnApplySave').text('Save');
                    $('#btnApplySave').attr('disabled',false);
            },
            error: function (jqXHR, textStatus, errorThrown){
                    
                     toastr.error('A process cannot get through!','Error!')
                 
                    $('#btnApplySave').text('Save'); 
                    $('#btnApplySave').attr('disabled',false); 
            }
    });
}

function show_leave_applications() {
    var culumn_order = [];
    var culumn_center = [];
    var culumn_disable_sort = [];

    tbl_on_leave_data = [{name: '', value: ''}];

    tbl_on_leave = $('#tbl_on_leave').DataTable({
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
            sAjaxSource: base_url+'admin_leave/leave_apply/get_onleave_list',
            fnServerParams: function(aoData) {
                    $.each(tbl_on_leave_data, function(i, field) {
                            aoData.push({ name: field.name, value: field.value });
                    });
            },
            fnDrawCallback: function() {

            }
    });
}

</script>