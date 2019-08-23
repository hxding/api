/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : pay

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-08-23 18:10:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `product_user_id` int(11) NOT NULL COMMENT '产品游戏ID',
  `credit_level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '信用等级',
  `star_level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '星级等级',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 正常 0 删除',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES ('1', '1', '1', '0', '0', '1', '2019-08-16 13:23:46', '2019-08-16 13:23:44');
INSERT INTO `customers` VALUES ('4', '1', '2067768', '3', '1', '1', '2019-08-19 01:48:57', '2019-08-19 01:48:57');

-- ----------------------------
-- Table structure for deposits
-- ----------------------------
DROP TABLE IF EXISTS `deposits`;
CREATE TABLE `deposits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `customer_id` int(11) NOT NULL COMMENT '客户ID',
  `currency_type` varchar(10) NOT NULL COMMENT '货币类型',
  `amount` decimal(11,2) NOT NULL COMMENT '金额',
  `real_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '实际金额',
  `order_sn` varchar(35) NOT NULL COMMENT '订单号',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `depositor` varchar(15) NOT NULL COMMENT '存款人',
  `receipt_bank_name` varchar(50) NOT NULL DEFAULT '' COMMENT '收款银行名称',
  `receipt_account` varchar(50) NOT NULL DEFAULT '' COMMENT '收款账号',
  `receipt_depositor` varchar(50) NOT NULL DEFAULT '' COMMENT '收款人',
  `deposit_type` varchar(1) NOT NULL COMMENT '存款类型',
  `merchant_order_sn` varchar(35) NOT NULL DEFAULT '' COMMENT '商户订单号',
  `product_order_sn` varchar(35) NOT NULL DEFAULT '' COMMENT '产品订单号',
  `deposit_channel_code` int(11) NOT NULL COMMENT '存款渠道类型',
  `notification_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '通知游戏状态',
  `notification_message` varchar(35) NOT NULL DEFAULT '' COMMENT '游戏回调信息',
  `notification_time` datetime DEFAULT NULL COMMENT '通知游戏时间',
  `original_currency_type` varchar(10) NOT NULL DEFAULT 'CNY' COMMENT '原币种类型',
  `original_currency_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '原币种金额',
  `original_currency_exchange_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原币种到现币种到汇率',
  `merchant_id` int(11) NOT NULL COMMENT '商户ID',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_order_sn` (`order_sn`) USING HASH COMMENT '订单号唯一'
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of deposits
-- ----------------------------
INSERT INTO `deposits` VALUES ('1', '1', '1', 'CNY', '8888.00', '0.00', 'A92EP19081915344743', '0', '订单', '中国农业银行', '6228480148852090676', '周洁莉', '0', '', '', '1', '0', '', null, 'CNY', '0.00', '0.00', '1', '2019-08-19 10:00:03', '2019-08-19 10:00:03');
INSERT INTO `deposits` VALUES ('2', '1', '1', 'CNY', '8888.00', '0.00', 'A92EP19081918195935', '0', '订单', '中国农业银行', '6228480148853011572', '李林清', '0', '', '', '1', '0', '', null, 'CNY', '0.00', '0.00', '1', '2019-08-19 10:20:02', '2019-08-19 10:20:02');
INSERT INTO `deposits` VALUES ('3', '1', '1', 'CNY', '88.00', '0.00', 'A92EP19082010264165', '0', 'TEST', '中国邮政储蓄银行', '6217995950006529090', '陈卫双', '0', '', '', '1', '0', '', null, 'CNY', '0.00', '0.00', '1', '2019-08-20 02:26:42', '2019-08-20 02:26:42');
INSERT INTO `deposits` VALUES ('4', '1', '1', 'CNY', '88.00', '0.00', 'A92EP19082010290172', '0', 'TEST', '中国邮政储蓄银行', '6217995950006529090', '陈卫双', '0', '', '', '1', '0', '', null, 'CNY', '0.00', '0.00', '1', '2019-08-20 02:29:02', '2019-08-20 02:29:02');
INSERT INTO `deposits` VALUES ('5', '1', '1', 'CNY', '88.00', '0.00', 'A92EP19082010373894', '0', 'TEST', '中国邮政储蓄银行', '6217995950006529090', '陈卫双', '0', '', '', '1', '0', '', null, 'CNY', '0.00', '0.00', '1', '2019-08-20 02:37:38', '2019-08-20 02:37:38');
INSERT INTO `deposits` VALUES ('9', '1', '4', 'CNY', '150.00', '0.00', '103190823061631D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 06:16:31', '2019-08-23 06:16:31');
INSERT INTO `deposits` VALUES ('10', '1', '4', 'CNY', '150.00', '0.00', '103190823061722D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 06:17:22', '2019-08-23 06:17:22');
INSERT INTO `deposits` VALUES ('11', '1', '4', 'CNY', '150.00', '0.00', '103190823061856D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 06:18:56', '2019-08-23 06:18:56');
INSERT INTO `deposits` VALUES ('12', '1', '4', 'CNY', '150.00', '0.00', '103190823074452D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:44:52', '2019-08-23 07:44:52');
INSERT INTO `deposits` VALUES ('13', '1', '4', 'CNY', '150.00', '0.00', '103190823074509D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:45:09', '2019-08-23 07:45:09');
INSERT INTO `deposits` VALUES ('14', '1', '4', 'CNY', '150.00', '0.00', '103190823074525D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:45:25', '2019-08-23 07:45:25');
INSERT INTO `deposits` VALUES ('15', '1', '4', 'CNY', '150.00', '0.00', '103190823074559D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:45:59', '2019-08-23 07:45:59');
INSERT INTO `deposits` VALUES ('16', '1', '4', 'CNY', '150.00', '0.00', '103190823074824D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:48:24', '2019-08-23 07:48:24');
INSERT INTO `deposits` VALUES ('17', '1', '4', 'CNY', '150.00', '0.00', '103190823075138D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:51:38', '2019-08-23 07:51:38');
INSERT INTO `deposits` VALUES ('18', '1', '4', 'CNY', '150.00', '0.00', '103190823075702D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:02', '2019-08-23 07:57:02');
INSERT INTO `deposits` VALUES ('19', '1', '4', 'CNY', '150.00', '0.00', '103190823075705D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:05', '2019-08-23 07:57:05');
INSERT INTO `deposits` VALUES ('20', '1', '4', 'CNY', '150.00', '0.00', '103190823075706D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:06', '2019-08-23 07:57:06');
INSERT INTO `deposits` VALUES ('21', '1', '4', 'CNY', '150.00', '0.00', '103190823075707D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:07', '2019-08-23 07:57:07');
INSERT INTO `deposits` VALUES ('22', '1', '4', 'CNY', '150.00', '0.00', '103190823075708D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:08', '2019-08-23 07:57:08');
INSERT INTO `deposits` VALUES ('24', '1', '4', 'CNY', '150.00', '0.00', '103190823075709D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:09', '2019-08-23 07:57:09');
INSERT INTO `deposits` VALUES ('25', '1', '4', 'CNY', '150.00', '0.00', '103190823075711D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:11', '2019-08-23 07:57:11');
INSERT INTO `deposits` VALUES ('26', '1', '4', 'CNY', '150.00', '0.00', '103190823075714D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:14', '2019-08-23 07:57:14');
INSERT INTO `deposits` VALUES ('27', '1', '4', 'CNY', '150.00', '0.00', '103190823075727D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:27', '2019-08-23 07:57:27');
INSERT INTO `deposits` VALUES ('28', '1', '4', 'CNY', '150.00', '0.00', '103190823075728D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:28', '2019-08-23 07:57:28');
INSERT INTO `deposits` VALUES ('29', '1', '4', 'CNY', '150.00', '0.00', '103190823075744D1', '0', '2067768', '', '', '', '0', '', '', '101', '0', '', null, 'CNY', '0.00', '0.00', '2', '2019-08-23 07:57:44', '2019-08-23 07:57:44');
INSERT INTO `deposits` VALUES ('30', '1', '4', 'CNY', '300.00', '0.00', 'A9219082316444472', '0', '2067768', '', '', '', '0', '', '', '6', '0', '', null, 'CNY', '0.00', '0.00', '1', '2019-08-23 09:01:05', '2019-08-23 09:01:05');

-- ----------------------------
-- Table structure for deposit_channels
-- ----------------------------
DROP TABLE IF EXISTS `deposit_channels`;
CREATE TABLE `deposit_channels` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `merchant_id` int(11) NOT NULL COMMENT '商户ID',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `code` varchar(10) NOT NULL COMMENT '渠道标识',
  `channel` varchar(25) NOT NULL DEFAULT '' COMMENT 'T 存款标识',
  `name` varchar(25) NOT NULL COMMENT '名称',
  `recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐',
  `min_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '最小限额',
  `max_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '最大限额',
  `exchange_rate` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '汇率',
  `category` varchar(255) DEFAULT '0' COMMENT '支付类别 0 在线支付 1 手工存款 2 虚拟货币',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付货币类型  0 CNY 1 BTC 2 USDT 3 VND',
  `sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='存款渠道表';

-- ----------------------------
-- Records of deposit_channels
-- ----------------------------
INSERT INTO `deposit_channels` VALUES ('1', '1', '1', '1', '', 'bq', '0', '100.00', '3000.00', '0.00', '0', '0', '0', '2019-08-20 10:32:29', '2019-08-20 10:32:31');
INSERT INTO `deposit_channels` VALUES ('2', '1', '1', '6', '', 'online', '0', '100.00', '3000.00', '0.00', '0', '0', '0', '2019-08-20 10:32:29', '2019-08-20 10:32:31');
INSERT INTO `deposit_channels` VALUES ('3', '2', '1', '101', 'AliPay', 'online', '0', '100.00', '3000.00', '0.00', '0', '0', '0', '2019-08-20 10:32:29', '2019-08-20 10:32:31');

-- ----------------------------
-- Table structure for merchants
-- ----------------------------
DROP TABLE IF EXISTS `merchants`;
CREATE TABLE `merchants` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `platform_id` int(11) NOT NULL COMMENT '平台ID',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `code` varchar(25) NOT NULL COMMENT '标识',
  `account` varchar(25) DEFAULT NULL COMMENT '账号',
  `password` varchar(25) DEFAULT NULL COMMENT '密码',
  `league_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'T 联盟ID',
  `club_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'T 俱乐部ID',
  `key` varchar(50) NOT NULL COMMENT 'key',
  `domain` varchar(50) NOT NULL COMMENT '请求域名',
  `callback_url` varchar(50) NOT NULL COMMENT '回调域名',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='商户表';

-- ----------------------------
-- Records of merchants
-- ----------------------------
INSERT INTO `merchants` VALUES ('1', '1', '1', 'A92', 'adminfromfunding', 'Fz09feaj9alepv', '1', '1', 'a92yui124', 'http://pay.apap8.com', 'http://api.aspk8.com', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `merchants` VALUES ('2', '1', '1', '103', 'adminfromfunding', 'Fz09feaj9alepv', '1', '1', 'a92yui124', 'http://officeapi.telfapay.com', 'sss', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '产品ID',
  `name` varchar(15) NOT NULL COMMENT '产品名称',
  `domain` varchar(50) NOT NULL COMMENT '产品的域名',
  `key` varchar(50) NOT NULL COMMENT '密钥',
  `deposit_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '存款状态',
  `withdraw_status` tinyint(1) NOT NULL COMMENT '取款状态',
  `deposit_callback` varchar(30) NOT NULL,
  `withdraw_callback` varchar(30) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='产品表';

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('1', 'dy', 'www.baidu.com', 'xxxxxxxxxxxx', '1', '1', 'callback', 'callback', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
