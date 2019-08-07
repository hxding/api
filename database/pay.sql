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

--
