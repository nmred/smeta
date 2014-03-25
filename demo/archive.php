<?php
require_once 'core.php';

use \lib\member\sw_member;

// 添加 monitor archive
//$monitor_basic_property = sw_member::property_factory('monitor_basic', array('monitor_id' => '1'));
//$monitor_archive_property = sw_member::property_factory('monitor_archive', 
//	array('cf_type' => '1', 'steps' => 60, 'rows' => 10, 'xff' => 0.5, 'title' => 'test'));
//$monitor = sw_member::operator_factory('monitor', $monitor_basic_property);
//$archive_id = $monitor->get_operator('archive')->add_archive($monitor_archive_property);
//var_dump($archive_id);

// 删除 monitor archive
//$condition = sw_member::condition_factory('del_monitor_archive', array('archive_id' => 4));
//$condition->set_in('archive_id');
//$monitor = sw_member::operator_factory('monitor');
//$monitor_archive = $monitor->get_operator('archive')->del_archive($condition);


// 修改 monitor archive
$monitor_property_archive = sw_member::property_factory('monitor_archive', array('rows' => '30'));
$condition = sw_member::condition_factory('mod_monitor_archive', array('archive_id' => 5));
$condition->set_property($monitor_property_archive);
$condition->set_in('archive_id');
$monitor = sw_member::operator_factory('monitor');
$monitor_archive = $monitor->get_operator('archive')->mod_archive($condition);

// 获取 monitor archive
$condition = sw_member::condition_factory('get_monitor_archive');
$monitor = sw_member::operator_factory('monitor');
$monitor_archive = $monitor->get_operator('archive')->get_archive($condition);
var_dump($monitor_archive);
