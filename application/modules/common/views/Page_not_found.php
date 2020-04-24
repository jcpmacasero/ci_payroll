<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>404 Error | <?= company_name; ?></title>

	<link rel="icon" type="image/png" href="<?= base_url(company_icon); ?>">

	<link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/plugins/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/plugins/animate/animate.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/plugins/admin_style_default/style.css'); ?>" rel="stylesheet">
</head>
<body class="lockscreen" oncontextmenu="return false">
	<div class="content-wrapper" style="margin:auto;">
		<section class="content">
			<div class="error-page">
				<div class="error-content">
					<center>
						<h2 style="margin-top:5em;" class="text-warning">
							<p style="font-size: 60px;"><?= company_name; ?></p>
							<i class="fa fa-warning"></i> Oops! Page not found.
						</h2>
						<img src="<?= base_url('assets/img/common/404_rhits_number.png'); ?>" width="50%">
					</center>
				</div>
			</div>
		</section>
	</div>
</body>
</html>