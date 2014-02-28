<?php
require_once 'core.php';

use \lib\member\sw_member as sw_mem;

// 添加 device monitor
//$device_property_key    = sw_mem::property_factory('device_key', array('device_id' => '1'));
//$monitor_property_basic = sw_mem::property_factory('monitor_basic', array('monitor_id' => '1'));
//$device_property_monitor = sw_mem::property_factory('device_monitor', array('dm_name' => 'lsss'));
//$device_property_monitor->set_monitor_basic($monitor_property_basic);
//$monitor_params[] = sw_mem::property_factory('monitor_params', array('attr_id' => '1', 'value' => 'dsds'));
//$monitor_params[] = sw_mem::property_factory('monitor_params', array('attr_id' => '2', 'value' => 'dsdsd'));
//$device_property_monitor->set_monitor_params($monitor_params);
//$device = sw_mem::operator_factory('device', $device_property_key);
//$device_monitor = $device->get_operator('monitor')->add_monitor($device_property_monitor);
//var_dump($device_monitor);

// 获取 device monitor
//$condition = sw_mem::condition_factory('get_device_monitor');
//$device = sw_mem::operator_factory('device');
//$device_monitor = $device->get_operator('monitor')->get_monitor($condition);
//var_dump($device_monitor);

// 修改 device monitor
//$device_property_key = sw_mem::property_factory('device_key', array('device_id' => '1'));
//$monitor_property_basic  = sw_mem::property_factory('monitor_basic', array('monitor_id' => '1'));
//$monitor_params[] = sw_mem::property_factory('monitor_params', array('value' => '2220000', 'attr_id' => '1'));
//$monitor_params[] = sw_mem::property_factory('monitor_params', array('value' => '222', 'attr_id' => '2'));
//$device_property_monitor = sw_mem::property_factory('device_monitor');
//$device_property_monitor->set_monitor_basic($monitor_property_basic);
//$device_property_monitor->set_monitor_params($monitor_params);
//$device_property_monitor->set_dm_id(3);
//$condition = sw_mem::condition_factory('mod_device_monitor');
//$condition->set_property($device_property_monitor);
//$device = sw_mem::operator_factory('device', $device_property_key);
//$device_monitor = $device->get_operator('monitor')->mod_monitor($condition);

// 删除 device monitor
//$condition = sw_mem::condition_factory('del_device_monitor');
//$condition->set_in('dm_id');
//$condition->set_dm_id(1);
//$device = sw_mem::operator_factory('device');
//$device_monitor = $device->get_operator('monitor')->del_monitor($condition);


// 获取 device monitor params 用于修改或者显示某个设备监控器的 params值
$condition = sw_mem::condition_factory('get_device_monitor_params');
$condition->set_in('device_id');
$condition->set_device_id(1);
$condition->set_in('monitor_id');
$condition->set_monitor_id(1);
$device = sw_mem::operator_factory('device');
$device_monitor = $device->get_operator('monitor')->get_monitor_params($condition);
var_dump($device_monitor);
