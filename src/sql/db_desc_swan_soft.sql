--  vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker:

-- {{{  table sequence_global

-- 
-- 维护全局的数据表的唯一序列号
-- 
-- table_name
-- 	维护序列号的表名称
-- sequence_id
-- 	自增长 ID

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

CREATE TABLE `sequence_device` (
	`device_id` int(11) UNSIGNED NOT NULL ,
	`table_name` varchar(64) NOT NULL ,
	`sequence_id` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`device_id`,`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table sequence_project

-- 
-- 项目成员数据表的唯一序列号
-- 
-- project_id
-- 	项目 ID
-- table_name
-- 	维护序列号的表名称
-- sequence_id
-- 	自增长 ID

CREATE TABLE `sequence_project` (
	`project_id` int(11) UNSIGNED NOT NULL ,
	`table_name` varchar(64) NOT NULL ,
	`sequence_id` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`project_id`,`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table device

-- 
-- 监控的设备信息表
-- 
-- device_id
-- 	设备 id
-- device_name
-- 	设备名称(唯一)
-- device_display_name
-- 	设备显示名称
-- host
-- 	设备的主机名
-- port
-- 	设备的端口号，默认是  SNMP 的默认端口：161
-- snmp_version
-- 	SNMP 协议的版本 0:VERSION_1, 1:VERSION_2 2:VERSION_3
-- snmp_method
-- 	通过 SNMP 获取数据的方式 0: EXEC 方式即直接是系统调用 1: EXT 方式( PHP 的内置扩展，需要有扩展支持)
-- snmp_protocol
-- 	SNMP 的通讯协议 0: NET 方式 1: UDP 方式
-- snmp_community
-- 	SNMP 的共同体，只有 V1 和 V2 版本有效，默认 public
-- snmp_timeout
-- 	SNMP 连接超时时间，单位是微秒，默认是 5s
-- snmp_retries
-- 	SNMP 连接失败后重试次数，默认 3 次
-- security_name
-- 	认证的用户名
-- security_level
-- 	认证的安全等级 0: noAuthNoPriv 1:authNoPriv 2:authPriv
-- auth_protocol
-- 	认证的协议算法 0: MD5  1: SHA
-- auth_passphrase
-- 	认证的公钥
-- priv_protocol
-- 	密钥的协议算法 0: DES  1: AES
-- priv_passphrase
-- 	认证密钥

CREATE TABLE `device` (
	`device_id` int(11) UNSIGNED NOT NULL ,
	`device_name` varchar(255) NOT NULL ,
	`device_display_name` varchar(255) NOT NULL ,
	`host` varchar(32) CHARACTER SET latin1 NOT NULL ,
	`port` smallint(6) UNSIGNED DEFAULT '161',
	`snmp_version` tinyint(2) UNSIGNED DEFAULT '0',
	`snmp_method` tinyint(2) UNSIGNED DEFAULT '0',
	`snmp_protocol` tinyint(2) UNSIGNED DEFAULT '0',
	`snmp_community` varchar(32) DEFAULT 'public',
	`snmp_timeout` int(6) UNSIGNED DEFAULT '5000',
	`snmp_retries` tinyint(2) UNSIGNED DEFAULT '3',
	`security_name` varchar(32) ,
	`security_level` tinyint(2) UNSIGNED DEFAULT '0',
	`auth_protocol` tinyint(2) UNSIGNED DEFAULT '0',
	`auth_passphrase` varchar(32) ,
	`priv_protocol` tinyint(2) UNSIGNED DEFAULT '0',
	`priv_passphrase` varchar(32) ,
	PRIMARY KEY (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table device_project

-- 
-- 记录监控设备的每个项目rrd信息
-- 
-- project_id
-- 	项目 id
-- project_name
-- 	监控设备项目名称
-- device_id
-- 	关联 device.device_id
-- step
-- 	rrd刷新周期 默认值 300s
-- start_time
-- 	数据库的起始时间 默认是now() - 10s

CREATE TABLE `device_project` (
	`project_id` int(11) UNSIGNED NOT NULL ,
	`project_name` varchar(64) NOT NULL ,
	`device_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
	`step` int(6) UNSIGNED DEFAULT '300',
	`start_time` int(11) UNSIGNED ,
	PRIMARY KEY (`device_id`,`project_name`),
	KEY`ik_0` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table rrd_ds

-- 
-- 记录每个监控项目对应的多个字段属性
-- 
-- ds_id
-- 	DS id
-- ds_name
-- 	属性名 规则和变量命名一样，限制不能输入中文，这个是有意义的字段
-- project_id
-- 	关联 device_project.project_id
-- device_id
-- 	关联 device.device_id (可能以后会有意义)
-- get_method
-- 	数据获取方式 0 :通过 SNMP 协议获取 1:通过其他方式，例如脚本等
-- object_type
-- 	如果使用SNMP协议 传递的object类型
-- object_id
-- 	如果使用SNMP协议 传递的object ids
-- source_type
-- 	决定最后写入数据后rrd的运算规则 0 : GAUGE（默认值） 1 : COUNTER 2 : DERIVE 3 : ABSOLUTE
-- heart_time
-- 	心跳时间 ，如果超过这个时间还没有数据传递则默认赋值为 (UN) --UNKNOWN
-- min
-- 	传递数据的最小限制 默认不限制
-- max
-- 	传递数据的最大限制 默认不限制

CREATE TABLE `rrd_ds` (
	`ds_id` int(11) UNSIGNED NOT NULL ,
	`ds_name` varchar(64) NOT NULL ,
	`project_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
	`device_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
	`get_method` tinyint(2) UNSIGNED DEFAULT '0',
	`object_type` char(32) ,
	`object_id` varchar(255) ,
	`source_type` tinyint(2) UNSIGNED DEFAULT '0',
	`heart_time` int(11) UNSIGNED DEFAULT '300',
	`min` varchar(32) DEFAULT 'U',
	`max` varchar(32) DEFAULT 'U',
	PRIMARY KEY (`ds_id`),
	KEY`ik_0` (`project_id`,`device_id`),
	KEY`ik_1` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table rrd_rra

-- 
-- 记录每个监控项目对数据的归档规则(合并数据的规则)
-- 
-- rra_id
-- 	RRA id
-- project_id
-- 	关联 device_project.project_id
-- device_id
-- 	关联 device.device_id (可能以后会有意义)
-- rra_cf
-- 	归档策略算法 0 : AVERAGE 1:MIN 2:MAX 3:LAST
-- rra_xff
-- 	xfiles factor 和unkown数据有关，很多资料都取0.5 (待研究)
-- steps
-- 	决定多少步长进行归档一次
-- rows
-- 	归档的条数

CREATE TABLE `rrd_rra` (
	`rra_id` int(11) UNSIGNED NOT NULL ,
	`project_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
	`device_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
	`rra_cf` tinyint(2) UNSIGNED DEFAULT '0',
	`rra_xff` float(11) DEFAULT '0.5',
	`steps` int(11) UNSIGNED NOT NULL ,
	`rows` int(11) UNSIGNED NOT NULL ,
	PRIMARY KEY (`rra_id`),
	KEY`ik_0` (`project_id`,`device_id`),
	KEY`ik_1` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table rrd_cdef

-- 
-- 记录绘制的图片规则中的中间变量运算
-- 
-- cdef_id
-- 	CDEF id
-- graph_id
-- 	关联 rrd_graph.graph_id
-- define_name
-- 	变量名称
-- cdef_cf
-- 	算法该字段存取的逆波兰表达式 (当前先就这样了，以后尽量优化的不让用户直接输入逆波兰表达式)

CREATE TABLE `rrd_cdef` (
	`cdef_id` int(11) UNSIGNED NOT NULL ,
	`graph_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
	`define_name` varchar(32) NOT NULL ,
	`cdef_cf` varchar(255) NOT NULL ,
	PRIMARY KEY (`cdef_id`),
	KEY`ik_0` (`graph_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table rrd_line_area

-- 
-- 记录绘制的图片规则中的线条定义
-- 
-- line_id
-- 	线条ID id
-- graph_id
-- 	关联 rrd_graph.graph_id
-- line_cate
-- 	线条类型 0：AREA 1: LINE1 2: LINE2 3: LINE3 4: STACK
-- define_name
-- 	变量名称
-- color
-- 	线条颜色
-- legend
-- 	线条注释 对该颜色的提示，最后会写在图上的

CREATE TABLE `rrd_line_area` (
	`line_id` int(11) UNSIGNED NOT NULL ,
	`graph_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
	`line_cate` tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
	`define_name` varchar(32) NOT NULL ,
	`color` varchar(32) NOT NULL ,
	`legend` varchar(32) NOT NULL ,
	PRIMARY KEY (`line_id`),
	KEY`ik_0` (`graph_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 
-- {{{  table rrd_gprint

-- 
-- 记录线条中的这个极限值
-- 
-- gprint_id
-- 	GPRINT id
-- line_id
-- 	关联 rrd_line_area.line_id
-- line_cf
-- 	线条运算算法 0：AVERAGE 1: MIN 2: MAX 3: LAST
-- format
-- 	线条极限值注释 对该颜色的提示，最后会写在图上的

CREATE TABLE `rrd_gprint` (
	`gprint_id` int(11) UNSIGNED NOT NULL ,
	`line_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
	`line_cf` tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
	`format` varchar(32) NOT NULL ,
	PRIMARY KEY (`gprint_id`),
	KEY`ik_0` (`line_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  }}} 