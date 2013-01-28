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
* rrd rra操作的示例
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
 * {{{ add rrd rra
+------------------------------------------------------------------
*/

$property = sw_orm::property_factory('rrd', 'rrd_rra');
$property->set_project_id(1);
$property->set_device_id(5);
$property->set_steps(720); // 5秒 * 12 = 1min
$property->set_rows(24);// 60行=一小时 
try {
	$property->check();
} catch (sw_exception $e) {
	echo $e->getMessage();
	exit;
}

try {
	$operator = sw_orm::operator_factory('rrd', 'rrd_rra');
	$operator->add_rrd_rra($property);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

/* }}}
+------------------------------------------------------------------
 * {{{ get rrd ds
+------------------------------------------------------------------
*/
$condition = sw_orm::condition_factory('rrd', 'rrd_rra:get_rrd_rra');

try {
	$operator = sw_orm::operator_factory('rrd', 'rrd_rra');
	$arr = $operator->get_rrd_rra($condition);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

P($arr);

/* }}}
+------------------------------------------------------------------
 * {{{ mod device project
+------------------------------------------------------------------
$property = sw_orm::property_factory('rrd', 'rrd_rra');
$property->set_steps(1);
$property->set_rows(600);
$condition = sw_orm::condition_factory('rrd', 'rrd_rra:mod_rrd_rra');

$condition->set_in('project_id');
$condition->set_project_id(1);
$condition->set_property($property);
try {
	$operator = sw_orm::operator_factory('rrd', 'rrd_rra');
	$arr = $operator->mod_rrd_rra($condition);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

P($arr);

*/
/* }}}
+------------------------------------------------------------------
 * {{{ del device project
+------------------------------------------------------------------

$condition = sw_orm::condition_factory('rrd', 'rrd_rra:del_rrd_rra');

$condition->set_in('device_id');
$condition->set_device_id(1);
try {
	$operator = sw_orm::operator_factory('rrd', 'rrd_rra');
	$arr = $operator->del_rrd_rra($condition);
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
