<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
		<div class="table-responsive">
				<table class="table table-striped table-hover table-bordered" id="tbl_leaves" style="width: 100%;">
						<a name='btn_add' id='btn_add' class='btn btn-sm btn-primary' onclick="add_leave_title();"><span class='fa fa-plus'></span> Add Leave title</a>
						<thead class="header-th">
								<tr>
										<th class="header-th">Leave Title</th>
										<th class="header-th">Leave Status</th> 
										<th class="header-th">Status</th>
										<th class="header-th">Duration</th>
										<th class="header-th">Date Created</th>
										<th class="header-th">Created by</th>
										<th class="header-th">Action</th>                   
								</tr>
						</thead>
						<tbody></tbody>
				</table>
		</div>
</div>

<div class="modal fade" id="modal_leave" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header  btn-success">
							 <button type="button" class="close" data-dismiss="modal">&times;</button>                
							 <h3 class="modal-title"></h3>                    
			</div><!-- modal-header -->
						<div class="modal-body">  
							<form class="form-horizontal" id="frm_leave">

								<div class="form-group">
									<label class="control-label col-sm-3" for="department_n">Leave Title:</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="lea_title" style="text-transform: capitalize;" placeholder="Leave title" required>
										<span class="help-block"></span>
									</div>
								</div>               

								<div class="form-group">
                                  <label class="control-label col-sm-3" for="deduct_stat">Leave Status:</label>
                                  <div class="col-sm-7">
                                     <select name="lea_status" class="form-control">
                                        <option value=""></option>
                                        <option value="0">With Pay</option>
                                        <option value="1">Without Pay</option>                                          
                                     </select>
                                    <span class="help-block"></span>
                                  </div>
                                </div>  

								<div class="form-group">
                                  <label class="control-label col-sm-3" for="deduct_stat">Status:</label>
                                  <div class="col-sm-7">
                                     <select name="main_status" class="form-control">
                                        <option value=""></option>
                                        <option value="0">DISABLED</option>
                                        <option value="1">ENABLED</option>                                          
                                     </select>
                                    <span class="help-block"></span>
                                  </div>
                                </div>

                                <div class="form-group">
									<label class="control-label col-sm-3" for="department_n">Leave Duration:</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="lea_duration" style="text-transform: capitalize;" placeholder="Leave duration" required>
										<span class="help-block"></span>
									</div>
								</div>               

								<div class="form-group">                                  
								<input type="hidden" class="form-control" name="leave_id" required> 
								</div>
							</form>
								
						</div><!-- modal-body -->
						<div class="modal-footer">
							<button onclick="save()" id="btnSaveLeave" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
						</div><!-- modal-footer -->
		</div><!-- modal-content -->
	</div><!-- modal-dialog -->
</div><!-- modaleditpatient -->

<script type="text/javascript">
var tbl_leaves;
var save_method; 
var tbl_leaves_data;

$(document).ready(function() {			
			showall_leaves();			

			$("input").change(function(){
						$(this).parent().parent().removeClass('has-error');
						$(this).next().empty();
			});
});

function add_leave_title(){
	save_method = 'add';
	$('#frm_leave')[0].reset(); 
	$('.form-group').removeClass('has-error'); 
	$('.help-block').empty();
	$('#modal_leave').modal('show'); 
	$('.modal-title').text('Add new leave title'); 
}

function save(){
	$('#btnSaveLeave').text('Saving...'); 
	$('#btnSaveLeave').attr('disabled',true);
	let url;

	if(save_method == 'add') {
			url = base_url+'admin_leave/leave_create/add_leave';
	} 
	else if (save_method == 'update') {
			url = base_url+'admin_leave/leave_create/edit_leave';
	}         

	$.ajax({
			url : url,
			type: "POST",
			data: $('#frm_leave').serialize(),
			dataType: "JSON",
			success: function(data){
					if(data.status) {
							reload_tbl_leave();
							$('#modal_leave').modal('hide');
							if(save_method == 'add'){
									toastr.success('Title added','Success!')
							}else{
								 toastr.success('Title updated','Success!')
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
					$('#btnDeductSave').text('Save');
					$('#btnDeductSave').attr('disabled',false);
			},
			error: function (jqXHR, textStatus, errorThrown){
					
					 toastr.error('A process cannot get through!','Error!')
				 
					$('#btnDeductSave').text('Save'); 
					$('#btnDeductSave').attr('disabled',false); 
			}
	});
}

function reload_tbl_leave(){
		tbl_leaves.ajax.reload(null,false);  
}

function showall_leaves() {
	var culumn_order = [];
	var culumn_center = [];
	var culumn_disable_sort = [];

	tbl_leaves_data = [{name: '', value: ''}];

	tbl_leaves = $('#tbl_leaves').DataTable({
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
			sAjaxSource: base_url+'admin_leave/leave_create/get_leave_list',
			fnServerParams: function(aoData) {
					$.each(tbl_leaves_data, function(i, field) {
							aoData.push({ name: field.name, value: field.value });
					});
			},
			fnDrawCallback: function() {

			}
	});
}

</script>