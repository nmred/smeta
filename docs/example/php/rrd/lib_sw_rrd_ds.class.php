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
* rrd ds操作的示例
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
 * {{{ add rrd ds
+------------------------------------------------------------------
*/

$property = sw_orm::property_factory('rrd', 'rrd_ds');
$property->set_project_id(1);
$property->set_device_id(1);
$property->set_ds_name('cpu');

try {
	$property->check();
} catch (sw_exception $e) {
	echo $e->getMessage();
	exit;
}

try {
	$operator = sw_orm::operator_factory('rrd', 'rrd_ds');
	$operator->add_rrd_ds($property);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

/* }}}
+------------------------------------------------------------------
 * {{{ get rrd ds
+------------------------------------------------------------------
*/
$condition = sw_orm::condition_factory('rrd', 'rrd_ds:get_rrd_ds');

$condition->set_columns(array('ds_name'));
try {
	$operator = sw_orm::operator_factory('rrd', 'rrd_ds');
	$arr = $operator->get_rrd_ds($condition);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

P($arr);

/* }}}
+------------------------------------------------------------------
 * {{{ mod device project
+------------------------------------------------------------------
*/
$property = sw_orm::property_factory('rrd', 'rrd_ds');
$property->set_ds_name("cpu_test");
$condition = sw_orm::condition_factory('rrd', 'rrd_ds:mod_rrd_ds');

$condition->set_like('ds_name');
$condition->set_ds_name('cpu');
$condition->set_property($property);
try {
	$operator = sw_orm::operator_factory('rrd', 'rrd_ds');
	$arr = $operator->mod_rrd_ds($condition);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

P($arr);

/* }}}
+------------------------------------------------------------------
 * {{{ del device project
+------------------------------------------------------------------
*/

$condition = sw_orm::condition_factory('rrd', 'rrd_ds:del_rrd_ds');

$condition->set_in('device_id');
$condition->set_device_id(1);
try {
	$operator = sw_orm::operator_factory('rrd', 'rrd_ds');
	$arr = $operator->del_rrd_ds($condition);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

P($arr);

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
