<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style type="text/css">
    .md-skin .navbar-default .nav > li > a {
        color: #36607f !important;
    }
    .md-skin .navbar-default .nav > li.active > a {
        color: #1ab394 !important;
    }

    /*.md-skin .li_clock {
        background: #f8ac59;
        padding: 10px 10px 1px 10px;
    }*/
    .md-skin .li_clock .content{
        background: #11C2A1;
        padding: 0px;
        border: 5px solid #31d2aa;
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
        color: #333;
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
                        <a href="<?= base_url('admin/dashboard'); ?>">
                            <img alt="image" style="width:70%; border-radius: 3%;" class="animated pulse" src="<?= base_url(company_logo); ?>"/>
                        </a>
                    </span>
                </div>

                <div class="logo-element">
                    <?= company_name; ?>
                </div>
            </li>

            <li class="li_clock">
                <div href="javascript:;" class="content" style="border: 5px solid #31d2aa;">
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
                <a href="<?= base_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <span class="nav-label">Dashboard</span></a>
            </li>
            <li id="lnk_Structure" class="<?= $this->uri->segment(2) == "department" ? "active" : ""; ?>">
                <a href="#"><i class="fa fa-window-maximize"></i> <span class="nav-label">Company Structure<span class="fa arrow"></span></span></span></a>
                <ul class="nav nav-second-level collapse">
                        <li><a href="<?= base_url('admin/structure-dept'); ?>">Department List</a></li>
                        <li><a href="<?= base_url('admin/structure-salary'); ?>">Salary Grades</a></li>
                        <li><a href="<?= base_url('admin/structure-position'); ?>">Position List</a></li>
                        <!-- <li><a href="<?= base_url('admin/user_permission'); ?>">Permissions</a></li> --> 
                </ul>
            </li>
            <li id="lnk_Employee" class="<?= $this->uri->segment(2) == "employee" ? "active" : ""; ?>">
                <a href="<?= base_url('admin/employee'); ?>"><i class="fa fa-users"></i> <span class="nav-label">Employee</span></a>
            </li>            
            <li id="lnk_Calendar" class="<?= $this->uri->segment(2) == "calendar" ? "active" : ""; ?>">
                <a href="<?= base_url('admin/calendar'); ?>"><i class="fa fa-calendar"></i> <span class="nav-label">Calendar</span></a>
            </li>
            <li id="lnk_Additional" class="<?= $this->uri->segment(2) == "addition" ? "active" : ""; ?>">
                <a href="#"><i class="fa fa-plus"></i> <span class="nav-label">Additionals</span><span class="fa arrow"></span></span></a>
                <ul class="nav nav-second-level collapse">
                        <li><a href="<?= base_url('admin/additionals'); ?>">Additional List</a></li>
                        <li><a href="<?= base_url('admin/additionals-tagging'); ?>">Additional Tagging</a></li>
                </ul>
            </li>
            <li id="lnk_Deduction" class="<?= $this->uri->segment(2) == "deduction" ? "active" : ""; ?>">
                <a href="#"><i class="fa fa-minus"></i> <span class="nav-label">Deductions</span><span class="fa arrow"></span></span></a>
                <ul class="nav nav-second-level collapse">
                        <li><a href="<?= base_url('admin/deductions'); ?>">Deduction List</a></li>
                        <li><a href="<?= base_url('admin/deductions-tagging'); ?>">Deduction Tagging</a></li>
                </ul>
            </li>            
            <li id="lnk_Leave" class="<?= $this->uri->segment(2) == "leaves" ? "active" : ""; ?>">
                <a href="#"><i class="fa fa-scissors"></i> <span class="nav-label">Leaves</span><span class="fa arrow"></span></span></a>
                <ul class="nav nav-second-level collapse">
                        <li><a href="<?= base_url('admin/leave-create'); ?>">Leave List</a></li>
                        <li><a href="<?= base_url('admin/leave-apply'); ?>">Apply Leave</a></li>
                        <!-- <li><a href="#">Employees Leave History</a></li> -->
                </ul>
            </li>
            <li id="lnk_Payroll" class="<?= $this->uri->segment(2) == "payroll" ? "active" : ""; ?>">
                <a href="#"><i class="fa fa-calculator"></i> <span class="nav-label">Payroll</span><span class="fa arrow"></span></span></a>
                <ul class="nav nav-second-level collapse">
                        <li><a href="<?= base_url('admin/dtr'); ?>">Daily Time Record</a></li>
                        <li><a href="<?= base_url('admin/calculate_payroll'); ?>">Calculate Payroll</a></li>
                        <li><a href="<?= base_url('admin/view_payroll'); ?>">View Payroll</a></li>
                        <li><a href="<?= base_url('admin/history_payroll'); ?>">Payroll History</a></li>
                        <li><a href="<?= base_url('admin/file_upload'); ?>">File Upload</a></li>
                        <!-- <li><a href="<?= base_url('admin/time_attendance'); ?>">Time and Attendance</a></li> -->                        
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script type="text/javascript" src="<?=base_url('assets/js/common/side_menu_clock.js');?>"></script>