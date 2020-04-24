<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= $page_title; ?> | <?= company_name; ?></title>
    <link rel="icon" type="image/png" href="<?= base_url(company_icon); ?>">

    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/animate/animate.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/admin_style_default/style.css'); ?>" rel="stylesheet">
    <style type="text/css">
        .form-control{
            font-size: 10pt;
        }
        .input:focus {
            outline-width: 0;
        }
        .login-form {
            margin-top: 5%;
        }
        .login-btn {
            background: transparent;
            background-color: #FFF;
            border: 0px;
            color: #00A876;
            font-size: 14px;
            border-radius: 2px;
            padding: 6px;
            width: 120px;
            margin-top: 20px;
            font-weight: bold;
        }
        .login-btn:hover {
            background-color: #151A22;
        }
        .gh-group {
            bottom: 0;
            text-align: center; 
            width: 100%;
            margin-top: 20%;
            font-size: 11px;
        }
        .invalid {
            color: #FFF;
            font-weight: bold;
        }
        .logo{
            width: 50%;
        }

        .form-control{
            background: rgba(255,255,255,0.9) !important;
        }
        .form-control::before{
            color:#fff !important;
        }
        .form-control::placeholder{
            color: silver;
        }
        .account-box {
            box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22); 
            background: rgba(255,255,255, 1);
            width: 100%;
            height: 100vh;
            padding:20px 20px 20px 20px;
        }
        .col-sm-8, .col-sm-4 {
            padding: 0px;
        }
        img {
            background-color: rgba(0,0,0,0.6) !important;
        }
        .carousel-control {
            color:white !important;
        }
    </style>

    <script type="text/javascript" src="<?= base_url('assets/plugins/jquery/jquery-2.1.1.js'); ?>"></script>
</head>
<body class="gray-bg" oncontextmenu="return true">
   <div class="row">
        <div class="col-sm-8">
            <div class="carousel slide" id="carousel2" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li♦ data-slide-to="0" data-target="#carousel2"  class="active"></li>
                    <l1i data-slide-to="0" data-target="#carousel2"  class=""></li>
                </ol>
                <div class="carousel-inner">
                    <div class="item active text-center">
                        <img style="width: 100%; height: 736px;" src="<?= base_url('assets/img/common/payroll_2.webp'); ?>">
                        <div class="carousel-caption">
                            <p></p>
                        </div>
                    </div>
                    <div class="item text-center">
                        <img style="width: 100%; height: 736px;" src="<?= base_url('assets/img/common/payroll_1.jpg'); ?>">
                        <div class="carousel-caption">
                            <p></p>
                        </div>
                    </div>
                </div>
                <a data-slide="prev" href="#carousel2" class="left carousel-control">
                    <span class="icon-prev"></span>
                </a>
                <a data-slide="next" href="#carousel2" class="right carousel-control">
                    <span class="icon-next"></span>
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="account-box animated fadeInDown">
                <div class="logo text-center" style="margin: 0 auto; margin-bottom: 20px; padding-top:10px;
                padding-bottom:20px;">
                    <img class="animated bounceIn" style=" margin: 0 auto; height:200px; width:200px; border:2px solid gray; border-radius: 50%;" src="<?= base_url(company_logo); ?>">
                    <h2><?=company_name;?></h2>
                </div>
                <?= form_open(base_url("admin_request_login")); ?>
                    <?php if ($this->input->get("login_attempt") == md5(0)): ?>
                        <p class='text-danger text-center'>Invalid Username or Password. Please try again.</p>
                    <?php elseif ($this->input->get("login_attempt") == md5(1)): ?>
                        <p class='text-danger text-center'>Invalid Username or Password. Please try again.</p>
                    <?php elseif ($this->input->get("login_attempt") == md5(2)): ?>
                        <p class="text-danger text-center" style="line-height: 17px;">Sorry, you are block in this website! <br> For more information. <br> Please contact admistrator.</p>
                    <?php endif ?>

                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" name="email" required autofocus />
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="password" required />
                    </div>     
                    <button class="btn btn-md btn-block btn-primary" type="submit">
                        <span class="glyphicon glyphicon-log-in"></span> Sign in
                    </button>
                </form>
                <p class="pull-right">&copy; <?= company_name; ?> <?= date("Y") ?></p>
                <a class="forgotLnk" href="#modal_contact_admin" data-toggle="modal">I can't access my account</a>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="modal_contact_admin" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content animated shake">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <i class="fa fa-exclamation-triangle modal-icon text-warning"></i>
                    <!-- <h4 class="modal-title">Contact Admin Window</h4> -->
                </div>
                <div class="modal-body text-center">
                    <h3 class="text-warning"><strong>Please contact the admistrator. <br> Thank you!</strong></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
</body>
</html>