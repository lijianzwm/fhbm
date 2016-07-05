

CREATE TABLE `fh_fahui` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(20) NOT NULL COMMENT '法会名称',
  `description` text NOT NULL COMMENT '法会描述',
  `starttime` datetime NOT NULL COMMENT '报名开始时间',
  `endtime` datetime NOT NULL COMMENT '报名结束时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `fh_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `fh_id` int(10) unsigned NOT NULL COMMENT '法会id',
  `order_no` varchar(30) NOT NULL UNIQUE COMMENT '订单号',
  `type` varchar(20) NOT NULL COMMENT '随喜类型',
  `pay_status` enum('NONE','PAYERROR','PAYSUCCESS','FINISH','DRAW') DEFAULT NULL COMMENT '支付状态',
  `pay_channel` enum('WXPAY','ALIPAY','OTHER') DEFAULT NULL COMMENT '支付渠道',
  `money` int NOT NULL COMMENT '随喜金额',
  `host_name` varchar(20) NOT NULL COMMENT '斋主姓名',
  `host_birthday` varchar(50) NOT NULL COMMENT '斋主农历生日',
  `phone` varchar(20) NOT NULL COMMENT '手机号码',
  `member` varchar(500) DEFAULT NULL COMMENT '其他成员',
  `address` varchar(500) NOT NULL COMMENT '居住地址',
  `huixiang` varchar(500) NOT NULL COMMENT '回向内容',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;