<?php
require_once 'core.php';

use \lib\member\sw_member as sw_mem;

// 添加 device key
$device_property_key = sw_mem::property_factory('device_key', array('device_name' => 'testsss'));
$device = sw_mem::operator_factory('device', $device_property_key);
$device_key = $device->get_operator('key')->add_key();
var_dump($device_key);

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
$condition = sw_mem::condition_factory('get_device_key');
$device = sw_mem::operator_factory('device');
$device_key = $device->get_operator('key')->get_key($condition);
var_dump($device_key);

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

// 获取 device list
//$condition = sw_mem::condition_factory('get_device');
//$condition->set_is_count(true);
//$device = sw_mem::operator_factory('device');
//$device_key = $device->get_device($condition);
//var_dump($device_key);

