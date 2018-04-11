/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : mmoney2

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-26 08:49:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sina_batchpay`
-- ----------------------------
DROP TABLE IF EXISTS `sina_batchpay`;
CREATE TABLE `sina_batchpay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `out_pay_no` varchar(255) DEFAULT NULL COMMENT '批量代付订单号',
  `collect_pay_no` varchar(255) DEFAULT NULL COMMENT '代付对应的代收订单号',
  `trade_list` text COMMENT '交易参数',
  `status` tinyint(1) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sina_batchpay
-- ----------------------------
INSERT INTO `sina_batchpay` VALUES ('1', '201508259754515586983', '201508235753565114051', '201508259754515502284~1440236114HQW131102199007042019~UID~SAVING_POT~1~~代收投资$201508259754561034273~1440144466HQW131102199007042019~UID~SAVING_POT~2~~代收投资$201508259754574962557~1440147796HQW131102199007042019~UID~SAVING_POT~3~~代收投资', '1', '代付成功', '1440492731', '1440492731');
