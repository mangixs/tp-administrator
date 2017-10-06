/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : tp

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2017-10-06 15:51:59
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_job
-- ----------------------------
INSERT INTO `admin_job` VALUES ('5', '超级管理员', '超级管理员s', '1');
INSERT INTO `admin_job` VALUES ('7', '管理员', '管理员', '1');

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
INSERT INTO `admin_job_auth` VALUES ('5', 'admin', 'export');
INSERT INTO `admin_job_auth` VALUES ('5', 'article', 'add');
INSERT INTO `admin_job_auth` VALUES ('5', 'article', 'delete');
INSERT INTO `admin_job_auth` VALUES ('5', 'article', 'edit');
INSERT INTO `admin_job_auth` VALUES ('5', 'article', 'export');
INSERT INTO `admin_job_auth` VALUES ('5', 'front', 'export');
INSERT INTO `admin_job_auth` VALUES ('5', 'func', 'add');
INSERT INTO `admin_job_auth` VALUES ('5', 'func', 'delete');
INSERT INTO `admin_job_auth` VALUES ('5', 'func', 'edit');
INSERT INTO `admin_job_auth` VALUES ('5', 'func', 'export');
INSERT INTO `admin_job_auth` VALUES ('5', 'job', 'add');
INSERT INTO `admin_job_auth` VALUES ('5', 'job', 'delete');
INSERT INTO `admin_job_auth` VALUES ('5', 'job', 'edit');
INSERT INTO `admin_job_auth` VALUES ('5', 'job', 'export');
INSERT INTO `admin_job_auth` VALUES ('5', 'menu', 'add');
INSERT INTO `admin_job_auth` VALUES ('5', 'menu', 'delete');
INSERT INTO `admin_job_auth` VALUES ('5', 'menu', 'edit');
INSERT INTO `admin_job_auth` VALUES ('5', 'menu', 'export');
INSERT INTO `admin_job_auth` VALUES ('5', 'staff', 'add');
INSERT INTO `admin_job_auth` VALUES ('5', 'staff', 'delete');
INSERT INTO `admin_job_auth` VALUES ('5', 'staff', 'edit');
INSERT INTO `admin_job_auth` VALUES ('5', 'staff', 'export');
INSERT INTO `admin_job_auth` VALUES ('7', 'admin', 'export');
INSERT INTO `admin_job_auth` VALUES ('7', 'front', 'export');
INSERT INTO `admin_job_auth` VALUES ('7', 'func', 'add');
INSERT INTO `admin_job_auth` VALUES ('7', 'func', 'export');
INSERT INTO `admin_job_auth` VALUES ('7', 'job', 'add');
INSERT INTO `admin_job_auth` VALUES ('7', 'job', 'delete');
INSERT INTO `admin_job_auth` VALUES ('7', 'job', 'export');
INSERT INTO `admin_job_auth` VALUES ('7', 'staff', 'add');
INSERT INTO `admin_job_auth` VALUES ('7', 'staff', 'delete');
INSERT INTO `admin_job_auth` VALUES ('7', 'staff', 'edit');
INSERT INTO `admin_job_auth` VALUES ('7', 'staff', 'export');

-- ----------------------------
-- Table structure for background_func
-- ----------------------------
DROP TABLE IF EXISTS `background_func`;
CREATE TABLE `background_func` (
  `key` varchar(24) NOT NULL,
  `func_name` varchar(24) DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of background_func
-- ----------------------------
INSERT INTO `background_func` VALUES ('admin', '后台管理');
INSERT INTO `background_func` VALUES ('article', '文章管理');
INSERT INTO `background_func` VALUES ('front', '前端管理');
INSERT INTO `background_func` VALUES ('func', '功能管理');
INSERT INTO `background_func` VALUES ('job', '职位管理');
INSERT INTO `background_func` VALUES ('menu', '菜单管理');
INSERT INTO `background_func` VALUES ('staff', '管理员管理');

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
INSERT INTO `func_auth` VALUES ('article', 'add', '添加');
INSERT INTO `func_auth` VALUES ('article', 'delete', '删除');
INSERT INTO `func_auth` VALUES ('article', 'edit', '编辑');
INSERT INTO `func_auth` VALUES ('func', 'add', '添加');
INSERT INTO `func_auth` VALUES ('func', 'delete', '删除');
INSERT INTO `func_auth` VALUES ('func', 'edit', '编辑');
INSERT INTO `func_auth` VALUES ('job', 'add', '添加');
INSERT INTO `func_auth` VALUES ('job', 'delete', '删除');
INSERT INTO `func_auth` VALUES ('job', 'edit', '编辑');
INSERT INTO `func_auth` VALUES ('menu', 'add', '添加');
INSERT INTO `func_auth` VALUES ('menu', 'delete', '删除');
INSERT INTO `func_auth` VALUES ('menu', 'edit', '编辑');
INSERT INTO `func_auth` VALUES ('staff', 'add', '添加');
INSERT INTO `func_auth` VALUES ('staff', 'delete', '删除');
INSERT INTO `func_auth` VALUES ('staff', 'edit', '编辑');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `named` varchar(36) DEFAULT NULL,
  `icon` varchar(120) DEFAULT NULL,
  `url` varchar(120) DEFAULT NULL,
  `sort` int(3) DEFAULT '100',
  `level` int(2) DEFAULT '1',
  `parent` int(11) DEFAULT '0',
  `screen_auth` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '后台管理', '/upload/icon/20171006\\menu_home.png', '', '100', '0', '0', '{\"admin\":[\"export\"]}');
INSERT INTO `menu` VALUES ('2', '管理员管理', '/upload/icon/20171006\\menu_home.png', '/staff', '100', '1', '1', '{\"staff\":[\"export\"]}');
INSERT INTO `menu` VALUES ('3', '前端管理', '/upload/icon/20171006\\menu_home.png', '', '100', '0', '0', '{\"front\":[\"export\"]}');
INSERT INTO `menu` VALUES ('5', '文章管理', '/upload/icon/20171006\\menu_home.png', '/article', '100', '1', '3', '{\"article\":[\"export\"]}');
INSERT INTO `menu` VALUES ('6', '职位管理', '/upload/icon/20171006\\menu_home.png', '/job', '100', '1', '1', '{\"job\":[\"export\"]}');
INSERT INTO `menu` VALUES ('7', '功能管理', '/upload/icon/20171006\\menu_home.png', '/func', '100', '1', '1', '{\"func\":[\"export\"]}');
INSERT INTO `menu` VALUES ('8', '菜单管理', '/upload/icon/20171006\\menu_home.png', '/menu', '100', '1', '1', '{\"menu\":[\"export\"]}');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of staff
-- ----------------------------
INSERT INTO `staff` VALUES ('1', 'admin', '管理员', '2', '/upload/headimage/20170929\\1d3348ac838f99c93b253c2429f42322.jpg', '001', null, '0#1', 'e10adc3949ba59abbe56e057f20f883e', '[5,7]');
INSERT INTO `staff` VALUES ('10', 'test', '管理员', '1', '/upload/headimage/20170929\\1d3348ac838f99c93b253c2429f42322.jpg', '002', null, '0', 'e10adc3949ba59abbe56e057f20f883e', '[7]');

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
INSERT INTO `staff_job` VALUES ('1', '5');
INSERT INTO `staff_job` VALUES ('1', '7');
INSERT INTO `staff_job` VALUES ('10', '7');
