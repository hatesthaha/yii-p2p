/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : mmoney2

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-27 15:41:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sina_notify`
-- ----------------------------
DROP TABLE IF EXISTS `sina_notify`;
CREATE TABLE `sina_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notify_type` varchar(255) DEFAULT NULL COMMENT '通知类型',
  `notify_id` varchar(255) DEFAULT NULL COMMENT '通知编码',
  `_input_charset` varchar(50) DEFAULT NULL COMMENT '参数编码字符集',
  `notify_time` varchar(50) DEFAULT NULL COMMENT '通知时间',
  `sign` varchar(255) DEFAULT NULL,
  `sign_type` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `memo` text COMMENT '备注',
  `error_code` varchar(50) DEFAULT NULL,
  `error_message` text COMMENT '错误信息',
  `notify_data` text,
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sina_notify
-- ----------------------------
INSERT INTO `sina_notify` VALUES ('2', null, null, null, null, null, null, null, null, null, null, '{\"idcard\":\"\",\"name\":\"\",\"new_pwd\":\"123456\",\"phone\":\"15127281324\",\"phone_code\":\"1111\",\"rep_pwd\":\"123456\"}', '1440574032', '1440574032');
INSERT INTO `sina_notify` VALUES ('3', null, null, null, null, null, null, null, null, null, null, '{\"idcard\":\"\",\"name\":\"\",\"new_pwd\":\"123456\",\"phone\":\"15127281324\",\"phone_code\":\"1111\",\"rep_pwd\":\"123456\"}', '1440574270', '1440574270');
