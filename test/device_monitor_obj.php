<?php
require_once 'core.php';

use \lib\member\sw_member as sw_mem;

// 添加 device key
$device_property_key    = sw_mem::property_factory('device_key', array('device_id' => '1'));
$device_property_monitor    = sw_mem::property_factory('device_monitor', array('value' => 'data'));
$monitor_property_basic = sw_mem::property_factory('monitor_basic', array('monitor_id' => '1'));
$monitor_property_attribute = sw_mem::property_factory('monitor_attribute', array('attr_id' => '1'));
$device_property_monitor->set_monitor_basic($monitor_property_basic);
$device_property_monitor->set_monitor_attribute($monitor_property_attribute);
$device = sw_mem::operator_factory('device', $device_property_key);
$device_monitor = $device->get_operator('monitor')->add_monitor($device_monitor_property);
var_dump($device_monitor);

// 删除 device key
//$condition = sw_mem::condition_factory('del_device_key', array('device_id' => 16));
//$condition->set_in('device_id');
//$device = sw_mem::operator_factory('device');
//$device_key = $device->get_operator('key')->del_key($condition);

// 添加 device basic
//$device_property_key = sw_mem::property_factory('device_key', array('device_id' => 16));
//$device_property_basic = sw_mem::property_factory('device_basic', array('device_display_name' => 'rttt'));
//$device = sw_mem::operator_factory('device', $device_property_key);
//$device_basic = $device->get_operator('basic')->add_basic($device_property_basic);

// 获取 device key
//$condition = sw_mem::condition_factory('get_device_key');
//$condition->set_like('device_name');
//$condition->set_device_name('t333');
//$device = sw_mem::operator_factory('device');
//$device_key = $device->get_operator('key')->get_key($condition);
//var_dump($device_key);

// 获取 device basic
//$condition = sw_mem::condition_factory('get_device_basic');
//$device = sw_mem::operator_factory('device');
//$device_basic = $device->get_operator('basic')->get_basic($condition);
//var_dump($device_basic);

// 修改 device basic
//$device_property_basic = sw_mem::property_factory('device_basic', array('device_display_name' => 'rt1111'));
//$condition = sw_mem::condition_factory('mod_device_basic', array('device_id' => 16));
//$condition->set_property($device_property_basic);
//$condition->set_in('device_id');
//$device = sw_mem::operator_factory('device');
//$device_basic = $device->get_operator('basic')->mod_basic($condition);

// 删除 device basic
//$condition = sw_mem::condition_factory('del_device_basic', array('device_id' => 16));
//$condition->set_in('device_id');
//$device = sw_mem::operator_factory('device');
//$device_basic = $device->get_operator('basic')->del_basic($condition);

