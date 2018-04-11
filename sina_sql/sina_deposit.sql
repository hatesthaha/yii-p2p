/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : mmoney2

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-08-26 08:49:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sina_deposit`
-- ----------------------------
DROP TABLE IF EXISTS `sina_deposit`;
CREATE TABLE `sina_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `identity_id` varchar(255) DEFAULT NULL COMMENT '用户标识信息',
  `out_trade_no` varchar(255) DEFAULT NULL COMMENT '交易订单号',
  `account_type` varchar(255) DEFAULT NULL COMMENT '账户类型',
  `amount` varchar(25) DEFAULT NULL COMMENT '金额',
  `payer_ip` varchar(25) DEFAULT NULL COMMENT '付款用户IP地址',
  `pay_method` varchar(255) DEFAULT NULL COMMENT '支付方式',
  `ticket` varchar(255) DEFAULT NULL,
  `validate_code` varchar(25) DEFAULT NULL COMMENT '验证码',
  `status` tinyint(1) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='新浪充值记录';

-- ----------------------------
-- Records of sina_deposit
-- ----------------------------
INSERT INTO `sina_deposit` VALUES ('1', '44', '1440032322HQW131102199007042019', '201508205049575029302', 'SAVING_POT', '0.01', '127.0.0.1', 'binding_pay^0.01^31131', '582ecfa36aab4b789132515707a7500b', '', '1', '等待确认', '1440065060', '1440065060');
INSERT INTO `sina_deposit` VALUES ('2', '44', '1440032322HQW131102199007042019', '201508209898515551843', 'SAVING_POT', '0.01', '127.0.0.1', 'binding_pay^0.01^31131', '874bf72629104255bd35639a4d45afee', '', '1', '等待确认', '1440065165', '1440065165');
INSERT INTO `sina_deposit` VALUES ('3', '44', '1440032322HQW131102199007042019', '201508214855102519723', 'SAVING_POT', '0.01', '127.0.0.1', 'binding_pay^0.01^31131', '8111c69bccc7454c90a96ad9cffa9bad', '', '1', '等待确认', '1440121444', '1440121444');
INSERT INTO `sina_deposit` VALUES ('4', '44', '1440032322HQW131102199007042019', '201508215757544842925', 'SAVING_POT', '0.01', '127.0.0.1', 'binding_pay^0.01^31131', 'e506219dd36040a8a03b1c44b580395e', '', '1', '等待确认', '1440121643', '1440121643');
INSERT INTO `sina_deposit` VALUES ('5', '44', '1440032322HQW131102199007042019', '201508215410010244396', 'SAVING_POT', '0.01', '127.0.0.1', 'binding_pay^0.01^31131', '9ca8841aa3224fb9b92c2cf6bbb62ba6', '219123', '0', '请求号重复', '1440122794', '1440123062');
INSERT INTO `sina_deposit` VALUES ('6', null, null, null, null, null, null, null, null, '219123', '2', 'success', '1440122982', '1440122982');
INSERT INTO `sina_deposit` VALUES ('7', '44', '1440032322HQW131102199007042019', '201508219810049981245', 'SAVING_POT', '0.01', '127.0.0.1', 'binding_pay^0.01^31131', 'dc7a57ba64fa4a2985408bef17026c39', '378446', '2', 'success', '1440123102', '1440123141');
INSERT INTO `sina_deposit` VALUES ('8', '44', '1440032322HQW131102199007042019', '201508225054485341793', 'SAVING_POT', '10000', '127.0.0.1', 'binding_pay^10000^31131', '9a1f456ffc4c47cea4cf6efd81d0d471', '', '1', '等待确认', '1440205573', '1440205573');
INSERT INTO `sina_deposit` VALUES ('9', '44', '1440032322HQW131102199007042019', '201508221005197103567', 'SAVING_POT', '10000', '127.0.0.1', 'binding_pay^10000^31131', '1fc411efa34e4fd597a063937ed107b3', '', '1', '等待确认', '1440205620', '1440205620');
INSERT INTO `sina_deposit` VALUES ('10', '44', '1440032322HQW131102199007042019', '201508225210110154463', 'SAVING_POT', '100', '127.0.0.1', 'binding_pay^100^31131', '51b8f745c5164c74ade373ffa4d0dfee', '530837', '2', 'success', '1440205687', '1440205739');
INSERT INTO `sina_deposit` VALUES ('11', '44', '1440032322HQW131102199007042019', '201508225448565060335', 'SAVING_POT', '100', '127.0.0.1', 'binding_pay^100^31131', '2432116ff68a421ba995032d5d04f73b', '138430', '2', 'success', '1440205848', '1440205890');
INSERT INTO `sina_deposit` VALUES ('12', '44', '1440032322HQW131102199007042019', '201508221015098555016', 'SAVING_POT', '1000', '127.0.0.1', 'binding_pay^1000^31131', 'd6a3c567fea6450aa52ceb3f9efed092', '240151', '2', 'success', '1440209728', '1440209774');
INSERT INTO `sina_deposit` VALUES ('13', '44', '1440052548HQW131102199007042019', '201508241011014821424', 'SAVING_POT', '1000', '127.0.0.1', 'binding_pay^1000^31193', 'da8c35d036914b0fb6c26fa1fb92a27e', '', '1', '等待确认', '1440378336', '1440378336');
INSERT INTO `sina_deposit` VALUES ('14', '44', '1440052548HQW131102199007042019', '201508249710199118099', 'SAVING_POT', '1000', '127.0.0.1', 'binding_pay^1000^31193', '8c372ffbe20c474aaab04e703e730cb1', '377213', '2', 'success', '1440378429', '1440378480');
