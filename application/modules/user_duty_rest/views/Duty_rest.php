<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('user_duty_rest/Table'); ?>
<?php $this->load->view('user_duty_rest/Modal_form'); ?>

<script type="text/javascript">
	var users = <?= $users; ?>;
	$(function() {
		select2_with_format(".user_id", users, user_select_format); 
	});

	function user_select_format(state) {
        if (!state.id) { return state.text; }
        var $state = $(
            "<table style='width: 100%; font-size: 12px;'>\
                <tr>\
                    <td style='width: 40%; padding: 10px; vertical-align: text-top;'>"+state.col1+"</td>\
                    <td style='width: 60%; padding: 10px; vertical-align: text-top;'>"+( state.col2 != null ? state.col2:"" )+"</td>\
                </tr>\
            </table>"
        );
        return $state;
    }
</script>