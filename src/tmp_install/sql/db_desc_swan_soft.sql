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
-- {{{  table sequence_graph

-- 
-- 图标定义数据表的唯一序列号
-- 
-- graph_id
-- 	图表 ID
-- table_name
-- 	维护序列号的表名称
-- sequence_id
-- 	自增长 ID

DROP TABLE IF EXISTS `sequence_graph`;
CREATE TABLE `sequence_graph` (
	`graph_id` int(11) UNSIGNED NOT NULL ,
	`table_name` varchar(64) NOT NULL ,
	`sequence_id` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`graph_id`,`table_name`)
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
-- host_name
-- 	设备主机地址

DROP TABLE IF EXISTS `device_basic`;
CREATE TABLE `device_basic` (
	`device_id` int(11) UNSIGNED NOT NULL ,
	`device_display_name` varchar(255) NOT NULL ,
	`host_name` varchar(255) NOT NULL ,
	PRIMARY KEY (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table device_monitor

-- 
-- 监控的设备的监控器
-- 
-- dm_id
-- 	设备监控器 id
-- dm_name
-- 	设备监控器名称
-- device_id
-- 	设备 id
-- monitor_id
-- 	监控器 id

DROP TABLE IF EXISTS `device_monitor`;
CREATE TABLE `device_monitor` (
	`dm_id` int(11) UNSIGNED NOT NULL ,
	`dm_name` varchar(255) NOT NULL ,
	`device_id` int(11) UNSIGNED NOT NULL ,
	`monitor_id` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`dm_id`,`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table device_monitor_params

-- 
-- 监控器参数
-- 
-- attr_id
-- 	监控器属性 id
-- device_id
-- 	设备 id
-- dm_id
-- 	设备监控器 id
-- value
-- 	属性值

DROP TABLE IF EXISTS `device_monitor_params`;
CREATE TABLE `device_monitor_params` (
	`attr_id` int(11) UNSIGNED NOT NULL ,
	`device_id` int(11) UNSIGNED NOT NULL ,
	`dm_id` int(11) UNSIGNED NOT NULL ,
	`value` varchar(255) NOT NULL ,
	PRIMARY KEY (`attr_id`,`device_id`,`dm_id`)
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
-- steps
-- 	监控器存储 rrd 的间隔时间 (秒)

DROP TABLE IF EXISTS `monitor_basic`;
CREATE TABLE `monitor_basic` (
	`monitor_id` int(11) UNSIGNED NOT NULL ,
	`monitor_name` varchar(255) NOT NULL ,
	`monitor_display_name` varchar(255) NOT NULL ,
	`steps` int(11) UNSIGNED NOT NULL ,
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
-- attr_default
-- 	属性默认值

DROP TABLE IF EXISTS `monitor_attribute`;
CREATE TABLE `monitor_attribute` (
	`attr_id` int(11) UNSIGNED NOT NULL ,
	`monitor_id` int(11) UNSIGNED NOT NULL ,
	`attr_name` varchar(255) NOT NULL ,
	`attr_display_name` varchar(255) NOT NULL ,
	`form_type` varchar(255) NOT NULL ,
	`form_data` varchar(255) NOT NULL ,
	`attr_default` varchar(255) NOT NULL ,
	PRIMARY KEY (`attr_id`,`monitor_id`)
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
-- tmax
-- 	没有收到数据的最长时间
-- dst_type
-- 	数据入库处理规则
-- vmin
-- 	数据的最小值
-- vmax
-- 	数据的最大值
-- unit
-- 	数据项的单位
-- title
-- 	数据项标题

DROP TABLE IF EXISTS `monitor_metric`;
CREATE TABLE `monitor_metric` (
	`metric_id` int(11) UNSIGNED NOT NULL ,
	`metric_name` varchar(255) NOT NULL ,
	`monitor_id` int(11) UNSIGNED NOT NULL ,
	`collect_every` int(11) UNSIGNED NOT NULL ,
	`time_threshold` int(11) UNSIGNED NOT NULL ,
	`tmax` int(11) UNSIGNED NOT NULL ,
	`dst_type` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
	`vmin` varchar(255) NOT NULL DEFAULT 'U',
	`vmax` varchar(255) NOT NULL DEFAULT 'U',
	`unit` varchar(255) NOT NULL ,
	`title` varchar(255) NOT NULL ,
	PRIMARY KEY (`metric_id`,`monitor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table monitor_archive

-- 
-- 监控器数据归档规则
-- 
-- archive_id
-- 	归档 id
-- monitor_id
-- 	监控器 id
-- title
-- 	归档规则标题
-- cf_type
-- 	归档运算类型
-- xff
-- 	无效数据判断界限
-- steps
-- 	归档一个数据点合并原始数据点个数
-- rows
-- 	一共归档条目数

DROP TABLE IF EXISTS `monitor_archive`;
CREATE TABLE `monitor_archive` (
	`archive_id` int(11) UNSIGNED NOT NULL ,
	`monitor_id` int(11) UNSIGNED NOT NULL ,
	`title` varchar(255) NOT NULL ,
	`cf_type` int(11) UNSIGNED NOT NULL ,
	`xff` varchar(255) NOT NULL DEFAULT '0.5',
	`steps` int(11) UNSIGNED NOT NULL ,
	`rows` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`archive_id`,`monitor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table graph_basic

-- 
-- 监控器绘图配置
-- 
-- graph_id
-- 	绘图配置 id
-- scope_type
-- 	该绘图配置作用范围 1: 全局 2：设备 3: 允许跨设备全局显示 4: 允许跨设备在提及的设备中显示
-- scope_ids
-- 	作用范围 IDS
-- title
-- 	图片的顶部标题
-- vlabel
-- 	图片的垂直的标题
-- desc
-- 	图片的描述信息
-- steps
-- 	绘图的间隔点
-- start
-- 	绘图的起始点
-- end
-- 	绘图的终点

DROP TABLE IF EXISTS `graph_basic`;
CREATE TABLE `graph_basic` (
	`graph_id` int(11) UNSIGNED NOT NULL ,
	`scope_type` tinyint(1) NOT NULL DEFAULT '1',
	`scope_ids` varchar(255) NOT NULL ,
	`title` varchar(255) NOT NULL ,
	`vlabel` varchar(255) NOT NULL ,
	`desc` varchar(255) NOT NULL ,
	`steps` int(11) UNSIGNED NOT NULL ,
	`start` int(11) UNSIGNED NOT NULL ,
	`end` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`graph_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table graph_def

-- 
-- 监控器绘图数据项定义
-- 
-- def_id
-- 	绘图数据项定义 id
-- graph_id
-- 	绘图配置 id
-- name
-- 	数据项的 name
-- ds_name
-- 	RRD 数据库的数据源名
-- cf_type
-- 	归并计算公式类型

DROP TABLE IF EXISTS `graph_def`;
CREATE TABLE `graph_def` (
	`def_id` int(11) UNSIGNED NOT NULL ,
	`graph_id` int(11) UNSIGNED NOT NULL ,
	`name` varchar(255) NOT NULL ,
	`ds_name` varchar(255) NOT NULL ,
	`cf_type` tinyint(1) UNSIGNED NOT NULL ,
	PRIMARY KEY (`graph_id`,`def_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table graph_cdef

-- 
-- 监控器绘图计算数据项定义
-- 
-- cdef_id
-- 	绘图计算数据项定义 id
-- graph_id
-- 	绘图配置 id
-- name
-- 	数据项的 name
-- operator
-- 	计算表达式

DROP TABLE IF EXISTS `graph_cdef`;
CREATE TABLE `graph_cdef` (
	`cdef_id` int(11) UNSIGNED NOT NULL ,
	`graph_id` int(11) UNSIGNED NOT NULL ,
	`name` varchar(255) NOT NULL ,
	`operator` varchar(255) NOT NULL ,
	PRIMARY KEY (`graph_id`,`cdef_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table graph_graph

-- 
-- 监控器绘图定义
-- 
-- dgraph_id
-- 	绘图定义 id
-- graph_id
-- 	绘图配置 id
-- data_id
-- 	数据项 id
-- color
-- 	图标的颜色
-- 图例说明
-- 	图例说明描述

DROP TABLE IF EXISTS `graph_graph`;
CREATE TABLE `graph_graph` (
	`dgraph_id` int(11) UNSIGNED NOT NULL ,
	`graph_id` int(11) UNSIGNED NOT NULL ,
	`data_id` int(11) UNSIGNED NOT NULL ,
	`color` varchar(255) NOT NULL ,
	`图例说明` varchar(255) NOT NULL ,
	PRIMARY KEY (`graph_id`,`dgraph_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table graph_comment

-- 
-- 监控器绘图注释性文字定义
-- 
-- comment_id
-- 	绘图注释定义 id
-- graph_id
-- 	绘图配置 id
-- comment
-- 	图片的注释信息

DROP TABLE IF EXISTS `graph_comment`;
CREATE TABLE `graph_comment` (
	`comment_id` int(11) UNSIGNED NOT NULL ,
	`graph_id` int(11) UNSIGNED NOT NULL ,
	`comment` varchar(255) NOT NULL ,
	PRIMARY KEY (`graph_id`,`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table graph_gprint

-- 
-- 监控器绘图格式化的文字信息
-- 
-- gprint_id
-- 	绘图格式化信息 id
-- graph_id
-- 	绘图配置 id
-- data_id
-- 	格式化的变量名 id
-- cf_type
-- 	运算类型
-- format
-- 	格式化的字符串定义

DROP TABLE IF EXISTS `graph_gprint`;
CREATE TABLE `graph_gprint` (
	`gprint_id` int(11) UNSIGNED NOT NULL ,
	`graph_id` int(11) UNSIGNED NOT NULL ,
	`data_id` int(11) UNSIGNED NOT NULL ,
	`cf_type` tinyint(11) UNSIGNED NOT NULL ,
	`format` varchar(255) NOT NULL ,
	PRIMARY KEY (`graph_id`,`gprint_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 