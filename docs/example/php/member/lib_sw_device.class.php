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
* device操作的示例
+------------------------------------------------------------------------------
* 
* @package sw_operator_rrd_device
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

require_once 'core.php';
require_once PATH_SWAN_LIB . 'sw_orm.class.php';

/*
+------------------------------------------------------------------
 * {{{ add device
+------------------------------------------------------------------
*/

require_once PATH_SWAN_LIB . 'sw_sequence.class.php';

$device_id = sw_sequence::get_next_global(SWAN_TBN_DEVICE);
$device_property = sw_orm::property_factory('rrd', 'device');
$device_property->set_device_id($device_id);
$device_property->set_device_name("test_132");
$device_property->set_snmp_version(1);
$device_property->set_host("192.168.56.130");
$device_property->set_port("161");

try {
	$device_property->check();
} catch (sw_exception $e) {
	echo $e->getMessage();
	exit;
}

try {
	$device_operator = sw_orm::operator_factory('rrd', 'device');
	$device_operator->add_device($device_property);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

/* }}}
+------------------------------------------------------------------
 * {{{ get device
+------------------------------------------------------------------
*/
$condition = sw_orm::condition_factory('rrd', 'device:get_device');

//$condition->set_is_count(true);
$condition->set_eq('device_id');
//$condition->set_device_id('5');
$condition->set_columns(array('device_id', 'device_name', 'port'));
//dcondition->set_device_name('test');
try {
	$device_operator = sw_orm::operator_factory('rrd', 'device');
	$arr = $device_operator->get_device($condition);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

P($arr);

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
