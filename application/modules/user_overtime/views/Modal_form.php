<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal" id="modal_overtime_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">overtime <small>Form</small></h4>
            </div>
            <?= form_open(base_url("user_overtime/overtime/insert_overtime"), ["id" => "form_overtime"], ["overtime_id" => ""]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="user_id" class="control-label">Employee <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-control input-sm select2 user_id" required style="width: 100%;">
                            <option></option>
                        </select>
                    </div>

                    <div class="form-group sys_datepicker col-sm-6">
                        <label for="overtime_in_date" class="control-label">Overtime In (Date)</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="overtime_in_date" id="overtime_in_date" class="form-control" required value="<?= date("m/d/Y"); ?>">
                        </div>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="overtime_in_time" class="control-label">Overtime In (Time) <span class="text-danger">*</span></label>
                        <div class="input-group clockpicker1">
                            <span class="input-group-addon">
                                <span class="fa fa-clock-o"></span>
                            </span>
                            <input type="text" name="overtime_in_time" id="overtime_in_time" class="form-control clockpicker" required value="<?= date("g:i:s A"); ?>">
                        </div>
                    </div>

                    <div class="form-group sys_datepicker col-sm-6">
                        <label for="overtime_out_date" class="control-label">Overtime Out (Date)</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="overtime_out_date" id="overtime_out_date" class="form-control" required value="<?= date("m/d/Y"); ?>">
                        </div>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="overtime_out_time" class="control-label">Overtime Out (Time) <span class="text-danger">*</span></label>
                        <div class="input-group clockpicker1">
                            <span class="input-group-addon">
                                <span class="fa fa-clock-o"></span>
                            </span>
                            <input type="text" name="overtime_out_time" id="overtime_out_time" class="form-control clockpicker" required value="<?= date("g:i:s A"); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="overtime_btnSave">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function() {
        form_validator();

        $("#form_overtime").ajaxForm({
            clearForm   : false,
            resetForm: false,
            beforeSubmit: function() {
                $("#overtime_btnSave").attr("disabled", true);
                $("#overtime_btnSave").html("<span class=\"fa fa-spinner fa-pulse\"></span>");
            },
            success: function(data) {
                var d = JSON.parse(data);
                if(d.success == true) {
                    toastr.success(d.msg, 'Success');
                    $("#overtime_btnSave").attr("disabled", false);
                    $("#overtime_btnSave").html("<span class=\"fa fa-check\"></span> Save");
                    $(".close").click();

                    tbl_overtime.ajax.reload();

                    $(".tbl_restday tbody").empty();
                } else {
                    toastr.warning(d.msg, 'Warning');
                    $("#overtime_btnSave").attr("disabled", false);
                    $("#overtime_btnSave").html("Save");
                }
            }
        });
	});

    function get_overtime_info(value) {
        $.post(base_url+"user_overtime/overtime/get_overtime_info", 
            { value: value }, 
            function(data) {
                var result = JSON.parse(data);
                $.each(result, function(k, v) {
                    $("#form_overtime").each(function() {
                        $("[name="+k+"]").val(v);
                        $(".select2").trigger("change");
                    });
                });
            }
        );
    }
</script>