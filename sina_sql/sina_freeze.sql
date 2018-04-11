/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : mmoney2

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-28 09:02:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sina_freeze`
-- ----------------------------
DROP TABLE IF EXISTS `sina_freeze`;
CREATE TABLE `sina_freeze` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `identity_id` varchar(255) DEFAULT NULL COMMENT '用户标识',
  `account_type` varchar(255) DEFAULT NULL COMMENT '账户类型',
  `out_freeze_no` varchar(255) DEFAULT NULL COMMENT '冻结订单号',
  `freeze_money` double(13,2) DEFAULT NULL COMMENT '冻结金额',
  `freeze_summary` varchar(255) DEFAULT NULL COMMENT '冻结原因',
  `status` tinyint(1) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `out_unfreeze_no` varchar(255) DEFAULT NULL COMMENT '解冻单号',
  `unfreeze_money` double(13,2) DEFAULT NULL COMMENT '解冻资金',
  `unfreeze_summary` varchar(255) DEFAULT NULL COMMENT '解冻原因',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sina_freeze
-- ----------------------------
INSERT INTO `sina_freeze` VALUES ('1', '44', '1440052548HQW131102199007042019', 'SAVING_POT', '201508271015610016291', '1.00', '11', '1', '退款成功', null, null, null, '1440665679', '1440665679');
