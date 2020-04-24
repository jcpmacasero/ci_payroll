<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style type="text/css">
    .md-skin .nav.navbar-right > li > a i.fa.fa-envelope:hover,
    .md-skin .nav.navbar-right > li.open > a i.fa.fa-envelope,
    .md-skin .nav.navbar-right > li > a i.fa.fa-bell:hover,
    .md-skin .nav.navbar-right > li.open > a i.fa.fa-bell {
        color: #940710 !important;
    }
    .admin-navbar-minimalize {
        background-color: rgba(204, 33, 44, 1) !important;
    }
</style>

<div class="row border-bottom">
    <nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom:0;">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <!-- <li class="dropdown" onclick="show_notifications()" name="view_notification">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" title="Notifications">
                    <i class="fa fa-bell"></i>  <span id="count_notif"></span>
                </a>
                <ul class="dropdown-menu dropdown-alerts" id="notifications"></ul>
            </li> -->

            <li><img src="<?= login_photo(); ?>" style="width: 30px; border-radius: 50%;"></li>

            <li>
                <a href="javascript:;">
                    <?= login_name(); ?>
                </a>
            </li>

            <li>
                <a href="<?= base_url('admin_request_logout'); ?>">
                    <i class="fa fa-sign-out"></i> Log Out
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- < ?php $this->load->view('notification_head/Notification_head'); ?> -->