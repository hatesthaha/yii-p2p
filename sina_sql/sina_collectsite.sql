/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : mmoney2

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-26 08:49:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sina_collectsite`
-- ----------------------------
DROP TABLE IF EXISTS `sina_collectsite`;
CREATE TABLE `sina_collectsite` (
  `id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='企业账户向用户发红包';

-- ----------------------------
-- Records of sina_collectsite
-- ----------------------------
