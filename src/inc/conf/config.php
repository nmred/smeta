<?php
return array(
	'db' => array(
		'type' => 'mysql',
		'dbname' => 'swan_soft',
		'username' => 'root',
		'password' => '',
		'host' => '127.0.0.1',
		'port' => '3306',
		'unix_socket' => '/usr/local/swan/smeta/run/sw_mysql.sock',
		'driver_options' => array(),
	),
	'log' => array(
		'host' => '127.0.0.1',
		'self' => 'localhost',
		'port' => '10514',
	),
	'data_host' => array( // swdata server
		'host' => '127.0.0.1',
		'port' => '9080',
	),
	'redis' => array(
		'host' => '127.0.0.1',
		'port' => '6378',
	),
	'gmw_update_rrd' => '127.0.0.1:4730',
	'gmc_update_rrd' => '127.0.0.1:4730',
	'gmw_push_server' => '127.0.0.1:4730',
	'gmc_push_server' => '127.0.0.1:4730',
);
