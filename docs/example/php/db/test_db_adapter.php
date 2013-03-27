<?php
require_once 'core.php';

use lib\db\adapter\sw_mysql as sw_mysql;

$db = new sw_mysql();
P($db);
$__db = $db->get_connection();
$arr = array(
	'dsdsd\'dsdsds',
	'3323dsdd',
	444.33,
	'0xqqeeeee'
);

//use lib\db\select\sw_select as sw_select;
use lib\db\sw_db_expr as sw_expr;
use lib\db\sw_db as sw_db;

//$select = new sw_expr('group_id = "group_id" + 1');
//var_dump($db->quote($arr));

//P($db->quote_into('device_id = ? AND device_name = ?', '3', null, 1));
//P($db->quote_into('device_id = ? AND device_name = ?', '3'));
