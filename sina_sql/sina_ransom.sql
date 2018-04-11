/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : mmoney2

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-23 12:52:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sina_ransom`
-- ----------------------------
DROP TABLE IF EXISTS `sina_ransom`;
CREATE TABLE `sina_ransom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `identity_id` varchar(255) DEFAULT NULL COMMENT '赎回人用户标识',
  `out_trade_no` varchar(255) DEFAULT NULL COMMENT '新浪代收订单信息',
  `summary` varchar(255) DEFAULT NULL COMMENT '代收备注',
  `trade_close_time` varchar(25) DEFAULT NULL COMMENT '代收有效时间',
  `payer_id` varchar(255) DEFAULT NULL COMMENT '新浪代收人标识信息',
  `payer_ip` varchar(25) DEFAULT NULL COMMENT '操作ip',
  `pay_method` varchar(255) DEFAULT NULL COMMENT '代收付款方式',
  `money_sina` double(13,2) DEFAULT NULL COMMENT '新浪代收金额',
  `payee_out_trade_no` varchar(255) DEFAULT NULL COMMENT '新浪代付订单号',
  `status` tinyint(1) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sina_ransom
-- ----------------------------
INSERT INTO `sina_ransom` VALUES ('1', '44', '1440052548HQW131102199007042019', '201508231005010029577', '用户赎回', '1d', '1440147796HQW131102199007042019', '127.0.0.1', 'balance^8624.87^SAVING_POT', '8624.87', '', '0', '0', '1440305407', '1440305407');
INSERT INTO `sina_ransom` VALUES ('2', '44', '1440052548HQW131102199007042019', '201508239998101591467', '用户赎回', '1d', '1440147796HQW131102199007042019', '127.0.0.1', 'balance^8624.87^SAVING_POT', '8624.87', '', '0', '代收错误--余额不足', '1440305535', '1440305535');
