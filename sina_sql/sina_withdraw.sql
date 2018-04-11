/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : mmoney2

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-23 12:36:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sina_withdraw`
-- ----------------------------
DROP TABLE IF EXISTS `sina_withdraw`;
CREATE TABLE `sina_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `out_trade_no` varchar(255) DEFAULT '' COMMENT '提现订单号',
  `identity_id` varchar(255) DEFAULT NULL COMMENT '用户标识信息',
  `card_id` varchar(25) DEFAULT NULL COMMENT '绑定银行卡号',
  `site_balance` double(13,2) DEFAULT NULL COMMENT '网站账户金额',
  `sina_balance` double(13,2) DEFAULT NULL COMMENT '新浪账户金额',
  `money` double(13,2) DEFAULT NULL COMMENT '用户提现金额',
  `money_fund` double(13,2) DEFAULT NULL COMMENT '货币基金收益',
  `money_site` double(13,2) DEFAULT NULL COMMENT '网站应赎回金额',
  `money_sina` double(13,2) DEFAULT NULL COMMENT '新浪托管应该赎回的金额',
  `type` tinyint(1) DEFAULT NULL COMMENT '提现类型',
  `status` tinyint(1) DEFAULT NULL COMMENT '提现状态',
  `msg` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sina_withdraw
-- ----------------------------
INSERT INTO `sina_withdraw` VALUES ('1', '44', '201508225756525660182', '1440052548HQW131102199007042019', '31193', '898.00', '1377.00', '1.00', '479.00', '0.00', '0.00', '1', '3', '提现金额小于余额', '1440237962', '1440237962');
INSERT INTO `sina_withdraw` VALUES ('2', '44', '201508225110055434436', '1440052548HQW131102199007042019', '31193', '898.00', '1377.00', '1.00', '479.00', '0.00', '0.00', '1', '3', '提现金额小于余额', '1440238053', '1440238053');
INSERT INTO `sina_withdraw` VALUES ('3', '44', '201508225448101128106', '1440052548HQW131102199007042019', '31193', '898.00', '1377.00', '1.00', '479.00', '0.00', '0.00', '1', '3', '提现金额小于余额', '1440239543', '1440239543');
