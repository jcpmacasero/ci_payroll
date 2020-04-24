$(function() {	
	$('.i-checks').iCheck({
	    checkboxClass: 'icheckbox_square-green',
	    radioClass: 'iradio_square-green',
	});

	$('.sys_datepicker .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true
    });

	$('.clockpicker').mdtimepicker({ format: 'h:mm:ss tt', hourPadding: true });

	check_user_permission();
	welcome_message();
});

function check_user_permission() {
	if(permission != null) {
		for(var x = 0; x < permission[0].length; x++) {
			if(permission[0][x].button_code == "view_page") {
				if(permission[0][x].status == 0) {
					location.replace(base_url);
				}
			} else {
				if(permission[0][x].status == 0) {
					$('[name='+permission[0][x].button_code+']').remove();
				}
			}
		}
		for(var x = 0; x < permission[1].length; x++) {
			if(permission[1][x].status == 0) {
				$('#lnk_'+permission[1][x].module_name).remove();
			}
		}
	}

    if ($('#lnk_Reports ul li.lnk_access').length == 0) {
        $("#lnk_Reports").remove();
    }
    if ($('#lnk_References ul li.lnk_access').length == 0) {
        $("#lnk_References").remove();
    }
    if ($('#lnk_History ul li.lnk_access').length == 0) {
        $("#lnk_History").remove();
    }
}

function welcome_message() {
	var new_login_date = new Date(login_date);

	var month = new_login_date.getMonth()+1;
	var day = new_login_date.getDate();
	var year = new_login_date.getFullYear();

	var hour = check_time(new_login_date.getHours());
	var minute = check_time(new_login_date.getMinutes());
	var second = check_time(new_login_date.getSeconds()+5);

	var time = hour + ":" + minute + ":" + second;
	var fullyear = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;

	var good_message = "";

	if (date('fulltime') <= fullyear+' '+time) {
		if (time >= '00:00:00' && time <= '11:59:59') {
			good_message = "Good Morning";
		} else if (time >= '12:00:00' && time <= '12:59:59') {
			good_message = "Good Noon";
		} else if (time >= '13:00:00' && time <= '17:59:59') {
			good_message = "Good Afternoon";
		} else if (time >= '18:00:00' && time <= '23:59:59') {
			good_message = "Good Evening";
		}

		setTimeout(function() {

		    toastr.options = {
		        closeButton: true,
		        progressBar: true,
		        showMethod: 'slideDown',
		        timeOut: 10000
		    };
		    toastr.warning(good_message+' '+login_name, 'Welcome to '+company_name);

		}, 1300);

	}

}

// $( document ).idleTimer( 300000 );
// $( document ).on( "idle.idleTimer", function(event, elem, obj){
//        toastr.options = {
//            "positionClass": "toast-top-right",
//            "timeOut": 8000
//        }

//        toastr.warning('You are about to logout after 1 minute if you not moving your mouse.','Warning');
//        $('.custom-alert').fadeIn();
//        $('.custom-alert-active').fadeOut();

//        setTimeout(function(){
//        	location.replace("< ?= base_url('lockscreen') ?>");
//        },60000);
//    });

$( document ).on( "active.idleTimer", function(event, elem, obj, triggerevent){
    // function you want to fire when the user becomes active again
    toastr.clear();
    $('.custom-alert').fadeOut();
    toastr.success('Great that you decided to move your mouse.','You are back. ');
});

function select2(id, data) {
	$(id).select2({
		placeholder: "Select",
		allowClear: true,
		data: data
	});
}

function select2_with_format(id, data, format) {
	$(id).select2({
        placeholder: "Search",
        allowClear: true,
        templateResult: format,
        dropdownAutoWidth: true,
        data: data
    });
}

function select2_server_side(id, url) {
	$(id).select2({
        placeholder: "Search",
        allowClear: true,
        ajax: {
            url: base_url+url,
            type: "POST",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                }
            },
            processResults: function (data) {
                return {
                    results: data
                }
            },
            cache: true
        }
    });
}

function form_validator() {
	$("form").attr({
		'data-toggle' : 'validator',
		'role' : 'form'
	});
	
	$("form").validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
            swal({
                html: true,
                title: "Warning",
                text:"<div class='text-danger'>You missed fields that are required. They have been highlighted</div>",
                type:"warning"
            });
        }
    });
}

function clear_form(form_id) {
	$("#"+form_id)[0].reset();
	$("#"+form_id).find("input[type='hidden']").each(function() {
		$(this).val("");
	});
	$("#"+form_id).find("input[type='checkbox']").each(function() {
		$(this).attr("checked", false);
	});
	$(".select2").val("").trigger("change");
}

function ajax_form(form_name, button_name, tbl_name) {
	$("#"+form_name).ajaxForm({
		clearForm	: false,
		resetForm: false,
		beforeSubmit: function() {
			$("#"+button_name).attr("disabled", true);
			$("#"+button_name).html("<span class=\"fa fa-spinner fa-pulse\"></span>");
		},
		success: function(data) {
			var d = JSON.parse(data);
			if(d.success == true) {
				toastr.success(d.msg, 'Success');
				$("#"+button_name).attr("disabled", false);
				$("#"+button_name).html("<span class=\"fa fa-check\"></span> Save");
				$(".close").click();
				for(var x=0; x < tbl_name.length; x++) {
					$("#"+tbl_name[x]).DataTable().ajax.reload();
			    }
			} else {
				toastr.warning(d.msg, 'Warning');
				$("#"+button_name).attr("disabled", false);
				$("#"+button_name).html("Save");
			}
		}
	});
}

function get_info(url, value, form_id) {
	$.post(base_url + url, 
		{ value: value }, 
		function(data) {
			var result = JSON.parse(data);
			$.each(result, function(k, v) {
				$("#"+form_id).each(function() {
					$("[name="+k+"]").val(v);
					$(".select2").trigger("change");
				});
			});
		}
	);
}

function delete_this(url, value, tbl) {
	$.confirm({
		icon: 'fa fa-warning',
		title: 'Warning',
		content: 'Are you sure you want to delete this?',
		confirmButton: "<span class='fa fa-check'></span> Yes",
		cancelButton: "<span class='fa fa-remove'></span> No",
		confirmButtonClass: "btn btn-sm btn-default",
		cancelButtonClass: "btn btn-sm btn-primary",
		animation: 'rotateY',
		closeAnimation: 'rotateY',
		confirm: function() {
			$.post(base_url + url,
				{ value: value }, 
				function(data) {
					var d = JSON.parse(data);
					if(d.success == true) {
					    toastr.success(d.msg, 'Success');
					    for(var x=0; x < tbl.length; x++) {
					    	tbl[x].ajax.reload();
					    }
					} else {
					    toastr.warning(d.msg, 'Warning');
					}
				}
			);
		},
		cancel: function() {}
	});
}

function number_format(n) {
	return n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
}

function date(value){
	var d = new Date();

	var month = d.getMonth()+1;
	var day = d.getDate();
	var year = d.getFullYear();

	var hour = check_time(d.getHours());
	var minute = check_time(d.getMinutes());
	var second = check_time(d.getSeconds());

	var time = hour + ":" + minute + ":" + second;

	var fullyear = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;

	var fulltime = fullyear + ' ' + time;

	var output = '';

	if (value == 'month') {
		output = month;
	} else if (value == 'day') {
		output = day;
	} else if (value == 'year') {
		output = year;
	} else if (value == 'year') {
		output = year;
	} else if (value == 'hour') {
		output = hour;
	} else if (value == 'minute') {
		output = minute;
	} else if (value == 'second') {
		output = second;
	} else if (value == 'time') {
		output = time;
	} else if (value == 'fullyear') {
		output = fullyear;
	} else if (value == 'fulltime') {
		output = fulltime;
	}

	return output;
}

function check_time(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}