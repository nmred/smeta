<?php
require_once 'core.php';

use \lib\member\sw_member;

$device_property_key = sw_member::property_factory('device_key', array('device_name' => 'test'));
$device = sw_member::operator_factory('device', $device_property_key);
$device_key = $device->get_operator('key')->add_key();
