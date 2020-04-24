<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal" id="modal_schedule_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Schedule <small>Form</small></h4>
            </div>
            <?= form_open(base_url("user_schedule/schedule/insert_schedule"), ["id" => "form_schedule"], ["schedule_id" => ""]); ?>
            <div class="modal-body">
                <div class="form-group">
                	<label for="user_id" class="control-label">Employee <span class="text-danger">*</span></label>
                	<select name="user_id" id="user_id" class="form-control input-sm select2 user_id" required style="width: 100%;">
                		<option></option>
                	</select>
                </div>

                <div class="form-group">
                	<label for="time_in" class="control-label">Time In <span class="text-danger">*</span></label>
                	<div class="input-group clockpicker1">
	                    <span class="input-group-addon">
	                        <span class="fa fa-clock-o"></span>
	                    </span>
	                    <input type="text" name="time_in" id="time_in" class="form-control clockpicker" required value="<?= date("g:i:s A"); ?>">
	                </div>
                </div>

                <div class="form-group">
                	<label for="time_out" class="control-label">Time Out <span class="text-danger">*</span></label>
                	<div class="input-group">
	                    <span class="input-group-addon">
	                        <span class="fa fa-clock-o"></span>
	                    </span>
	                    <input type="text" name="time_out" id="time_out" class="form-control clockpicker" required value="<?= date("g:i:s A"); ?>">
	                </div>
                </div>

                <div class="form-group sys_datepicker">
                    <label for="date_from" class="control-label">From</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="date_from" id="date_from" class="form-control" required value="<?= date("m/d/Y"); ?>">
                    </div>
                </div>

                <div class="form-group sys_datepicker">
                    <label for="date_to" class="control-label">To</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="date_to" id="date_to" class="form-control" required value="<?= date("m/d/Y"); ?>">
                    </div>
                </div>

                <table class="table tbl_restday">
                    <thead>
                        <th>Restday</th>
                        <th style="width: 5%;">
                            <button type="button" class="btn btn-primary btn-xs" onclick="add_rest_day();" title="Add Row"><i class="fa fa-plus"></i></button>
                        </th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="schedule_btnSave">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function() {
        $("#modal_schedule_form").on("shown.bs.modal", function() {
            $(".tbl_restday tbody").empty();
        });

        form_validator();

        $("#form_schedule").ajaxForm({
            clearForm   : false,
            resetForm: false,
            beforeSubmit: function() {
                $("#schedule_btnSave").attr("disabled", true);
                $("#schedule_btnSave").html("<span class=\"fa fa-spinner fa-pulse\"></span>");
            },
            success: function(data) {
                var d = JSON.parse(data);
                if(d.success == true) {
                    toastr.success(d.msg, 'Success');
                    $("#schedule_btnSave").attr("disabled", false);
                    $("#schedule_btnSave").html("<span class=\"fa fa-check\"></span> Save");
                    $(".close").click();

                    tbl_schedule.ajax.reload();

                    $(".tbl_restday tbody").empty();
                } else {
                    toastr.warning(d.msg, 'Warning');
                    $("#schedule_btnSave").attr("disabled", false);
                    $("#schedule_btnSave").html("Save");
                }
            }
        });
	});

    function get_schedule_info(value) {
        $(".tbl_restday tbody").empty();

        $.post(base_url+"user_schedule/schedule/get_schedule_info", 
            { value: value }, 
            function(data) {
                var result = JSON.parse(data);
                $.each(result.data_info, function(k, v) {
                    $("#form_schedule").each(function() {
                        $("[name="+k+"]").val(v);
                        $(".select2").trigger("change");
                    });
                });

                $.each(result.rest_day, function(k, v) {
                    var row = '<tr>\
                                    <td>\
                                        <div class="form-group sys_datepicker">\
                                            <div class="input-group date">\
                                                <input type="hidden" name="restday_id[]" value="'+v.restday_id+'">\
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>\
                                                <input type="text" name="rest_day[]" id="rest_day" class="form-control" required value="'+v.rest_day+'">\
                                            </div>\
                                        </div>\
                                    </td>\
                                    <td>\
                                        <button type="button" class="btn btn-danger btn-xs" onclick="remove_rest_day(this, '+v.restday_id+');" title="Remove Row"><i class="fa fa-remove"></i></button>\
                                    </td>\
                            </tr>';

                     $(".tbl_restday tbody").append(row);

                     $('.sys_datepicker .input-group.date').datepicker({
                        todayBtn: "linked",
                        keyboardNavigation: false,
                        forceParse: false,
                        calendarWeeks: true,
                        autoclose: true
                    });
                });
            }
        );
    }

    function add_rest_day() {
        var row = '<tr>\
                        <td>\
                            <div class="form-group sys_datepicker">\
                                <div class="input-group date">\
                                    <input type="hidden" name="restday_id[]">\
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>\
                                    <input type="text" name="rest_day[]" id="rest_day" class="form-control" required value="<?= date("m/d/Y"); ?>">\
                                </div>\
                            </div>\
                        </td>\
                        <td>\
                            <button type="button" class="btn btn-danger btn-xs" onclick="remove_rest_day(this);" title="Remove Row"><i class="fa fa-remove"></i></button>\
                        </td>\
                </tr>';
        $(".tbl_restday tbody").append(row);

        $('.sys_datepicker .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });
    }

    function remove_rest_day(elem, id) {
        if (id != null) {
             $.confirm({
                icon: 'fa fa-warning',
                title: 'Warning',
                content: 'Are you sure you want to delete this?',
                confirmButton: "<span class='fa fa-check'></span> Yes",
                cancelButton: "<span class='fa fa-remove'></span> No",
                confirmButtonClass: "btn btn-sm btn-default",
                cancelButtonClass: "btn btn-sm btn-primary",
                animation: 'rotateX',
                closeAnimation: 'rotateY',
                confirm: function() {
                    $.post(base_url+"user_schedule/schedule/delete_rest_day",
                        { value: id },
                        function(data) {
                            var d = JSON.parse(data);
                            if(d.success == true) {
                                $(elem).closest('tr').remove();
                                tbl_schedule.ajax.reload();
                                toastr.success(d.msg, 'Success');
                            } else {
                                toastr.warning(d.msg, 'Warning');
                            }
                        }
                    );
                },
                cancel: function() { }
            });
        } else {
            $(elem).closest('tr').remove();
        }
    }
</script>