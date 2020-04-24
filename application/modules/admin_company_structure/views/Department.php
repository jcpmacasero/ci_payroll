<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
		<div class="table-responsive">
				<table class="table table-striped table-hover table-bordered" id="tbl_department" style="width: 100%;">
						<a name='btn_add' id='btn_add' class='btn btn-sm btn-primary' onclick="add_dept_click();"><span class='fa fa-plus'></span> Add Department</a>
						<thead class="header-th">
								<tr>
										<th class="header-th">Department Name</th>
										<th class="header-th">Date Created</th> 
										<th class="header-th">Created by</th>
										<th class="header-th">Action</th>                   
								</tr>
						</thead>
						<tbody></tbody>
				</table>
		</div>
</div>

<div class="modal fade" id="modal_department" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header  btn-success">
							 <button type="button" class="close" data-dismiss="modal">&times;</button>                
							 <h3 class="modal-title"></h3>                    
			</div><!-- modal-header -->
						<div class="modal-body">  
							<form class="form-horizontal" id="frm_department">

								<div class="form-group">
									<label class="control-label col-sm-3" for="department_n">Department Name:</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="dept_name" style="text-transform: capitalize;" placeholder="Department name" required>
										<span class="help-block"></span>
									</div>
								</div>               
								<div class="form-group">                                  
								<input type="hidden" class="form-control" name="dept_id" required> 
								</div>
							</form>
								
						</div><!-- modal-body -->
						<div class="modal-footer">
							<button onclick="save()" id="btnDeptSave" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
						</div><!-- modal-footer -->
		</div><!-- modal-content -->
	</div><!-- modal-dialog -->
</div><!-- modaleditpatient -->

<script type="text/javascript">
var tbl_department;
var save_method; 

$(document).ready(function() {			
			showall_department();

			$("input").change(function(){
						$(this).parent().parent().removeClass('has-error');
						$(this).next().empty();
			});
});

function add_dept_click(){
				save_method = 'add';
				$('#frm_department')[0].reset(); 
				$('.form-group').removeClass('has-error'); 
				$('.help-block').empty();
				$('#modal_department').modal('show'); 
				$('.modal-title').text('Add new department'); 
}

function update_dept(id){
        save_method = 'update';
        $('#frm_department')[0].reset(); 
        $('.form-group').removeClass('has-error'); 
        $('.help-block').empty(); 
        $.ajax({
            url : base_url+'admin_company_structure/department/get_specific_dept/'+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('[name="dept_id"]').val(id);
                $('[name="dept_name"]').val(data.department_name);                
                $('#modal_department').modal('show'); 
                $('.modal-title').text('Edit department');
            },
            error: function (jqXHR, textStatus, errorThrown){
              toastr.error('A process cannot get through!','Error!')
            }
        });
}

function save(){
	$('#btnDeptSave').text('Saving...'); 
	$('#btnDeptSave').attr('disabled',true);
	let url;

	if(save_method == 'add') {
			url = base_url+'admin_company_structure/department/add_department';
	} 
	else if (save_method == 'update') {
			url = base_url+'admin_company_structure/department/edit_department';
	}         

	$.ajax({
			url : url,
			type: "POST",
			data: $('#frm_department').serialize(),
			dataType: "JSON",
			success: function(data){
					if(data.status) {
							reload_tbl_dept();
							$('#modal_department').modal('hide');
							if(save_method == 'add'){
									toastr.success('Department added','Success!')
							}else{
								 toastr.success('Department updated','Success!')
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
					$('#btnDeptSave').text('Save');
					$('#btnDeptSave').attr('disabled',false);
			},
			error: function (jqXHR, textStatus, errorThrown){
					
					 toastr.error('A process cannot get through!','Error!')
				 
					$('#btnDeptSave').text('Save'); 
					$('#btnDeptSave').attr('disabled',false); 
			}
	});
}

function reload_tbl_dept(){
    tbl_department.ajax.reload(null,false);  
}

function showall_department() {
	var culumn_order = [];
	var culumn_center = [3];
	var culumn_disable_sort = [3];

	tbl_department_data = [{name: '', value: ''}];

	tbl_department = $('#tbl_department').DataTable({
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
			sAjaxSource: base_url+'admin_company_structure/department/get_department_list',
			fnServerParams: function(aoData) {
					$.each(tbl_department_data, function(i, field) {
							aoData.push({ name: field.name, value: field.value });
					});
			},
			fnDrawCallback: function() {

			}
	});
}
</script>