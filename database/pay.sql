-- 客户表
DROP TABLE IF EXISTS customers;
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
)COMMENT='客户表';

-- 产品表
DROP TABLE IF EXISTS products;
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
)COMMENT='产品表';


-- 商户表
DROP TABLE IF EXISTS merchants;
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
INSERT INTO `pay`.`merchants` (`id`, `platform_id`, `product_id`, `code`, `account`, `password`, `key`, `domain`, `callback_url`, `updated_at`, `created_at`) VALUES ('1', '1', '1', 'A92', 'adminfromfunding', 'Fz09feaj9alepv', 'a92yui124', 'http://pay.apap8.com', 'sss', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- 存款渠道表
DROP TABLE IF EXISTS deposit_channels;
CREATE TABLE `deposit_channels` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `merchant_id` int(11) NOT NULL COMMENT '商户ID',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `code` varchar(10) NOT NULL COMMENT '渠道标识',
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

--存款表
DROP TABLE IF EXISTS deposits;
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
)COMMENT='存款表';
