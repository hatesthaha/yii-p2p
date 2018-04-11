/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : mmoney2

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-26 08:50:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sina_member`
-- ----------------------------
DROP TABLE IF EXISTS `sina_member`;
CREATE TABLE `sina_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identity_id` varchar(255) DEFAULT NULL COMMENT '用户标识信息',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `name` varchar(25) DEFAULT '' COMMENT '用户名',
  `idcard` varchar(25) DEFAULT '身份证号',
  `user_ip` varchar(25) DEFAULT NULL COMMENT '用户ip',
  `phone` varchar(25) DEFAULT NULL COMMENT '认证手机号',
  `status` tinyint(1) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='新浪创建会员记录';

-- ----------------------------
-- Records of sina_member
-- ----------------------------
INSERT INTO `sina_member` VALUES ('1', null, '24', '王摸摸', '131102199007042019', '127.0.0.1', '15127213241', '1', '提交成功', '1439971551', '1439971551');
INSERT INTO `sina_member` VALUES ('2', null, '24', '王摸摸', '131102199007042019', '127.0.0.1', '15127213241', '0', '用户标识信息重复', '1439971582', '1439971582');
INSERT INTO `sina_member` VALUES ('3', null, '25', '王利亚', '131102199007042019', '127.0.0.1', '15127213241', '0', '用户标识信息重复', '1439977331', '1439977331');
INSERT INTO `sina_member` VALUES ('4', null, '25', '王利亚', '131102199007042019', '127.0.0.1', '15127213241', '1', '提交成功', '1440031564', '1440031564');
INSERT INTO `sina_member` VALUES ('5', '1440032322HQW131102199007042019', '44', '王利亚', '131102199007042019', '127.0.0.1', '15127213241', '-1', '提交成功', '1440032325', '1440032325');
INSERT INTO `sina_member` VALUES ('6', '1440052294HQW131102199007042019', '44', '王利亚', '131102199007042019', '127.0.0.1', '15127213241', '-1', '提交成功', '1440052296', '1440052296');
INSERT INTO `sina_member` VALUES ('7', '1440052548HQW131102199007042019', '44', '王利亚', '131102199007042019', '127.0.0.1', '15127281324', '1', '提交成功', '1440052552', '1440052552');
INSERT INTO `sina_member` VALUES ('8', '1440144318HQW131102199007042019', '43', '网名', '131102199007042019', '127.0.0.1', '15933977599', '1', '提交成功', '1440144322', '1440144322');
INSERT INTO `sina_member` VALUES ('9', '1440144466HQW131102199007042019', '42', '网名', '131102199007042019', '127.0.0.1', '15933977595', '1', '提交成功', '1440144469', '1440144469');
INSERT INTO `sina_member` VALUES ('10', '1440144523HQW131102199007042019', '41', '网名', '131102199007042019', '127.0.0.1', '15933977592', '1', '提交成功', '1440144525', '1440144525');
INSERT INTO `sina_member` VALUES ('11', '1440147735HQW131102199007042019', '38', '网名', '131102199007042019', '127.0.0.1', '159339775921', '0', '认证内容手机号格式不正确', '1440147738', '1440147738');
INSERT INTO `sina_member` VALUES ('12', '1440147796HQW131102199007042019', '38', '网名', '131102199007042019', '127.0.0.1', '15933977521', '1', '提交成功', '1440147799', '1440147799');
INSERT INTO `sina_member` VALUES ('13', '1440235740HQW131102199007042019', '27', '网名', '131102199007042019', '127.0.0.1', '1851861234', '0', '认证内容手机号格式不正确', '1440235746', '1440235746');
INSERT INTO `sina_member` VALUES ('16', '1440236114HQW131102199007042019', '27', '网名', '131102199007042019', '127.0.0.1', '18518671234', '1', '提交成功', '1440236116', '1440236116');
