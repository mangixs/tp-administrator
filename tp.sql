/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50718
Source Host           : localhost:3306
Source Database       : tp

Target Server Type    : MYSQL
Target Server Version : 50718
File Encoding         : 65001

Date: 2017-09-27 17:56:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin_job
-- ----------------------------
DROP TABLE IF EXISTS `admin_job`;
CREATE TABLE `admin_job` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `job_name` varchar(36) DEFAULT NULL,
  `explain` varchar(240) DEFAULT NULL,
  `vaild` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_job
-- ----------------------------

-- ----------------------------
-- Table structure for admin_job_auth
-- ----------------------------
DROP TABLE IF EXISTS `admin_job_auth`;
CREATE TABLE `admin_job_auth` (
  `admin_job_id` int(8) NOT NULL,
  `func_key` varchar(24) NOT NULL,
  `auth_key` varchar(24) NOT NULL,
  PRIMARY KEY (`admin_job_id`,`func_key`,`auth_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_job_auth
-- ----------------------------

-- ----------------------------
-- Table structure for background_func
-- ----------------------------
DROP TABLE IF EXISTS `background_func`;
CREATE TABLE `background_func` (
  `key` varchar(24) NOT NULL,
  `func_name` varchar(24) DEFAULT NULL,
  `background_url_key` int(6) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of background_func
-- ----------------------------
INSERT INTO `background_func` VALUES ('admin', '员工管理', '0');
INSERT INTO `background_func` VALUES ('admin_job', '职位管理', '0');
INSERT INTO `background_func` VALUES ('adv_package', '社区广告', '0');
INSERT INTO `background_func` VALUES ('adv_resource', '广告资源', '0');
INSERT INTO `background_func` VALUES ('app_advertise', 'APP广告', '0');
INSERT INTO `background_func` VALUES ('bg_func', '后台功能', '0');
INSERT INTO `background_func` VALUES ('bg_menu', '后台菜单', '0');
INSERT INTO `background_func` VALUES ('brand', '品牌', '0');
INSERT INTO `background_func` VALUES ('classify', '商品分类', '0');
INSERT INTO `background_func` VALUES ('devices', '门禁设备', '0');
INSERT INTO `background_func` VALUES ('device_monitor', '设备监控', '0');
INSERT INTO `background_func` VALUES ('enterprice', '企业管理', '0');
INSERT INTO `background_func` VALUES ('goods', '商品管理', '0');
INSERT INTO `background_func` VALUES ('home_menu', '管家菜单', '0');
INSERT INTO `background_func` VALUES ('manager_menu', '物业菜单', '0');
INSERT INTO `background_func` VALUES ('owner', '业主管理', '0');
INSERT INTO `background_func` VALUES ('property_advise', '社区建议/保修', '0');
INSERT INTO `background_func` VALUES ('property_areas', '物业区域', '0');
INSERT INTO `background_func` VALUES ('property_community', '社区', '0');
INSERT INTO `background_func` VALUES ('property_house', '单元', '0');
INSERT INTO `background_func` VALUES ('property_notice', '社区通知', '0');
INSERT INTO `background_func` VALUES ('property_room', '房间', '0');
INSERT INTO `background_func` VALUES ('property_user', '住户信息', '0');
INSERT INTO `background_func` VALUES ('sale', '上架销售', '0');
INSERT INTO `background_func` VALUES ('slide', '幻灯片设置', '0');
INSERT INTO `background_func` VALUES ('tag', '商品标签', '0');
INSERT INTO `background_func` VALUES ('user_advise', '用户建议', '0');
INSERT INTO `background_func` VALUES ('video_devices', '视频门禁设备', '0');
INSERT INTO `background_func` VALUES ('voucher', '优惠', '0');

-- ----------------------------
-- Table structure for bg_menu
-- ----------------------------
DROP TABLE IF EXISTS `bg_menu`;
CREATE TABLE `bg_menu` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `parent_id` int(6) DEFAULT '0',
  `menu_name` varchar(24) DEFAULT NULL,
  `url` varchar(120) DEFAULT NULL,
  `screen` varchar(120) DEFAULT NULL,
  `type` int(1) DEFAULT '0',
  `icon` varchar(120) DEFAULT NULL,
  `screen_auth` varchar(480) DEFAULT NULL,
  `sort` int(3) DEFAULT '500',
  `valid` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bg_menu
-- ----------------------------

-- ----------------------------
-- Table structure for func_auth
-- ----------------------------
DROP TABLE IF EXISTS `func_auth`;
CREATE TABLE `func_auth` (
  `func_key` varchar(24) NOT NULL,
  `key` varchar(24) NOT NULL,
  `auth_name` varchar(24) DEFAULT NULL,
  PRIMARY KEY (`func_key`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of func_auth
-- ----------------------------

-- ----------------------------
-- Table structure for staff
-- ----------------------------
DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` varchar(24) NOT NULL,
  `true_name` varchar(24) DEFAULT NULL,
  `sex` varchar(9) DEFAULT NULL,
  `header_img` varchar(120) DEFAULT NULL,
  `staff_num` varchar(16) DEFAULT NULL,
  `study_his` varchar(24) DEFAULT NULL,
  `store_id` varchar(24) DEFAULT '0',
  `pwd` varchar(64) NOT NULL,
  `job` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of staff
-- ----------------------------
INSERT INTO `staff` VALUES ('1', 'admin', '管理员', '男', '/resources/upload/2017-02-13/1486947527396580.jpg', '001', null, '0#1', '96e79218965eb72c92a549dd5a330112', '[\"1\",\"2\"]');

-- ----------------------------
-- Table structure for staff_job
-- ----------------------------
DROP TABLE IF EXISTS `staff_job`;
CREATE TABLE `staff_job` (
  `staff_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  PRIMARY KEY (`staff_id`,`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of staff_job
-- ----------------------------
