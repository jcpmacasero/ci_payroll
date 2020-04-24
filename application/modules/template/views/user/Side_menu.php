<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style type="text/css">
    .md-skin.fixed-nav #side-menu{
        background: transparent;
    }
    .md-skin .nav-header {
        background: url(<?= base_url('assets/plugins/admin_style_default/patterns/header-profile.png'); ?>) !important;
    }
    .md-skin .navbar-default {
        background-color: transparent !important;
    }
    .md-skin .navbar-default .nav > li > a:hover, 
    .md-skin .navbar-default .nav > li > a:focus {
        background-color: #293846;
    }
    .md-skin .nav > li.active {
        background-color: #293846;
    }
    .md-skin .navbar-default .nav > li.active > a {
        color: #FFFFFF !important;
    }
    .md-skin .navbar-default .nav > li > a:hover, 
    .md-skin .navbar-default .nav > li > a:focus {
        background-color: #293846 !important;
        color: #FFFFFF !important;
    }
    .md-skin .nav > li > a {
        font-weight: 600;
        color: #a7b1c2;
    }

    /*.md-skin .li_clock {
        background: #f8ac59;
        padding: 10px 10px 1px 10px;
    }*/
    .md-skin .li_clock .content{
        padding: 0px;
        border: 5px solid #19aa8d;
    }
    .md-skin .li_clock .admin-content{
        border: 5px solid rgba(204, 33, 44, 1) !important;
    }
    .md-skin  .li_clock .content p.days span{
        font-size: 12px;
    }
    .md-skin .li_clock .content p.days span:first-child {
        color: #B80000;
    }
    .md-skin .li_clock .content p.days span:first-child.days_active {
        color: #FF0000;
    }
    .md-skin .li_clock .content p.days span:not(:first-child) {
        color: #999c9e;
    }
    .md-skin .li_clock .content p.days span:not(:first-child).days_active {
        color: #FFFFFF;
    }
    .md-skin .li_clock .content p.days span:not(:last-child){
        margin-right: 2px;
    }
    .days_active {
        font-weight: bolder;
    }
    .md-skin .li_clock .content p.time {
        font-size: 1.5em;
        color: #FFFFFF;
    }
    .md-skin .li_clock .content p.date{
        color: #FFFFFF;
    }
    .md-skin .li_clock a:hover {
        background: transparent;
    }
</style>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header" style="text-align: center; padding: 30px 10px;">
                <div class="dropdown profile-element">
                    <span>
                        <a href="<?= base_url('user/dashboard'); ?>">
                            <img alt="image" style="width:70%; border-radius: 3%;" class="animated pulse" src="<?= base_url(company_logo); ?>"/>
                        </a>
                    </span>
                </div>

                <div class="logo-element">
                    <?= company_name; ?>
                </div>
            </li>

            <li class="li_clock">
                <div href="javascript:;" class="content">
                    <p class="days text-center">
                        <span id="sun">SUN</span>
                        <span id="mon">MON</span>
                        <span id="tus">TUE</span>
                        <span id="wed">WED</span>
                        <span id="thu">THU</span>
                        <span id="fri">FRI</span>
                        <span id="sat">SAT</span>
                    </p>

                    <p class="time text-center">
                        <span id="hours"></span> : <span id='mins'></span> <span id='am_pm'></span>
                    </p>

                    <p class="date text-center">
                        <span id="month"></span>
                        <span id="day"></span>
                        <span id="year"></span>
                    </p>
                </div>          
            </li>

            <li id="lnk_Dashboard" class="<?= $this->uri->segment(2) == "dashboard" ? "active" : ""; ?>">
                <a href="<?= base_url('user/dashboard'); ?>"><i class="fa fa-dashboard"></i> <span class="nav-label">Dashboard</span></a>
            </li>

            <li id="lnk_Schedules" class="<?= $this->uri->segment(2) == "schedule" ? "active" : ""; ?>">
                <a href="<?= base_url('user/schedule'); ?>"><i class="fa fa-calendar"></i> <span class="nav-label">Schedules</span></a>
            </li>

            <li id="lnk_DutyRest" class="<?= $this->uri->segment(2) == "duty_rest" ? "active" : ""; ?>">
                <a href="<?= base_url('user/duty_rest'); ?>"><i class="fa fa-cutlery"></i> <span class="nav-label">Duty Rest</span></a>
            </li>

            <li id="lnk_Overtime" class="<?= $this->uri->segment(2) == "overtime" ? "active" : ""; ?>">
                <a href="<?= base_url('user/overtime'); ?>"><i class="fa fa-clock-o"></i> <span class="nav-label">Overtime</span></a>
            </li>
        </ul>
    </div>
</nav>

<script type="text/javascript" src="<?= base_url('assets/js/common/side_menu_clock.js'); ?>"></script>