<?php
require_once 'core.php';

use \lib\member\sw_member;

// 添加 monitor attribute
//$monitor_basic_property = sw_member::property_factory('monitor_basic', array('monitor_id' => 6));
//$monitor_attribute_property = sw_member::property_factory('monitor_attribute', array('attr_name' => 'testsss1', 'form_type' => 1));
//$monitor = sw_member::operator_factory('monitor', $monitor_basic_property);
//$attr_id = $monitor->get_operator('attribute')->add_attribute($monitor_attribute_property);
//var_dump($attr_id);

// 获取 monitor attribute
$condition = sw_member::condition_factory('get_monitor_attribute');
$condition->set_like('attr_name');
$condition->set_attr_name('te');
$monitor = sw_member::operator_factory('monitor');
$monitor_attribute = $monitor->get_operator('attribute')->get_attribute($condition);
var_dump($monitor_attribute);

// 删除 monitor attribute
//$condition = sw_member::condition_factory('del_monitor_attribute', array('monitor_id' => 4));
//$condition->set_in('monitor_id');
//$monitor = sw_member::operator_factory('monitor');
//$monitor_attribute = $monitor->get_operator('attribute')->del_attribute($condition);


// 修改 monitor attribute
//$monitor_property_attribute = sw_member::property_factory('monitor_attribute', array('monitor_display_name' => 'rt1111sssss'));
//$condition = sw_member::condition_factory('mod_monitor_attribute', array('monitor_id' => 4));
//$condition->set_property($monitor_property_attribute);
//$condition->set_in('monitor_id');
//$monitor = sw_member::operator_factory('monitor');
//$monitor_attribute = $monitor->get_operator('attribute')->mod_attribute($condition);
