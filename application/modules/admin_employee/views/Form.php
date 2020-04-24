<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
    
    .admin_img_width img {
        width: 150px;
    }

</style>

<div class="col-sm-12">
    <?php 
        $attributes = ["id" => "form_employee"]; 
        $hidden = [
            "user_id" => !empty($employee_info) ? $employee_info["user_id"] : "",
            "family_background_id" => !empty($employee_info) ? $employee_info["family_background_id"] : "",
            "spouse_id" => !empty($employee_info) ? $employee_info["spouse_id"] : ""
        ];
    ?>

    <?= form_open_multipart(base_url("admin_employee/employee/insert_employee"), $attributes, $hidden); ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-info">
                    <div class="panel-body text-center">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumb_zoom zoom admin_img_width">
                                <?php if (!empty($employee_info)): ?>
                                    <img src="<?= base_url($employee_info["photo"]); ?>" class="" alt="admin" id="image" class="admin_img_width" style="width: 150px;">
                                <?php else: ?>
                                    <img src="<?= default_profile_image(); ?>" class="" alt="admin" class="admin_img_width" style="width: 150px;">
                                <?php endif ?>
                            </div>

                            <div class="fileinput-preview fileinput-exists thumb_zoom zoom admin_img_width"></div>

                            <div class="btn_file_position m-t-sm">
                                <span class="btn btn-primary btn-file btn-outline">
                                    <span class="fileinput-new">Upload Picture</span>
                                    <span class="fileinput-exists">Change</span>
                                    <input type="file" name="user_picture" id="user_picture">
                                </span>

                                <a href="#" class="btn btn-warning fileinput-exists btn-outline" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <fieldset class="fieldset" style="padding-bottom: 20px;">
                    <legend class="legend">Personal Information</legend>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="employee_id" class="control-label">User ID <span class="text-danger">*</span></label>
                                <input type="text" name="employee_id" class="form-control input-sm" id="employee_id" required value="<?= !empty($employee_info) ? html_escape($employee_info["employee_id"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_status" class="control-label">User Status <span class="text-danger">*</span></label>
                                <select name="user_status" class="form-control input-sm" id="user_status" required>
                                    <option value="">Select</option>
                                    <option value="PENDING">PENDING</option>
                                    <option value="ACTIVATED">ACTIVATED</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="firstname" class="control-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="firstname" class="form-control input-sm text-capitalize" id="firstname" required value="<?= !empty($employee_info) ? html_escape($employee_info["firstname"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="middlename" class="control-label">Middle Name</label>
                                <input type="text" name="middlename" class="form-control input-sm text-capitalize" id="middlename" value="<?= !empty($employee_info) ? html_escape($employee_info["middlename"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lastname">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="lastname" class="form-control input-sm text-capitalize" id="lastname" required value="<?= !empty($employee_info) ? html_escape($employee_info["lastname"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name_ext" class="control-label">Name Extension</label>
                                <select name="name_ext" id="name_ext" class="form-control input-sm">
                                    <option value="">Select Extension</option>
                                    <option value="Jr">Jr.</option>
                                    <option value="Sr">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="gender" class="control-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control input-sm" id="gender" required>
                                    <option value="">Select</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                    <option value="N">Not Specified</option>
                                </select>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="contact_number" class="control-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" name="contact_number" class="form-control input-sm" id="contact_number" required value="<?= !empty($employee_info) ? html_escape($employee_info["contact_no"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email" class="control-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control input-sm" id="email" required value="<?= !empty($employee_info) ? html_escape($employee_info["email"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password" id="lbl_password" class="control-label">Password</label>
                                <input type="password" name="password" class="form-control input-sm" id="password">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="confirm_password" id="lbl_confirm_password" class="control-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control input-sm" id="confirm_password">
                            </div>
                        </div>

                        <div class="col-sm-12 m-t-sm">
                            <div class="panel panel-info">
                                <div class="panel-body">
                                    <h3>Permanent address</h3>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="province_id" class="control-label">Province <span class="text-danger">*</span></label>
                                                <select name="province_id" class="form-control input-sm" id="province_id" required>
                                                    <option value="">Select</option>
                                                    <?php foreach ($provinces as $key => $value): ?>
                                                        <option value="<?= $value->province_id; ?>"><?= html_escape($value->province_name); ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="city_id" class="control-label">City <span class="text-danger">*</span></label>
                                                <select name="city_id" class="form-control input-sm" id="city_id" required>
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="street_name" class="control-label">House no. / Street name <span class="text-danger">*</span></label>
                                                <input type="text" name="street_name" class="form-control input-sm" id="street_name" required value="<?= !empty($employee_info) ? html_escape($employee_info["street_address"]) : ""; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="philhealth_no" class="control-label">Philhealth Number <span class="text-danger">*</span></label>
                                <input type="text" name="philhealth_no" class="form-control input-sm" id="philhealth_no" required value="<?= !empty($employee_info) ? html_escape($employee_info["philhealth_no"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pagibig_no" class="control-label">Pag-ibig Number <span class="text-danger">*</span></label>
                                <input type="text" name="pagibig_no" class="form-control input-sm" id="pagibig_no" required value="<?= !empty($employee_info) ? html_escape($employee_info["pag_ibig_no"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tin_no" class="control-label">TIN Number <span class="text-danger">*</span></label>
                                <input type="text" name="tin_no" class="form-control input-sm" id="tin_no" required value="<?= !empty($employee_info) ? html_escape($employee_info["tin_no"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dependent_children" class="control-label">Number of Dependent Child <span class="text-danger">*</span></label>
                                <input type="text" name="dependent_children" class="form-control input-sm" id="dependent_children" required value="<?= !empty($employee_info) ? html_escape($employee_info["philhealth_no"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sss_no" class="control-label">SSS Number <span class="text-danger">*</span></label>
                                <input type="text" name="sss_no" class="form-control input-sm" id="sss_no" required value="<?= !empty($employee_info) ? html_escape($employee_info["sss_no"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="place_of_birth" class="control-label">Place of Birth <span class="text-danger">*</span></label>
                                <input type="text" name="place_of_birth" class="form-control input-sm" id="place_of_birth" required value="<?= !empty($employee_info) ? html_escape($employee_info["place_of_birth"]) : ""; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group sys_datepicker">
                                <label for="birthdate" class="control-label">Birthdate <span class="text-danger">*</span></label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="birthdate" id="birthdate" class="form-control input-sm" value="<?= !empty($employee_info) ? html_escape($employee_info["birthdate"]) : date("m/d/Y"); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="religion_id" class="control-label">Religion <span class="text-danger">*</span></label>
                                <select name="religion_id" class="form-control input-sm" id="religion_id" required>
                                    <option value="">Select</option>
                                    <?php foreach ($religions as $key => $value): ?>
                                        <option value="<?= $value->religion_id; ?>"><?= html_escape($value->religion_name); ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="civil_status" class="control-label">Civil Status <span class="text-danger">*</span></label>
                                <select name="civil_status" class="form-control input-sm" id="civil_status" required>
                                    <option>Select Option</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Legally Separated">Legally Separated</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="citizenship_id" class="control-label">Citizenship <span class="text-danger">*</span></label>
                                <select name="citizenship_id" class="form-control input-sm" id="citizenship_id" required>
                                    <option value="">Select</option>
                                    <?php foreach ($citizenships as $key => $value): ?>
                                        <option value="<?= $value->citizenship_id; ?>"><?= html_escape($value->citizenship_name); ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="department_id" class="control-label">Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-control input-sm" id="department_id" required>
                                    <option value="">Select</option>
                                    <?php foreach ($departments as $key => $value): ?>
                                        <option value="<?= $value->department_id; ?>"><?= html_escape($value->department_name); ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="position_id" class="control-label">Position <span class="text-danger">*</span></label>
                                <select name="position_id" class="form-control input-sm" id="position_id" required></select>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="col-sm-12 m-t-md">
                <fieldset class="fieldset" style="padding-bottom: 20px;">
                    <legend class="legend">Family Background</legend>

                    <div class="panel panel-info">
                        <div class="panel-body">
                            <h3>Father's Info</h3>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="fathers_name" class="control-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="fathers_name" class="form-control input-sm text-capitalize" id="fathers_name" required value="<?= !empty($employee_info) ? html_escape($employee_info["fathers_name"]) : ""; ?>">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="fathers_occupation" class="control-label">Occupation <span class="text-danger">*</span></label>
                                        <input type="text" name="fathers_occupation" class="form-control input-sm text-capitalize" id="fathers_occupation" required value="<?= !empty($employee_info) ? html_escape($employee_info["fathers_occupation"]) : ""; ?>">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group sys_datepicker">
                                        <label for="fathers_birthdate" class="control-label">Date of birth <span class="text-danger">*</span></label>
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" name="fathers_birthdate" id="fathers_birthdate" class="form-control input-sm" value="<?= !empty($employee_info) ? html_escape($employee_info["fathers_birthdate"]) : date("m/d/Y"); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-body">
                            <h3>Mother's Info</h3>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="mothers_name" class="control-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="mothers_name" class="form-control input-sm text-capitalize" id="mothers_name" required value="<?= !empty($employee_info) ? html_escape($employee_info["mothers_name"]) : ""; ?>">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="mothers_occupation" class="control-label">Occupation <span class="text-danger">*</span></label>
                                        <input type="text" name="mothers_occupation" class="form-control input-sm text-capitalize" id="mothers_occupation" required value="<?= !empty($employee_info) ? html_escape($employee_info["mothers_occupation"]) : ""; ?>">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group sys_datepicker">
                                        <label for="mothers_birthdate" class="control-label">Date of birth <span class="text-danger">*</span></label>
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" name="mothers_birthdate" id="mothers_birthdate" class="form-control input-sm" value="<?= !empty($employee_info) ? html_escape($employee_info["mothers_birthdate"]) : date("m/d/Y"); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info" id="div_spouse" style="display: none;">
                        <div class="panel-body">
                            <h3>Spouse Info</h3>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="spouse_name" class="control-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="spouse_name" class="form-control input-sm text-capitalize" id="spouse_name" value="<?= !empty($employee_info) ? html_escape($employee_info["spouse_name"]) : ""; ?>">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="spouse_occupation" class="control-label">Occupation <span class="text-danger">*</span></label>
                                        <input type="text" name="spouse_occupation" class="form-control input-sm text-capitalize" id="spouse_occupation" value="<?= !empty($employee_info) ? html_escape($employee_info["spouse_occupation"]) : ""; ?>">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group" id="div_spouse_birthdate">
                                        <label for="spouse_birthdate" class="control-label">Date of birth <span class="text-danger">*</span></label>
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" name="spouse_birthdate" id="spouse_birthdate" class="form-control input-sm" value="<?= !empty($employee_info) ? html_escape($employee_info["spouse_birthdate"]) : date("m/d/Y"); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="col-sm-12 m-t-md">
                <fieldset class="fieldset" style="padding-bottom: 20px;">
                    <legend class="legend">Educational Background</legend>

                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-sm btn-primary" onclick="add_field_educ()">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <div class="col-sm-12" id="div_educ">
                            <?php if (!empty($employee_educ_info)): ?>
                                <?php foreach ($employee_educ_info as $key => $value): ?>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="hidden" name="educational_background_id[]" value="<?= $value['educational_background_id']; ?>">
                                                <select name="educ_opt[]" class="form-control input-sm" id="educ_opt<?= $value['educational_background_id']; ?>">
                                                    <option value="">Select</option>
                                                    <option value="Elem">Elementary</option>
                                                    <option value="Sec">Secondary</option>
                                                    <option value="Bac">Baccalaureate</option>
                                                    <option value="Post">Postgraduate</option>
                                                    <option value="Doc">Doctorate</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="text" name="name_of_school[]" class="form-control input-sm text-capitalize" placeholder="Name of School" required value="<?= $value['name_of_school']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group sys_datepicker">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="year_attended[]" class="form-control input-sm" value="<?= html_escape($value["date_attended"]); ?>" required <?= empty($value["date_attended"]) ? 'placeholder="Year Attended"' : ''; ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group sys_datepicker">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="year_graduated[]" class="form-control input-sm" value="<?= html_escape($value["date_graduated"]); ?>" required <?= empty($value["date_graduated"]) ? 'placeholder="Year Graduated"' : ''; ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="remove_field_educ(this, <?= $value['educational_background_id']; ?>)"><i class="fa fa-remove"></i></button>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        $("#educ_opt<?= $value['educational_background_id']; ?>").val("<?= $value["school_level"]; ?>");
                                    </script>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="col-sm-12 m-t-md">
                <fieldset class="fieldset" style="padding-bottom: 20px;">
                    <legend class="legend">Work Experience</legend>

                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-sm btn-primary" onclick="add_field_work()">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <div class="col-sm-12" id="div_work">
                            <?php if (!empty($employee_work_info)): ?>
                                <?php foreach ($employee_work_info as $key => $value): ?>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="hidden" name="work_exp_id[]" value="<?= $value["work_exp_id"]; ?>">
                                                <input type="text" name="position[]" class="form-control input-sm text-capitalize" placeholder="Position" required value="<?= $value["position"]; ?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="text" name="company_name[]" class="form-control input-sm text-capitalize" placeholder="Name of Company" required value="<?= $value["name_of_company"]; ?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group sys_datepicker">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="work_year_start[]" class="form-control input-sm" value="<?= html_escape($value["date_attended"]); ?>" required <?= empty($value["date_attended"]) ? 'placeholder="Year Started"' : ''; ?>>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group sys_datepicker">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="work_year_end[]" class="form-control input-sm" value="<?= html_escape($value["date_ended"]); ?>" required <?= empty($value["date_ended"]) ? 'placeholder="Year Ended"' : ''; ?>>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="remove_field_work(this, <?= $value["work_exp_id"]; ?>)"><i class="fa fa-remove"></i></button>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="col-sm-12 m-t-md text-center">
                <button class="btn btn-primary" id="btn_save_employee">Save</button>
                <button type="button" class="btn btn-primary" onclick="location.reload();">Clear</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
	$(function(){
        <?php if (!empty($employee_info)): ?>
            <?php if ($employee_info["civil_status"] == "Married"): ?>
                $("#div_spouse").show("slide");
                $("#spouse_name").prop('required', true);
                $("#spouse_occu").prop('required', true);
                $("#spouse_place_birth").prop('required', true);
            <?php endif ?>

            $("#user_status").val('<?= $employee_info["user_status"]; ?>');
            $("#name_ext").val('<?= $employee_info["name_ext"]; ?>');
            $("#gender").val('<?= $employee_info["gender"]; ?>');
            $("#religion_id").val('<?= $employee_info["religion_id"]; ?>');
            $("#civil_status").val('<?= $employee_info["civil_status"]; ?>');
            $("#citizenship_id").val('<?= $employee_info["citizenship_id"]; ?>');

             $("#province_id").val('<?= $employee_info["province_id"]; ?>');
            get_city_by_province_select('<?= $employee_info["province_id"]; ?>');

            $("#department_id").val('<?= $employee_info["department_id"]; ?>');
            get_position_by_department_select('<?= $employee_info["department_id"]; ?>');
            
            setTimeout(function() {
                $("#city_id").val('<?= $employee_info["city_id"]; ?>').trigger("change");
                $("#position_id").val('<?= $employee_info["position_id"]; ?>').trigger("change");
            }, 1000);
        <?php else: ?>
            $("#user_picture").prop('required', true);
            $("#lbl_password").append(' <span class="text-danger">*</span>');
            $("#lbl_confirm_password").append(' <span class="text-danger">*</span>');
            $("#password").prop('required', true);
            $("#confirm_password").prop('required', true);
        <?php endif ?>

        $("#province_id").on('change', function() {
            if ($(this).val() != "") {

                get_city_by_province_select($(this).val());
                
            }
        });

        $("#department_id").on('change', function() {
            if ($(this).val() != "") {
                get_position_by_department_select($(this).val());
            }
        });

        $("#civil_status").on('change', function() {
            if ($(this).val() != "") {
                if ($(this).val() == "Married") {
                    $("#div_spouse").show("slide");
                    $("#spouse_name").prop('required', true);
                    $("#spouse_occu").prop('required', true);
                    $("#spouse_place_birth").prop('required', true);
                } else{
                    $("#div_spouse").hide("slide");
                    $("#spouse_name").prop('required', false);
                    $("#spouse_occu").prop('required', false);
                    $("#spouse_place_birth").prop('required', false);
                }
            }
        });

        $("#form_user [name=user_picture]").on("change", function(){
            var ext = this.value.match(/\.([^\.]+)$/)[1];
            switch(ext) {
                case 'jpeg':
                case 'jpg':
                case 'png':
                break;
                default:
                swal({
                    title: "Warning",
                    text: "Your file uploaded is not allowed!",
                    type: "warning"
                });
                this.value='';
            }
        });

        form_validator();

        $("#form_employee").ajaxForm({
            clearForm   : false,
            resetForm: false,
            beforeSubmit: function() {
                $("#btn_save_employee").attr("disabled", true);
                $("#btn_save_employee").html("<span class=\"fa fa-spinner fa-pulse\"></span>");
            },
            success: function(data) {
                var d = JSON.parse(data);
                if(d.success == true) {
                    $("#btn_save_employee").attr("disabled", false);
                    $("#btn_save_employee").html("<span class=\"fa fa-check\"></span> Save");

                    toastr.success(d.msg, 'Success');

                    setTimeout(function(){
                        location.replace(base_url+"admin/employee");
                    }, 1000);
                } else {
                    toastr.warning(d.msg, 'Warning');

                    $("#btn_save_employee").attr("disabled", false);
                    $("#btn_save_employee").html("Save");
                }
            }
        });

        <?php if (empty($employee_educ_info)): ?>
            add_field_educ();
        <?php endif ?>
        
        <?php if (empty($employee_work_info)): ?>
            add_field_work();
        <?php endif ?>
	});
    
    function get_city_by_province_select(id) {
        $.post(base_url+'common/select/get_city_by_province_select', 
            { id }, 
            function(data) {
                var d = JSON.parse(data);

                $("#city_id").empty();

                $("#city_id").append("<option value=''>Select</option>");

                $.each(d, function(k, v) {
                     $("#city_id").append("<option value='"+v.city_id+"'>"+v.city_name+"</option>");
                });
        });
    }

    function get_position_by_department_select(id) {
        $.post(base_url+'common/select/get_position_by_department_select', 
            { id }, 
            function(data) {
                var d = JSON.parse(data);

                $("#position_id").empty();
                
                $("#position_id").append("<option value=''>Select</option>");

                $.each(d, function(k, v) {
                     $("#position_id").append("<option value='"+v.position_id+"'>"+v.position_title+"</option>");
                });
        });
    }

    function add_field_educ() {
        $("#div_educ").append('<div class="row">\
                                    <div class="col-sm-3">\
                                        <div class="form-group">\
                                            <input type="hidden" name="educational_background_id[]">\
                                            <select name="educ_opt[]" class="form-control input-sm">\
                                                <option value="">Select</option>\
                                                <option value="Elem">Elementary</option>\
                                                <option value="Sec">Secondary</option>\
                                                <option value="Bac">Baccalaureate</option>\
                                                <option value="Post">Postgraduate</option>\
                                                <option value="Doc">Doctorate</option>\
                                            </select>\
                                        </div>\
                                    </div>\
                                    <div class="col-sm-3">\
                                        <div class="form-group">\
                                            <input type="text" name="name_of_school[]" class="form-control input-sm text-capitalize" placeholder="Name of School" required>\
                                        </div>\
                                    </div>\
                                    <div class="col-sm-2">\
                                        <div class="form-group sys_datepicker">\
                                            <div class="input-group date">\
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>\
                                                <input type="text" name="year_attended[]" class="form-control input-sm" placeholder="Year Attended" required>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="col-sm-2">\
                                        <div class="form-group sys_datepicker">\
                                            <div class="input-group date">\
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>\
                                                <input type="text" name="year_graduated[]" class="form-control input-sm" placeholder="Year Graduated" required>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="col-sm-2">\
                                        <button type="button" class="btn btn-sm btn-danger" onclick="remove_field_educ(this)"><i class="fa fa-remove"></i></button>\
                                    </div>\
                                </div>');
        
        $('.sys_datepicker .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });
    }

    function remove_field_educ(elem, id) {
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
                    $.post(base_url+"admin_employee/employee/delete_educ",
                        { value: id },
                        function(data) {
                            var d = JSON.parse(data);
                            if(d.success == true) {
                                $(elem).closest('.row').remove();
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
            $(elem).closest('.row').remove();
        }
    }

    function add_field_work() {
        $("#div_work").append('<div class="row">\
                                    <div class="col-sm-3">\
                                        <div class="form-group">\
                                            <input type="hidden" name="work_exp_id[]">\
                                            <input type="text" name="position[]" class="form-control input-sm text-capitalize" placeholder="Position" required>\
                                        </div>\
                                    </div>\
                                    <div class="col-sm-3">\
                                        <div class="form-group">\
                                            <input type="text" name="company_name[]" class="form-control input-sm text-capitalize" placeholder="Name of Company" required>\
                                        </div>\
                                    </div>\
                                    <div class="col-sm-2">\
                                        <div class="form-group sys_datepicker">\
                                            <div class="input-group date">\
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>\
                                                <input type="text" name="work_year_start[]" class="form-control input-sm" required placeholder="Year Started">\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="col-sm-2">\
                                        <div class="form-group sys_datepicker">\
                                            <div class="input-group date">\
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>\
                                                <input type="text" name="work_year_end[]" class="form-control input-sm" placeholder="Year Ended">\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="col-sm-2">\
                                        <button type="button" class="btn btn-sm btn-danger" onclick="remove_field_work(this)"><i class="fa fa-remove"></i></button>\
                                    </div>\
                                </div>');

        $('.sys_datepicker .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });
    }

    function remove_field_work(elem, id) {
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
                    $.post(base_url+"admin_employee/employee/delete_work",
                        { value: id },
                        function(data) {
                            var d = JSON.parse(data);
                            if(d.success == true) {
                                $(elem).closest('.row').remove();
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
            $(elem).closest('.row').remove();
        }
    }
</script>