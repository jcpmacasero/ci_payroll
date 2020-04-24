<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal" id="modal_duty_rest_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Duty Rest <small>Form</small></h4>
            </div>
            <?= form_open(base_url("user_duty_rest/duty_rest/insert_duty_rest"), ["id" => "form_duty_rest"], ["dutyrest_id" => ""]); ?>
            <div class="modal-body">
                <div class="form-group">
                	<label for="user_id" class="control-label">Employee <span class="text-danger">*</span></label>
                	<select name="user_id" id="user_id" class="form-control input-sm select2 user_id" required style="width: 100%;">
                		<option></option>
                	</select>
                </div>

                <div class="form-group sys_datepicker">
                    <label for="date_duty" class="control-label">Date Duty</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="date_duty" id="date_duty" class="form-control" required value="<?= date("m/d/Y"); ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="duty_rest_btnSave">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function() {
        form_validator();

        $("#form_duty_rest").ajaxForm({
            clearForm   : false,
            resetForm: false,
            beforeSubmit: function() {
                $("#duty_rest_btnSave").attr("disabled", true);
                $("#duty_rest_btnSave").html("<span class=\"fa fa-spinner fa-pulse\"></span>");
            },
            success: function(data) {
                var d = JSON.parse(data);
                if(d.success == true) {
                    toastr.success(d.msg, 'Success');
                    $("#duty_rest_btnSave").attr("disabled", false);
                    $("#duty_rest_btnSave").html("<span class=\"fa fa-check\"></span> Save");
                    $(".close").click();

                    tbl_duty_rest.ajax.reload();

                    $(".tbl_restday tbody").empty();
                } else {
                    toastr.warning(d.msg, 'Warning');
                    $("#duty_rest_btnSave").attr("disabled", false);
                    $("#duty_rest_btnSave").html("Save");
                }
            }
        });
	});

    function get_duty_rest_info(value) {
        $.post(base_url+"user_duty_rest/duty_rest/get_duty_rest_info", 
            { value: value }, 
            function(data) {
                var result = JSON.parse(data);
                $.each(result, function(k, v) {
                    $("#form_duty_rest").each(function() {
                        $("[name="+k+"]").val(v);
                        $(".select2").trigger("change");
                    });
                });
            }
        );
    }
</script>