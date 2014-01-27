--  vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker:
-- 
-- Current Database: `swan_soft`-- 

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `swan_soft` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `swan_soft`;

-- {{{  table sequence_global

-- 
-- 维护全局的数据表的唯一序列号
-- 
-- table_name
-- 	维护序列号的表名称
-- sequence_id
-- 	自增长 ID

DROP TABLE IF EXISTS `sequence_global`;
CREATE TABLE `sequence_global` (
	`table_name` varchar(64) NOT NULL ,
	`sequence_id` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table sequence_device

-- 
-- 设备成员数据表的唯一序列号
-- 
-- device_id
-- 	设备 ID
-- table_name
-- 	维护序列号的表名称
-- sequence_id
-- 	自增长 ID

DROP TABLE IF EXISTS `sequence_device`;
CREATE TABLE `sequence_device` (
	`device_id` int(11) UNSIGNED NOT NULL ,
	`table_name` varchar(64) NOT NULL ,
	`sequence_id` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`device_id`,`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table sequence_monitor

-- 
-- 监控器数据表的唯一序列号
-- 
-- monitor_id
-- 	设备 ID
-- table_name
-- 	维护序列号的表名称
-- sequence_id
-- 	自增长 ID

DROP TABLE IF EXISTS `sequence_monitor`;
CREATE TABLE `sequence_monitor` (
	`monitor_id` int(11) UNSIGNED NOT NULL ,
	`table_name` varchar(64) NOT NULL ,
	`sequence_id` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`monitor_id`,`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
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

DROP TABLE IF EXISTS `device_basic`;
CREATE TABLE `device_basic` (
	`device_id` int(11) UNSIGNED NOT NULL ,
	`device_display_name` varchar(255) NOT NULL ,
	PRIMARY KEY (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table monitor_basic

-- 
-- 监控器管理
-- 
-- monitor_id
-- 	监控器 id
-- monitor_name
-- 	监控器名称
-- monitor_display_name
-- 	监控器显示名称

DROP TABLE IF EXISTS `monitor_basic`;
CREATE TABLE `monitor_basic` (
	`monitor_id` int(11) UNSIGNED NOT NULL ,
	`monitor_name` varchar(255) NOT NULL ,
	`monitor_display_name` varchar(255) NOT NULL ,
	PRIMARY KEY (`monitor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table monitor_attribute

-- 
-- 监控器属性管理
-- 
-- attr_id
-- 	监控器属性 id
-- monitor_id
-- 	监控器 id
-- attr_name
-- 	属性名称
-- attr_display_name
-- 	属性显示名称
-- form_type
-- 	属性表单类型
-- form_data
-- 	属性表单数据

DROP TABLE IF EXISTS `monitor_attribute`;
CREATE TABLE `monitor_attribute` (
	`attr_id` int(11) UNSIGNED NOT NULL ,
	`monitor_id` int(11) UNSIGNED NOT NULL ,
	`attr_name` varchar(255) NOT NULL ,
	`attr_display_name` varchar(255) NOT NULL ,
	`form_type` varchar(255) NOT NULL ,
	`form_data` varchar(255) NOT NULL ,
	PRIMARY KEY (`attr_id`,`monitor_id`)
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
-- device_id
-- 	设备 id
-- monitor_id
-- 	监控器 id
-- value
-- 	属性值

DROP TABLE IF EXISTS `monitor_params`;
CREATE TABLE `monitor_params` (
	`value_id` int(11) UNSIGNED NOT NULL ,
	`attr_id` int(11) UNSIGNED NOT NULL ,
	`device_id` int(11) UNSIGNED NOT NULL ,
	`monitor_id` int(11) UNSIGNED NOT NULL ,
	`value` varchar(255) NOT NULL ,
	PRIMARY KEY (`value_id`,`attr_id`,`monitor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table monitor_metric

-- 
-- 监控器收集数据项
-- 
-- metric_id
-- 	数据项 id
-- metric_name
-- 	数据项名称
-- monitor_id
-- 	监控器 id
-- collect_every
-- 	轮询周期
-- time_threshold
-- 	收集数据超时
-- title
-- 	数据项标题

DROP TABLE IF EXISTS `monitor_metric`;
CREATE TABLE `monitor_metric` (
	`metric_id` int(11) UNSIGNED NOT NULL ,
	`metric_name` varchar(255) NOT NULL ,
	`monitor_id` int(11) UNSIGNED NOT NULL ,
	`collect_every` int(11) UNSIGNED NOT NULL ,
	`time_threshold` int(11) UNSIGNED NOT NULL ,
	`title` varchar(255) NOT NULL ,
	PRIMARY KEY (`metric_id`,`monitor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 