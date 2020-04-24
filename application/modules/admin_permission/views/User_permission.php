<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style type="text/css">
	input[type="checkbox"] {
    	display: none;
	}

	input[type="checkbox"] + label {
	    color: #f2f2f2;
	}

	input[type="checkbox"] + label span {
	    display:inline-block;
	    width: 19px;
	    height: 19px;
	    margin: -2px 10px 0 0;
	    vertical-align: middle;
	    background: url(<?= base_url("assets/img/common/check_radio_sheet.png"); ?>) left top no-repeat;
	    cursor:pointer;
	}

	input[type="checkbox"]:checked + label span {
	    background: url(<?= base_url("assets/img/common/check_radio_sheet.png"); ?>) -19px top no-repeat;
	}
</style>

<div class="col-md-12">
	<div class="panel panel-info">
		<div class="panel-body">
			<form id="form_user">
				<div class="form-group col-sm-3">
					<div class="form-group">
						<label for="user_id">User</label>
						<select name="user_id" id="user_id" class="form-control">
							<option></option>
						</select>
					</div>
				</div>
	        </form>
		</div>
	</div>
</div>

<div class="col-sm-12">
	<button href='#modal_module' data-toggle='modal' name='btn_add' onclick="get_system_mods()" class='btn btn-sm btn-primary'><span class='fa fa-puzzle-piece'></span> Modules</button>
	<table class="table table-striped table-hover table-bordered" id="tbl_permission_mod" style="font-size: 12px; width: 100%;">
    	<thead>
    		<th>Module</th>
    		<th>Permission</th>
    	</thead>
    	<tbody></tbody>
    </table>
</div>

<style type="text/css">
	#modal_module input[type=text] {
		font-size: 12px;
	}
</style>

<div class="modal fade" id="modal_module" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5>Module <small>List</small></h5>
			</div>
			<div class="modal-body">
				<table class="table table-striped" id="tbl_module" style="font-size: 12px; width: 100%;">
					<thead>
						<th>Module</th>
						<th></th>
						<th>Buttons</th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<?= form_open(base_url('admin_permission/user_permission/insert_module'), ["class" => "form-inline", "id" => "form_module"]); ?>
					<div class="form-group">
						<input type="text" placeholder="Module Name" name="module_name" class="form-control input-sm" required>
						<button class="btn btn-sm btn-primary" id="mod_btnAdd">Add Module</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var users = <?= $users ?>;

	var tbl_permission_mod;
	var tbl_permission_mod_data;

	$(function() {

		// $("[name=btn_add]").hide();

		$("#user_id").select2({
			placeholder: "Search",
			allowClear: true,
			data: users
		}).on('change', function() {
			tbl_permission_mod_data = $("#form_user").serializeArray();
        	tbl_permission_mod.ajax.reload();
		});

		tbl_permission_mod = $("#tbl_permission_mod").DataTable({
			dom: "ft",
			sort: false,
			bPaginate: false,
			ajax: {
	            url: base_url+"admin_permission/user_permission/get_permission_mods",
	            type: "POST",
	            data: function() {
	              	return tbl_permission_mod_data;
	            }
	        }
		});
	});

	function change_status(elem, user_id, user_mod_button_id) {
		var status = $(elem).prop("checked");

		if (status == true) {
			status = 1;
		} else {
			status = 0;
		}

		$.post(base_url+"admin_permission/user_permission/change_status",
			{ status: status, user_id: user_id, user_mod_button_id: user_mod_button_id },
			function() {}
		);		
	}


	function change_status_all(elem, user_id, user_module_id) {
		var status = $(elem).prop("checked");

		if (status == true) {
			status = 1;
		} else {
			status = 0;
		}

		$.post(base_url+"admin_permission/user_permission/change_status_all",
			{ status: status, user_id: user_id, user_module_id: user_module_id },
			function() {
				tbl_permission_mod_data = $("#form_user").serializeArray();
	    		tbl_permission_mod.ajax.reload();
			}
		);		
	}

	var tbl_module;
	var tbl_module_data;

	$(function() {

		tbl_module = $("#tbl_module").DataTable({
			dom: "t",
			sort: false,
			bPaginate: false,
			ajax: {
	            url: base_url+"admin_permission/user_permission/get_modules",
	            type: "POST",
	            data: function() {
	              	return tbl_module_data;
	            }
	        }
		});

		var mod_form_options = {
	        clearForm: false,
	        resetForm: true,
	        beforeSubmit: function() {
	    		$("#mod_btnAdd").attr("disabled", true);
	        	$("#mod_btnAdd").html("<span class=\"fa fa-spinner fa-pulse\"></span>");
	        },
	        success: function(data) {
	            var d = JSON.parse(data);
	            if(d.success == true) {
						tbl_module.ajax.reload();
						tbl_permission_mod.ajax.reload();
	            }
	            toastr.success(d.msg, 'Success');
	            $("#mod_btnAdd").attr("disabled", false);
	            $("#mod_btnAdd").html('Add Module');
	        }
	    };

	    $("#form_module").ajaxForm(mod_form_options);

	});

	function get_system_mods() {
		tbl_module.ajax.reload();
	}

	function edit_module(elem, id) {
		var module_name = $(elem).closest("tr").find(".module_name").val();

		if(module_name != "") {
			$.post(base_url+"admin_permission/user_permission/insert_module",
				{ user_module_id: id, module_name: module_name },
				function(data) {
					var d = JSON.parse(data);
	                if(d.success == true) {
	  					tbl_module.ajax.reload();
	  					tbl_permission_mod.ajax.reload();
	                }
	                toastr.success(d.msg, 'Success');
				}
			);
		}
	}

	function add_button(elem, user_module_id) {
		var button_name = $(elem).closest(".form_mod_btn").find(".button_name").val();
		var button_code = $(elem).closest(".form_mod_btn").find(".button_code").val();

		if(button_name != "" && button_code != "") {
			$.post(base_url+"admin_permission/user_permission/insert_module_btn",
				{ user_module_id: user_module_id, button_name: button_name, button_code: button_code },
				function(data) {
					var d = JSON.parse(data);
	                if(d.success == true) {
	  					tbl_module.ajax.reload();
	  					tbl_permission_mod.ajax.reload();
	                }
	                toastr.success(d.msg, 'Success');
				}
			);
		}
	}

	function add_button_enter(elem, user_module_id) {
		var keypressed = event.keyCode || event.which;

		if(keypressed == 13) {
			var button_name = $(elem).closest(".form_mod_btn").find(".button_name").val();
			var button_code = $(elem).closest(".form_mod_btn").find(".button_code").val();
			if(button_name != "" && button_code != "") {
				$.post(base_url+"admin_permission/user_permission/insert_module_btn",
					{ user_module_id: user_module_id, button_name: button_name, button_code: button_code },
					function(data) {
						var d = JSON.parse(data);
		                if(d.success == true) {
		  					tbl_module.ajax.reload();
		  					tbl_permission_mod.ajax.reload();
		                }
		                toastr.success(d.msg, 'Success');
					}
				);
			}
		}
	}

	function edit_mod_button(elem, id) {
		var button_name = $(elem).closest("tr").find(".button_name").val();
		var button_code = $(elem).closest("tr").find(".button_code").val();
		
		if(button_name != "" && button_code != "") {
			$.post(base_url+"admin_permission/user_permission/insert_module_btn",
				{ user_mod_button_id: id, button_name: button_name, button_code: button_code },
				function(data) {
					var d = JSON.parse(data);
	                if(d.success == true) {
	  					tbl_module.ajax.reload();
	  					tbl_permission_mod.ajax.reload();
	                }
	                toastr.success(d.msg, 'Success');
				}
			);
		}
	}
</script>