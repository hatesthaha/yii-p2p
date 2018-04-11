/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : mmoney2

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-24 09:54:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sina_invest`
-- ----------------------------
DROP TABLE IF EXISTS `sina_invest`;
CREATE TABLE `sina_invest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `identity_id` varchar(255) DEFAULT NULL,
  `out_trade_no` varchar(255) DEFAULT NULL COMMENT '订单号',
  `summary` varchar(255) DEFAULT NULL COMMENT '订单简介',
  `trade_close_time` varchar(25) DEFAULT NULL COMMENT '订单有效时间',
  `payer_ip` varchar(50) DEFAULT NULL COMMENT '支付ip',
  `pay_type` varchar(25) DEFAULT NULL COMMENT '支付方式',
  `account_type` varchar(25) DEFAULT NULL COMMENT '账户类型',
  `goods_id` varchar(25) DEFAULT NULL COMMENT '标的标号',
  `money` double(13,2) DEFAULT NULL COMMENT '投资金额',
  `status` tinyint(1) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `payee_out_trade_no` varchar(255) DEFAULT NULL COMMENT '网站收款订单号',
  `payee_identity_id` varchar(255) DEFAULT NULL COMMENT '收款人标识信息',
  `payee_account_type` varchar(50) DEFAULT NULL COMMENT '收款人账户信息',
  `payee_amount` double(13,2) DEFAULT NULL COMMENT '收款金额',
  `payee_summary` varchar(50) DEFAULT NULL COMMENT '收款简介',
  `refund_out_trade_no` varchar(255) DEFAULT NULL COMMENT '退款订单号',
  `refund_amount` double(13,2) DEFAULT NULL COMMENT '退款金额',
  `refund_summary` varchar(255) DEFAULT NULL COMMENT '退款摘要',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='新浪投资记录';

-- ----------------------------
-- Records of sina_invest
-- ----------------------------
INSERT INTO `sina_invest` VALUES ('1', '44', '1440032322HQW131102199007042019', '201508215250495763763', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '1.00', '0', '新浪账户余额不足', null, null, null, null, null, null, null, null, '1440152903', '1440152903');
INSERT INTO `sina_invest` VALUES ('2', '44', '1440032322HQW131102199007042019', '201508215757545269162', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '0.01', '1', '投资成功', null, null, null, null, null, null, null, null, '1440152956', '1440152956');
INSERT INTO `sina_invest` VALUES ('3', '44', '1440032322HQW131102199007042019', '201508225348534819873', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '100.00', '1', '投资成功', null, null, null, null, null, null, null, null, '1440205976', '1440205976');
INSERT INTO `sina_invest` VALUES ('4', '44', '1440032322HQW131102199007042019', '201508221021024894045', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '100.00', '1', '投资成功', null, null, null, null, null, null, null, null, '1440206418', '1440206418');
INSERT INTO `sina_invest` VALUES ('5', '44', '1440032322HQW131102199007042019', '201508221015599544679', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '100.00', '0', '新浪账户余额不足', null, null, null, null, null, null, null, null, '1440209569', '1440209569');
INSERT INTO `sina_invest` VALUES ('6', '44', '1440032322HQW131102199007042019', '201508225152101526226', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '100.00', '0', '新浪账户余额不足', null, null, null, null, null, null, null, null, '1440209653', '1440209653');
INSERT INTO `sina_invest` VALUES ('7', '44', '1440032322HQW131102199007042019', '201508221019949924038', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '100.00', '2', '收款成功', '201508221019898485412', '1440147796HQW131102199007042019', 'SAVING_POT', '100.00', '123', null, null, null, '1440209906', '1440210001');
INSERT INTO `sina_invest` VALUES ('8', '44', '1440052548HQW131102199007042019', '201508229953559741888', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '100.00', '1', '投资成功', null, null, null, null, null, null, null, null, '1440227952', '1440227952');
INSERT INTO `sina_invest` VALUES ('9', '44', '1440052548HQW131102199007042019', '201508225155994831743', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '1.00', '1', '投资成功', null, null, null, null, null, null, null, null, '1440228262', '1440228262');
INSERT INTO `sina_invest` VALUES ('10', '44', '1440052548HQW131102199007042019', '201508235753565114051', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '10.00', '1', '投资成功', null, null, null, null, null, null, null, null, '1440295149', '1440295149');
INSERT INTO `sina_invest` VALUES ('11', '44', '1440052548HQW131102199007042019', '201508231025498153872', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '1.00', '1', '投资成功', null, null, null, null, null, null, null, null, '1440299235', '1440299235');
INSERT INTO `sina_invest` VALUES ('12', '44', '1440052548HQW131102199007042019', '201508245210155504523', '购买标的', '1d', '127.0.0.1', 'balance', 'SAVING_POT', '1', '100.00', '-3', '可退付款金额[0.00]小于退款金额[100.00]', null, null, null, null, null, '201508244848571033366', '100.00', '中间账户退款', '1440378535', '1440379873');
