/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
--
-- 此脚本用于在安装监控系统的时候向数据库中写入默认的数据
--

USE swan_soft;
SET NAMES utf8;

--
-- 授权
--
GRANT ALL PRIVILEGES ON *.* TO swan@127.0.0.1 IDENTIFIED BY 'swan' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO swan@localhost IDENTIFIED BY 'swan' WITH GRANT OPTION;

--
-- 监控器相关数据
--
INSERT INTO monitor_basic VALUES
    (1, 'apache', 'HTTP SERVER FOR APACHE 监控器'),
    (2, 'mysql', 'MYSQL 监控器');
INSERT INTO monitor_attribute VALUES
	(1, 1, 'url', 'APACHE 状态访问路劲', '1', ''),
	(1, 2, 'dsn', 'MYSQL 连接的 DSN', '1', '');
INSERT INTO monitor_metric VALUES
	(1, 'ap_busy_workers', 1, 30, 200, 'Apache - 繁忙线程数'),
	(2, 'ap_idle_workers', 1, 30, 200, 'Apache - 空闲线程数'),
	(3, 'ap_req_per_sec', 1, 30, 200, 'Apache - 每秒平均请求数'),
	(4, 'ap_bytes_per_sec', 1, 30, 200, 'Apache - 每秒平均流量'),
	(5, 'ap_busy_workers', 1, 30, 200, 'Apache - 每个请求的平均流量'),
	(6, 'ap_busy_workers', 1, 30, 200, 'Apache - CPU 的负载'),
	(1, 'mysql_select', 2, 30, 200, 'MySQL - Select 查询数'),
	(2, 'mysql_insert', 2, 30, 200, 'MySQL - Insert 查询数'),
	(3, 'mysql_update', 2, 30, 200, 'MySQL - Update 查询数');

--
-- 设备管理相关
--
INSERT INTO device_key VALUES
    (1, 'lan-114'),
    (2, 'lan-115');
INSERT INTO device_basic VALUES
    (1, 'lan-114', '192.168.1.114'),
    (2, 'lan-115', '192.168.1.115');

INSERT INTO monitor_params VALUES
    (1, 1, 1, 1, 'http://localhost:8090/server-status'),
    (2, 1, 1, 2, 'unix_socket=/usr/local/swan/run/sw_mysql.sock;dbname=swan_soft'),
    (1, 1, 2, 1, 'http://localhost:8090/server-status'),
    (2, 1, 2, 2, 'unix_socket=/usr/local/swan/run/sw_mysql.sock;dbname=swan_soft');

--
-- auto increment 管理
--
INSERT INTO sequence_global VALUES
    ('device_key', 2),
    ('monitor_basic', 2);
INSERT INTO sequence_device VALUES
    (1, 'monitor_params', 2),
    (2, 'monitor_params', 2);
INSERT INTO sequence_monitor VALUES
    (1, 'monitor_attribute', 1),
    (1, 'monitor_metric', 6),
    (2, 'monitor_attribute', 1),
    (2, 'monitor_metric', 3);
