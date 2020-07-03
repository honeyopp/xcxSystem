-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 年 02 月 19 日 14:34
-- 服务器版本: 5.5.38
-- PHP 版本: 5.4.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: ``
--

-- --------------------------------------------------------

--
-- 表的结构 `wxb_payorder`
--

CREATE TABLE IF NOT EXISTS `wxb_payorder` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(30) NOT NULL,
  `trade_no` varchar(150) DEFAULT NULL COMMENT '交易号',
  `order_money` int(100) DEFAULT '',
  `unit_name` varchar(10) NOT NULL,
  `pay_type` varchar(20) DEFAULT NULL COMMENT '支付方式',
  `state` int(2) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL,
  `update_time` int(10) DEFAULT '0',
  `member_id` int(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `wxb_payorder`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
