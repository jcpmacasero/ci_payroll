<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
		<div class="table-responsive">
				<table class="table table-striped table-hover table-bordered" id="tbl_deduction" style="width: 100%;">
						<a name='btn_add' id='btn_add' class='btn btn-sm btn-primary' onclick="add_deduction_title();"><span class='fa fa-plus'></span> Add Deduction title</a>
						<thead class="header-th">
								<tr>
										<th class="header-th">Deduction Title</th>
										<th class="header-th">Amount</th> 
										<th class="header-th">Status</th>
										<th class="header-th">Date Created</th>
										<th class="header-th">Created by</th>
										<th class="header-th">Action</th>                   
								</tr>
						</thead>
						<tbody></tbody>
				</table>
		</div>
</div>

<div class="modal fade" id="modal_deduction" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header  btn-success">
							 <button type="button" class="close" data-dismiss="modal">&times;</button>                
							 <h3 class="modal-title"></h3>                    
			</div><!-- modal-header -->
						<div class="modal-body">  
							<form class="form-horizontal" id="frm_deduction">

								<div class="form-group">
									<label class="control-label col-sm-3" for="department_n">Title:</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="deduction_title" style="text-transform: capitalize;" placeholder="Deduction title" required>
										<span class="help-block"></span>
									</div>
								</div>               

								<div class="form-group">
									<label class="control-label col-sm-3" for="department_n">Amount:</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="amt_deduction" style="text-transform: capitalize;" placeholder="Amount" required>
										<span class="help-block"></span>
									</div>
								</div>

								<div class="form-group">
                                  <label class="control-label col-sm-3" for="deduct_stat">Status:</label>
                                  <div class="col-sm-7">
                                     <select name="deduc_stat" class="form-control">
                                        <option value=""></option>
                                        <option value="0">DISABLED</option>
                                        <option value="1">ENABLED</option>                                          
                                     </select>
                                    <span class="help-block"></span>
                                  </div>
                                </div>               

								<div class="form-group">                                  
								<input type="hidden" class="form-control" name="deduction_id" required> 
								</div>
							</form>
								
						</div><!-- modal-body -->
						<div class="modal-footer">
							<button onclick="save()" id="btnDeductSave" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
						</div><!-- modal-footer -->
		</div><!-- modal-content -->
	</div><!-- modal-dialog -->
</div><!-- modaleditpatient -->

<script type="text/javascript">
var tbl_deduction;
var save_method; 
var tbl_deduction_data;

$(document).ready(function() {			
			showall_deductions();			

			$("input").change(function(){
						$(this).parent().parent().removeClass('has-error');
						$(this).next().empty();
			});
});

function add_deduction_title(){
	save_method = 'add';
	$('#frm_deduction')[0].reset(); 
	$('.form-group').removeClass('has-error'); 
	$('.help-block').empty();
	$('#modal_deduction').modal('show'); 
	$('.modal-title').text('Add new title'); 
}

function edit_deduc(id){
        save_method = 'update';
        $('#frm_deduction')[0].reset(); 
        $('.form-group').removeClass('has-error'); 
        $('.help-block').empty(); 
        $.ajax({
            url : base_url+'admin_deduction/deduction/get_specific_deduct/'+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('[name="deduction_title"]').val(data.deduction_title);
                $('[name="amt_deduction"]').val(data.amount);                
                $('[name="deduc_stat"]').val(data.deduction_status);                
                $('[name="deduction_id"]').val(id);
                $('#modal_deduction').modal('show'); 
                $('.modal-title').text('Edit deduction');
            },
            error: function (jqXHR, textStatus, errorThrown){
              toastr.error('A process cannot get through!','Error!')
            }
        });
}

function save(){
	$('#btnDeductSave').text('Saving...'); 
	$('#btnDeductSave').attr('disabled',true);
	let url;

	if(save_method == 'add') {
			url = base_url+'admin_deduction/deduction/add_deduction';
	} 
	else if (save_method == 'update') {
			url = base_url+'admin_deduction/deduction/edit_deduction';
	}         

	$.ajax({
			url : url,
			type: "POST",
			data: $('#frm_deduction').serialize(),
			dataType: "JSON",
			success: function(data){
					if(data.status) {
							reload_tbl_deduction();
							$('#modal_deduction').modal('hide');
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

function reload_tbl_deduction(){
		tbl_deduction.ajax.reload(null,false);  
}

function showall_deductions() {
	var culumn_order = [];
	var culumn_center = [];
	var culumn_disable_sort = [];

	tbl_deduction_data = [{name: '', value: ''}];

	tbl_deduction = $('#tbl_deduction').DataTable({
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
			sAjaxSource: base_url+'admin_deduction/deduction/get_deduction_list',
			fnServerParams: function(aoData) {
					$.each(tbl_deduction_data, function(i, field) {
							aoData.push({ name: field.name, value: field.value });
					});
			},
			fnDrawCallback: function() {

			}
	});
}
</script>
