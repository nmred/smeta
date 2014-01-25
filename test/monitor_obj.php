<?php
require_once 'core.php';

use \lib\member\sw_member;

// 添加 monitor basic
$monitor_basic_property = sw_member::property_factory('monitor_basic', array('monitor_name' => 'testsss1'));
$monitor = sw_member::operator_factory('monitor', $monitor_basic_property);
$monitor_id = $monitor->get_operator('basic')->add_basic();
var_dump($monitor_id);

// 获取 monitor basic
//$condition = sw_member::condition_factory('get_monitor_basic');
//$condition->set_like('monitor_name');
//$condition->set_monitor_name('te');
//$monitor = sw_member::operator_factory('monitor');
//$monitor_basic = $monitor->get_operator('basic')->get_basic($condition);
//var_dump($monitor_basic);

// 删除 monitor basic
//$condition = sw_member::condition_factory('del_monitor_basic', array('monitor_id' => 4));
//$condition->set_in('monitor_id');
//$monitor = sw_member::operator_factory('monitor');
//$monitor_basic = $monitor->get_operator('basic')->del_basic($condition);


// 修改 monitor basic
//$monitor_property_basic = sw_member::property_factory('monitor_basic', array('monitor_display_name' => 'rt1111sssss'));
//$condition = sw_member::condition_factory('mod_monitor_basic', array('monitor_id' => 4));
//$condition->set_property($monitor_property_basic);
//$condition->set_in('monitor_id');
//$monitor = sw_member::operator_factory('monitor');
//$monitor_basic = $monitor->get_operator('basic')->mod_basic($condition);
