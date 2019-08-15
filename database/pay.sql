-- 客户表
CREATE TABLE `customers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '客户主键',
  `product_id` int(11) NOT NULL COMMENT '游戏产品ID',
  `product_user_id` int(11) NOT NULL COMMENT '游戏用户ID',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='客户表'

-- 产品表
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='产品表'


-- 商户表
CREATE TABLE `merchants` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `platform_id` int(11) NOT NULL COMMENT '平台ID',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `code` varchar(25) NOT NULL COMMENT '标识',
  `account` varchar(25) DEFAULT NULL COMMENT '账号',
  `password` varchar(25) DEFAULT NULL COMMENT '密码',
  `key` varchar(50) NOT NULL COMMENT 'key',
  `domain` varchar(50) NOT NULL COMMENT '请求域名',
  `callback_url` varchar(50) NOT NULL COMMENT '回调域名',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商户表'

-- 存款渠道表
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
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型',
  `sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='存款渠道表'

--存款表
CREATE TABLE `deposits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `currency_type` varchar(10) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `real_amount` decimal(11,2) NOT NULL,
  `order_sn` varchar(35) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `depositor` varchar(15) NOT NULL,
  `deposit_account` varchar(50) NOT NULL,
  `deposit_type` varchar(1) NOT NULL,
  `merchant_order_sn` varchar(35) NOT NULL,
  `product_order_sn` varchar(35) NOT NULL,
  `deposit_channel_code` int(11) NOT NULL,
  `notification_status` tinyint(1) NOT NULL COMMENT '通知游戏状态',
  `notification_message` varchar(35) NOT NULL COMMENT '游戏回调信息',
  `notification_time` datetime DEFAULT NULL COMMENT '通知游戏时间',
  `original_currency_type` varchar(10) NOT NULL COMMENT '原币种类型',
  `original_currency_amount` decimal(11,2) NOT NULL COMMENT '原币种金额',
  `original_currency_exchange_rate` decimal(10,0) NOT NULL COMMENT '原币种到现币种到汇率',
  `merchant_id` int(11) NOT NULL COMMENT '商户ID',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
