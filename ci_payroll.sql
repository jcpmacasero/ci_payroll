/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1_3306
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : ci_payroll

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-07-20 09:05:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tbl_admin
-- ----------------------------
DROP TABLE IF EXISTS `tbl_admin`;
CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(40) DEFAULT NULL,
  `name` varchar(60) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_date` datetime DEFAULT NULL,
  `delete_status` int(1) DEFAULT '0',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_admin
-- ----------------------------
INSERT INTO `tbl_admin` VALUES ('1', 'admin@gmail.com', 'Admin', '$2a$08$5cfoscDD8UwMem5FvLwT5uxHpboncC8/jiIYOq4pWw24wJMzuvMqy', null, null, null, '2019-07-17 09:48:01', '2019-07-20 07:23:24', '2019-07-20 07:23:24', '0');

-- ----------------------------
-- Table structure for tbl_calendar
-- ----------------------------
DROP TABLE IF EXISTS `tbl_calendar`;
CREATE TABLE `tbl_calendar` (
  `calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_date` date DEFAULT NULL,
  `event_status` int(2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_status` int(1) DEFAULT '0',
  PRIMARY KEY (`calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_calendar
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_citizenship
-- ----------------------------
DROP TABLE IF EXISTS `tbl_citizenship`;
CREATE TABLE `tbl_citizenship` (
  `citizenship_id` int(11) NOT NULL AUTO_INCREMENT,
  `citizenship_name` varchar(40) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_status` int(1) DEFAULT '0',
  PRIMARY KEY (`citizenship_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_citizenship
-- ----------------------------
INSERT INTO `tbl_citizenship` VALUES ('1', 'Filipino', null, null, '2019-06-26 01:20:55', '2019-06-26 01:20:55', '0');

-- ----------------------------
-- Table structure for tbl_city
-- ----------------------------
DROP TABLE IF EXISTS `tbl_city`;
CREATE TABLE `tbl_city` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `province_id` int(11) DEFAULT NULL,
  `city_name` varchar(40) DEFAULT NULL,
  `zip_code` int(12) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_status` int(1) DEFAULT '0',
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_city
-- ----------------------------
INSERT INTO `tbl_city` VALUES ('1', '1', 'Bangued', '2800', null, null, '2019-06-25 19:13:13', '2019-06-25 19:14:57', '0');
INSERT INTO `tbl_city` VALUES ('2', '1', 'Boliney', '2815', null, null, '2019-06-25 19:14:06', '2019-06-25 19:15:01', '0');

-- ----------------------------
-- Table structure for tbl_department
-- ----------------------------
DROP TABLE IF EXISTS `tbl_department`;
CREATE TABLE `tbl_department` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_status` int(11) DEFAULT '0',
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_department
-- ----------------------------
INSERT INTO `tbl_department` VALUES ('2', 'Marketing', null, null, '2019-07-15 14:50:40', '2019-07-15 14:50:43', '0');

-- ----------------------------
-- Table structure for tbl_educational_background
-- ----------------------------
DROP TABLE IF EXISTS `tbl_educational_background`;
CREATE TABLE `tbl_educational_background` (
  `educational_background_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_level` varchar(40) DEFAULT NULL,
  `name_of_school` varchar(100) DEFAULT NULL,
  `degree` varchar(100) DEFAULT NULL,
  `date_attended` date DEFAULT NULL,
  `date_graduated` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `delete_by` int(11) DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL,
  `delete_status` int(1) DEFAULT '0',
  PRIMARY KEY (`educational_background_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_educational_background
-- ----------------------------
INSERT INTO `tbl_educational_background` VALUES ('1', 'Sec', 'asd', 'WALA', '2019-07-14', '2019-07-19', '3', null, null, '0');
INSERT INTO `tbl_educational_background` VALUES ('2', 'Bac', 'das', 'WALA', '2019-07-31', '2019-07-31', '3', '1', '2019-07-19 18:47:22', '1');

-- ----------------------------
-- Table structure for tbl_family_background
-- ----------------------------
DROP TABLE IF EXISTS `tbl_family_background`;
CREATE TABLE `tbl_family_background` (
  `family_background_id` int(11) NOT NULL AUTO_INCREMENT,
  `fathers_name` varchar(80) DEFAULT NULL,
  `fathers_occupation` varchar(50) DEFAULT NULL,
  `fathers_birthdate` date DEFAULT NULL,
  `mothers_name` varchar(80) DEFAULT NULL,
  `mothers_occupation` varchar(50) DEFAULT NULL,
  `mothers_birthdate` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`family_background_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_family_background
-- ----------------------------
INSERT INTO `tbl_family_background` VALUES ('3', 'asd', 'asd', '2019-07-17', 'asd', 'asd', '2019-07-30', '3');

-- ----------------------------
-- Table structure for tbl_position
-- ----------------------------
DROP TABLE IF EXISTS `tbl_position`;
CREATE TABLE `tbl_position` (
  `position_id` int(11) NOT NULL AUTO_INCREMENT,
  `position_title` varchar(80) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_status` int(1) DEFAULT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_position
-- ----------------------------
INSERT INTO `tbl_position` VALUES ('1', 'Janitor', '2', null, null, '2019-07-05 15:08:07', '2019-07-19 08:35:36', '0');

-- ----------------------------
-- Table structure for tbl_province
-- ----------------------------
DROP TABLE IF EXISTS `tbl_province`;
CREATE TABLE `tbl_province` (
  `province_id` int(11) NOT NULL AUTO_INCREMENT,
  `province_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_status` int(1) DEFAULT '0',
  PRIMARY KEY (`province_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_province
-- ----------------------------
INSERT INTO `tbl_province` VALUES ('1', 'Abra', null, null, '2019-06-25 19:11:31', '2019-06-25 19:11:31', '0');

-- ----------------------------
-- Table structure for tbl_religion
-- ----------------------------
DROP TABLE IF EXISTS `tbl_religion`;
CREATE TABLE `tbl_religion` (
  `religion_id` int(11) NOT NULL AUTO_INCREMENT,
  `religion_name` varchar(30) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_status` int(1) DEFAULT '0',
  PRIMARY KEY (`religion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_religion
-- ----------------------------
INSERT INTO `tbl_religion` VALUES ('1', 'Roman Catholic', null, null, '2019-06-26 01:11:34', '2019-06-26 01:11:34', '0');
INSERT INTO `tbl_religion` VALUES ('2', 'Seventh Day Adventist', null, null, '2019-06-26 01:11:47', '2019-06-26 01:11:47', '0');

-- ----------------------------
-- Table structure for tbl_scheduler
-- ----------------------------
DROP TABLE IF EXISTS `tbl_scheduler`;
CREATE TABLE `tbl_scheduler` (
  `scheduler_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `no_of_day_off` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_status` int(1) DEFAULT '0',
  PRIMARY KEY (`scheduler_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tbl_scheduler
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_spouse
-- ----------------------------
DROP TABLE IF EXISTS `tbl_spouse`;
CREATE TABLE `tbl_spouse` (
  `spouse_id` int(11) NOT NULL AUTO_INCREMENT,
  `spouse_name` varchar(80) DEFAULT NULL,
  `spouse_occupation` varchar(50) DEFAULT NULL,
  `spouse_birthdate` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`spouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_spouse
-- ----------------------------
INSERT INTO `tbl_spouse` VALUES ('1', 'asd', 'asd', '2019-07-16', '3');

-- ----------------------------
-- Table structure for tbl_user
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user` (
  `user_id` int(20) NOT NULL AUTO_INCREMENT,
  `photo` varchar(100) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `employee_id` varchar(8) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `name_ext` varchar(50) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `citizenship_id` int(11) DEFAULT NULL,
  `religion_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `civil_status` varchar(10) DEFAULT NULL,
  `dependent_children` int(11) DEFAULT NULL,
  `street_address` varchar(100) DEFAULT NULL,
  `place_of_birth` text,
  `philhealth_no` varchar(20) DEFAULT NULL,
  `tin_no` varchar(20) DEFAULT NULL,
  `pag_ibig_no` varchar(20) DEFAULT NULL,
  `sss_no` varchar(20) DEFAULT NULL,
  `user_status` varchar(40) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_status` int(1) DEFAULT '0',
  `last_login_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_user
-- ----------------------------
INSERT INTO `tbl_user` VALUES ('3', 'assets/img/upload/Users/user_picture_20190719024544.png', '1', '14100001', 'Klaven Rey', '', 'Matugas', '', 'M', '', 'user@gmail.com', '$2a$08$cUZFO/dHkQNLaXYH6sPYrOQtN9Db0gk4gi7GgnF/aR31pyve08gYq', '2019-07-19', '1', '1', '1', 'Married', '0', 'P-1, Agong~ong, Buenavista Agusan Del Norte', 'asdas', 'as', 'as', '', 'asd', 'ACTIVATED', '1', '1', '2019-07-19 08:45:44', '2019-07-20 07:42:42', '0', '2019-07-20 07:42:42');

-- ----------------------------
-- Table structure for tbl_user_module
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user_module`;
CREATE TABLE `tbl_user_module` (
  `user_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tbl_user_module
-- ----------------------------
INSERT INTO `tbl_user_module` VALUES ('1', 'Dashboard');

-- ----------------------------
-- Table structure for tbl_user_module_button
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user_module_button`;
CREATE TABLE `tbl_user_module_button` (
  `user_mod_button_id` int(11) NOT NULL AUTO_INCREMENT,
  `button_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_module_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_mod_button_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_user_module_button
-- ----------------------------
INSERT INTO `tbl_user_module_button` VALUES ('1', 'btn_add', 'Add', '1');
INSERT INTO `tbl_user_module_button` VALUES ('2', 'btn_edit', 'Edit', '1');
INSERT INTO `tbl_user_module_button` VALUES ('3', 'btn_delete', 'Delete', '1');
INSERT INTO `tbl_user_module_button` VALUES ('4', 'view_page', 'View', '1');

-- ----------------------------
-- Table structure for tbl_user_permission
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user_permission`;
CREATE TABLE `tbl_user_permission` (
  `user_permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_mod_button_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_user_permission
-- ----------------------------
INSERT INTO `tbl_user_permission` VALUES ('1', '3', '1', '1');
INSERT INTO `tbl_user_permission` VALUES ('2', '3', '2', '1');
INSERT INTO `tbl_user_permission` VALUES ('3', '3', '3', '1');
INSERT INTO `tbl_user_permission` VALUES ('4', '3', '4', '1');

-- ----------------------------
-- Table structure for tbl_work_experience
-- ----------------------------
DROP TABLE IF EXISTS `tbl_work_experience`;
CREATE TABLE `tbl_work_experience` (
  `work_exp_id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(50) DEFAULT NULL,
  `name_of_company` varchar(100) DEFAULT NULL,
  `date_attended` date DEFAULT NULL,
  `date_ended` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `delete_by` int(11) DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL,
  `delete_status` int(1) DEFAULT '0',
  PRIMARY KEY (`work_exp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_work_experience
-- ----------------------------
INSERT INTO `tbl_work_experience` VALUES ('1', 'asd', 'asd', '2019-07-15', '2019-07-30', '3', null, null, '0');
INSERT INTO `tbl_work_experience` VALUES ('2', 'asd', 'asd', '2019-07-21', '2019-07-29', '3', '1', '2019-07-19 18:47:25', '1');
