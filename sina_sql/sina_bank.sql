/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : mmoney2

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-26 08:49:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sina_bank`
-- ----------------------------
DROP TABLE IF EXISTS `sina_bank`;
CREATE TABLE `sina_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '网站用户id',
  `identity_id` varchar(255) DEFAULT NULL COMMENT '用户信息标识',
  `request_no` varchar(255) DEFAULT NULL COMMENT '绑卡请求号',
  `bank_code` varchar(25) DEFAULT NULL COMMENT '银行编号',
  `bank_name` varchar(255) DEFAULT NULL COMMENT '银行名称',
  `bank_account_no` varchar(50) DEFAULT NULL COMMENT '银行卡号',
  `card_type` varchar(25) DEFAULT NULL COMMENT '银行卡类型',
  `card_attribute` varchar(25) DEFAULT NULL COMMENT '银行卡属性',
  `phone_no` varchar(25) DEFAULT NULL COMMENT '银行预留手机',
  `province` varchar(50) DEFAULT NULL COMMENT '开卡省份',
  `city` varchar(50) DEFAULT NULL COMMENT '开卡城市',
  `bank_branch` varchar(255) DEFAULT NULL COMMENT '支行名称',
  `ticket` varchar(255) DEFAULT NULL COMMENT '推进参数',
  `valid_code` varchar(25) DEFAULT NULL COMMENT '短信验证码',
  `card_id` varchar(255) DEFAULT NULL COMMENT '钱包系统卡ID',
  `status` tinyint(1) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='新浪绑卡记录';

-- ----------------------------
-- Records of sina_bank
-- ----------------------------
INSERT INTO `sina_bank` VALUES ('1', '44', '1440032322HQW131102199007042019', '201508201004956578100', 'CITIC', null, '6226901805086869', 'DEBIT', 'C', '15127281324', '河北省', '保定市', '', '27d92fafda0949a0bbbce727d283c3a1', '965531', null, '1', 'ticket不存在或已失效', '1440037231', '1440039046');
INSERT INTO `sina_bank` VALUES ('5', '44', '1440032322HQW131102199007042019', '201508204899545225185', 'CITIC', null, '6226901805086869', 'DEBIT', 'C', '15127281324', '河北省', '保定市', '', '9e08eeb8cfe844d3a7c22f4f96b4fdfa', '555730', '31131', '1', '提交成功', '1440039124', '1440039177');
INSERT INTO `sina_bank` VALUES ('6', '44', '1440052548HQW131102199007042019', '201508201001005397980', 'CITIC', null, '6226901805086869', 'DEBIT', 'C', '15127281324', '河北省', '保定市', '', 'bdf307fc78ab438eb5991aa11c87809b', '020346', '31193', '2', '提交成功', '1440052640', '1440052706');
INSERT INTO `sina_bank` VALUES ('7', '44', '1440052548HQW131102199007042019', '201508215210151530079', 'CCB', null, '6217000140004808851', 'DEBIT', 'C', '15127281324', '河北省', '保定市', '', '36dc2c65ee604b79b6d07a9f553a79a3', null, null, '1', '等待短信确认', '1440132058', '1440132058');
INSERT INTO `sina_bank` VALUES ('8', '44', '1440052548HQW131102199007042019', '201508219952575290536', 'CCB', '建设银行', '6217000140004808851', 'DEBIT', 'C', '15127281324', '河北省', '保定市', '', 'c2076f7b05314e459177d2a1e849b4a2', null, null, '1', '等待短信确认', '1440132495', '1440132495');
INSERT INTO `sina_bank` VALUES ('9', '44', '1440052548HQW131102199007042019', '201508215656574931628', 'CCB', '建设银行', '6217000140004808851', 'DEBIT', 'C', '15127281324', '河北省', '保定市', '', 'eb3d3b14dedf483c8d6067fa6f68445e', null, null, '1', '等待短信确认', '1440133100', '1440133100');
