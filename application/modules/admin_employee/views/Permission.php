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
        background: url(<?=base_url('assets/img/common/check_radio_sheet.png');?>) left top no-repeat;
        cursor:pointer;
    }

    input[type="checkbox"]:checked + label span {
        background: url(<?=base_url('assets/img/common/check_radio_sheet.png');?>) -19px top no-repeat;
    }
</style>

<div class="modal fade" id="modal_permission" role="dialog">
  	<div class="modal-dialog modal-md">
	    <div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal">&times;</button>
	            <h5>Permission <small>Setting</small></h5>
	        </div>
	        <div class="modal-body">
	            <table class="table table-striped table-hover table-bordered" id="tbl_permission_mod" style="font-size: 12px; width: 100%;">
                    <thead>
                        <th>Module</th>
                        <th>
                            Permission
                            <div class="pull-right">
	                            <input type="checkbox" name="super_select_all" id="super_select_all" class="super_select_all">
                            	<label for="super_select_all"><span></span></label>
	                            Super Select All
                            </div>
                        </th>
                    </thead>
                    <tbody></tbody>
                </table>
	        </div>
	    </div>
  	</div>
</div>

<script type="text/javascript">
	function get_permission(id) {
		console.log(id);
	}

	$("#super_select_all").on("click",function(){
	    $(".check").each(function(){
	        $(this).click();
	        $(this).prop("checked", "checked");
	    });
	});

	var tbl_permission_mod;
	var tbl_permission_mod_data;

	$(function() {

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

	    $("#modal_permission" ).on('shown.bs.modal', function(){
		    var count = $("#tbl_permission_mod .check").length;
		    var count_checked = $("#tbl_permission_mod .check:checked").length;
		    var count_unchecked = $("#tbl_permission_mod .check:not(:checked)").length;
		    if (count_checked == count) {
		        $("#tbl_permission_mod #super_select_all").prop("checked", true);
		    } else {
		        $("#tbl_permission_mod #super_select_all").prop("checked", false);
		    }
		});
	});

	function get_permission(id) {
	    tbl_permission_mod_data = [{ name: "user_id", value: id }];
	    tbl_permission_mod.ajax.reload();
	}

	function change_status(elem, user_id, user_mod_button_id) {
	    var status = $(elem).prop("checked");
	    if(status == true) {
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
	    if(status == true) {
	        status = 1;
	    } else {
	        status = 0;
	    }
	    $.post(base_url+"admin_permission/user_permission/change_status_all",
	            { status: status, user_id: user_id, user_module_id: user_module_id },
	            function() {
	                tbl_permission_mod_data = [{ name: "user_id", value: user_id }];
	                tbl_permission_mod.ajax.reload();
	            }
	        );      
	}
</script>