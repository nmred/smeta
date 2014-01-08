--  vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker:
-- 
-- Current Database: `swan_soft`-- 

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `swan_soft` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `swan_soft`;

-- {{{  table device_key

-- 
-- 监控的设备关键表
-- 
-- device_id
-- 	设备 id
-- device_name
-- 	设备名称(唯一)

DROP TABLE IF EXISTS `device_key`;
CREATE TABLE `device_key` (
	`device_id` int(11) UNSIGNED NOT NULL ,
	`device_name` varchar(255) NOT NULL ,
	PRIMARY KEY (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table device_basic

-- 
-- 监控的设备基本信息表
-- 
-- device_id
-- 	设备 id
-- device_display_name
-- 	设备显示名称
-- moniter_id
-- 	监视器 ID

DROP TABLE IF EXISTS `device_basic`;
CREATE TABLE `device_basic` (
	`device_id` int(11) UNSIGNED NOT NULL ,
	`device_display_name` varchar(255) NOT NULL ,
	`moniter_id` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table monitor_basic

-- 
-- 监控器管理
-- 
-- moniter_id
-- 	监控器 id
-- moniter_name
-- 	监控器名称
-- moniter_display_name
-- 	监控器显示名称

DROP TABLE IF EXISTS `monitor_basic`;
CREATE TABLE `monitor_basic` (
	`moniter_id` int(11) UNSIGNED NOT NULL ,
	`moniter_name` varchar(255) NOT NULL ,
	`moniter_display_name` varchar(255) NOT NULL ,
	PRIMARY KEY (`moniter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table monitor_attribute

-- 
-- 监控器属性管理
-- 
-- attr_id
-- 	监控器属性 id
-- moniter_id
-- 	监控器 id
-- attr_name
-- 	属性名称
-- attr_display_name
-- 	属性显示名称
-- form_type
-- 	属性表单类型

DROP TABLE IF EXISTS `monitor_attribute`;
CREATE TABLE `monitor_attribute` (
	`attr_id` int(11) UNSIGNED NOT NULL ,
	`moniter_id` int(11) UNSIGNED NOT NULL ,
	`attr_name` varchar(255) NOT NULL ,
	`attr_display_name` varchar(255) NOT NULL ,
	`form_type` varchar(255) NOT NULL ,
	PRIMARY KEY (`attr_id`,`moniter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table monitor_params

-- 
-- 监控器参数
-- 
-- value_id
-- 	属性值 id
-- attr_id
-- 	监控器属性 id
-- moniter_id
-- 	监控器 id
-- value
-- 	属性值

DROP TABLE IF EXISTS `monitor_params`;
CREATE TABLE `monitor_params` (
	`value_id` int(11) UNSIGNED NOT NULL ,
	`attr_id` int(11) UNSIGNED NOT NULL ,
	`moniter_id` int(11) UNSIGNED NOT NULL ,
	`value` varchar(255) NOT NULL ,
	PRIMARY KEY (`value_id`,`attr_id`,`moniter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
