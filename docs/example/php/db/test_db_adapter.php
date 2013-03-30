<?php
require_once 'core.php';

use lib\db\adapter\sw_mysql as sw_mysql;

$db = new sw_mysql();
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
//P($db->quote_table_as(new sw_expr('count(*)'), 'id'));
//P($db->quote_table_as(new sw_expr('count(*)'), 'id'));
//P($db->quote_indentifier('aaa'));
//P($db->fold_case('SS'));
P($db->supports_parameters('named1'));
P($db->get_server_version());

