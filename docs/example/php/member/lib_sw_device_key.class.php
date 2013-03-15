<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +---------------------------------------------------------------------------
// | SWAN [ $_SWANBR_SLOGAN_$ ]
// +---------------------------------------------------------------------------
// | Copyright $_SWANBR_COPYRIGHT_$
// +---------------------------------------------------------------------------
// | Version  $_SWANBR_VERSION_$
// +---------------------------------------------------------------------------
// | Licensed ( $_SWANBR_LICENSED_URL_$ )
// +---------------------------------------------------------------------------
// | $_SWANBR_WEB_DOMAIN_$
// +---------------------------------------------------------------------------
 
/**
+------------------------------------------------------------------------------
* device_key 操作的示例
+------------------------------------------------------------------------------
* 
* @package sw_operator_rrd_device
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

require_once 'core.php';
require_once PATH_SWAN_LIB . 'sw_member.class.php';

/*
+------------------------------------------------------------------
 * {{{ add device_key
+------------------------------------------------------------------
$device_key_property = sw_member::property_factory('device_key', array('device_name' => 'test1_2222'));

try {
	$device_key_property->check();
} catch (sw_exception $e) {
	echo $e->getMessage();
	exit;
}

try {
	$device_operator = sw_member::operator_factory('device', $device_key_property);
	$device_key_operator = $device_operator->get_operator('device_key');
	$device_key_operator->add_key();

} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}
*/
/*

/* }}}
+------------------------------------------------------------------
 * {{{ process_key
+------------------------------------------------------------------
*/
$device_key_property = sw_member::property_factory('device_key', array('device_name' => 'test1_2222'));
try {
	$device_operator = sw_member::operator_factory('device', $device_key_property);
	$device_key_operator = $device_operator->get_operator('device_key');
	$device_key_operator->process_key();
	$device_key_property = $device_key_operator->get_device_operator()->get_device_key_property();
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

P($device_key_property);

/* }}}
+------------------------------------------------------------------
 * {{{ mod device
+------------------------------------------------------------------

$device_property = sw_orm::property_factory('rrd', 'device');
$device_property->set_device_name("device_test");
$condition = sw_orm::condition_factory('rrd', 'device:mod_device');

$condition->set_in('device_id');
$condition->set_device_id('5');
$condition->set_property($device_property);
try {
	$device_operator = sw_orm::operator_factory('rrd', 'device');
	$arr = $device_operator->mod_device($condition);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

P($arr);

*/
/* }}}
+------------------------------------------------------------------
 * {{{ del device
+------------------------------------------------------------------
$condition = sw_orm::condition_factory('rrd', 'device:del_device');

$condition->set_in('device_id');
$condition->set_device_id(array(2, 3, 4));
try {
	$device_operator = sw_orm::operator_factory('rrd', 'device');
	$arr = $device_operator->del_device($condition);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

P($arr);

*/
/* }}}
+------------------------------------------------------------------
 * {{{ get_profiler()  
+------------------------------------------------------------------
*/
$__db = sw_db::singleton();

$profile = $__db->get_profiler()->get_query_profiles(null, true);
P($profile);

/* }}}
+------------------------------------------------------------------
 * {{{  
+------------------------------------------------------------------
*/
