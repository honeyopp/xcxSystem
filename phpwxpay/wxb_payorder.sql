/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50554
Source Host           : localhost:3306
Source Database       : sql642661

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2018-01-16 00:03:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wxb_payorder
-- ----------------------------
DROP TABLE IF EXISTS `wxb_payorder`;
CREATE TABLE `wxb_payorder` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(30) NOT NULL,
  `trade_no` varchar(150) DEFAULT NULL COMMENT '交易号',
  `order_money` int(100) DEFAULT '0',
  `unit_name` varchar(10) NOT NULL,
  `pay_type` varchar(20) DEFAULT NULL COMMENT '支付方式',
  `state` int(2) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL,
  `update_time` int(10) DEFAULT '0',
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8;
