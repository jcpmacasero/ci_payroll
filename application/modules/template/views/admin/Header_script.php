<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $page; ?> | <?= company_name; ?></title>

<link rel="icon" type="image/png" href="<?= base_url(company_icon); ?>">

<link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/animate/animate.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/iCheck/custom.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/select2/select2.min.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/jasny/jasny-bootstrap.min.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/sweetalert/sweetalert.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/toastr/toastr.min.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/craftpip/dist/jquery-confirm.min.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/fullcalendar/fullcalendar.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/fullcalendar/fullcalendar.print.css'); ?>" rel='stylesheet' media='print'>
<link href="<?= base_url('assets/plugins/datapicker/datepicker3.css'); ?>" rel="stylesheet">
<link href="<?= base_url('assets/plugins/clockpicker/mdtimepicker.min.css'); ?>" rel="stylesheet">
<?php if ($datatable_script): ?>
	<link href="<?= base_url('assets/plugins/dataTables/dataTables.bootstrap.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/plugins/dataTables/dataTables.responsive.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/plugins/dataTables/dataTables.tableTools.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/plugins/dataTables/datatables.min.css'); ?>" rel="stylesheet">
<?php endif ?>

<?php if ($file_drop_script): ?>
	<link href="<?= base_url('assets/plugins/dropzone/basic.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/plugins/dropzone/dropzone.css'); ?>" rel="stylesheet">	
	<link href="<?= base_url('assets/plugins/codemirror/codemirror.css'); ?>" rel="stylesheet">
<?php endif ?>

<link href="<?= base_url('assets/plugins/admin_style_default/style.css'); ?>" rel="stylesheet">

<style type="text/css">
	[class^='select2'] { border-radius: 0px !important; border-color: #d9d9d9 !important; font-size: 14px;}
	.select2-close-mask{ z-index: 2099; }
	.select2-dropdown{ z-index: 3051; }
	.notiny-theme-inspinia { background-color: #f8ac59; border-color: #f8ac59 !important; color: #FFFFFF; }
	.notiny-theme-light { background-color: #f8ac59; border-color: #f8ac59 !important; color: #000000; }
	.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
	.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; font-size: 14px;}
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: normal; color: #1ab394; font-size: 18px;}
	.autocomplete-group { padding: 2px 5px; }
	.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
	.dhxcombo_material { border: 1px solid #d9d9d9; z-index: 99999; width: 100% !important;}
	.dhxcombolist_material { z-index: 4000 !important; width: 300px !important;}
	.dhxcombo_input { width: 95% !important; }

	.vertical-middle { vertical-align: middle !important; }

	.md-skin .ibox-title {
		border-bottom: 1px solid #e7eaec !important;
	}

	.float-e-margins .btn {
	    margin-bottom: 5px !important;
	}

	.b-r {
		border-color: #1ab394;
	}

	.fieldset {
	    border: 1px solid #23c6c8 !important;
	    margin: 0;
	    min-width: 0;
	    padding: 10px 10px 0px 10px;       
	    position: relative;
	    border-radius:4px;
	}  

	.fieldset .legend {
		color: #fff;
	    font-size:14px;
	    font-weight:bold;
	    margin-bottom: 0px; 
	    width: 50%; 
	    border: 1px solid #ddd;
	    border-radius: 4px; 
	    padding: 5px 5px 5px 10px; 
	    background-color:#23c6c8;
	}

	.fieldset .legend-with-btn {
	    width: 35%;
	    border-radius: 4px; 
	    background-color:#f5f5f5;
	    margin-bottom: 0px;
	    font-weight:bold;
	    border: 1px solid #ddd;
	}

	.slimScrollBar {
		width: 10px !important;
	}

	#toast-container .toast-success {
		border: 2px solid #009688;
	}

	table.table thead th {
		vertical-align: bottom;
	}

	.slimScrollBar {
		display: block !important;
		width: 10px !important;
	}
</style>

<script src="<?= base_url('assets/plugins/fullcalendar/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/jquery/jquery-2.1.1.js'); ?>"></script>

<script type="text/javascript">
    var permission = [[],[]];
    var login_name = "<?= login_name(); ?>";
    var login_date = "<?= login_date(); ?>";
    var base_url = "<?= base_url(); ?>";
    var js_date = new Date();
    var company_name = "<?= company_name; ?>";
</script>