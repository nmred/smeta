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
* device project操作的示例
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
 * {{{ add device project
+------------------------------------------------------------------

$project_property = sw_orm::property_factory('rrd', 'device_project');
$project_property->set_project_name("cpu");
$project_property->set_device_id(5);
$project_property->set_start_time(time());
$project_property->set_step(300);

try {
	$project_property->check();
} catch (sw_exception $e) {
	echo $e->getMessage();
	exit;
}

try {
	$project_operator = sw_orm::operator_factory('rrd', 'device_project');
	$project_operator->add_device_project($project_property);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

*/
/* }}}
+------------------------------------------------------------------
 * {{{ get device project
+------------------------------------------------------------------
*/
$condition = sw_orm::condition_factory('rrd', 'device_project:get_device_project');

//$condition->set_columns(array('project_name', 'start_time'));
try {
	$device_operator = sw_orm::operator_factory('rrd', 'device_project');
	$arr = $device_operator->get_device_project($condition);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

P($arr);

/* }}}
+------------------------------------------------------------------
 * {{{ mod device project
+------------------------------------------------------------------
$property = sw_orm::property_factory('rrd', 'device_project');
$property->set_step(5);
$condition = sw_orm::condition_factory('rrd', 'device_project:mod_device_project');

$condition->set_like('project_name');
$condition->set_project_name('cpu');
$condition->set_property($property);
try {
	$operator = sw_orm::operator_factory('rrd', 'device_project');
	$arr = $operator->mod_device_project($condition);
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

$condition = sw_orm::condition_factory('rrd', 'device_project:del_device_project');

$condition->set_in('device_id');
$condition->set_device_id(0);
try {
	$operator = sw_orm::operator_factory('rrd', 'device_project');
	$arr = $operator->del_device_project($condition);
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
