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
* RRD 库操作测试脚本
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
 * {{{ function insert_project()
+------------------------------------------------------------------
*/
function insert_project($device_id, $options = array())
{
	$project_property = sw_orm::property_factory('rrd', 'device_project');
	$project_property->set_project_name($options['project_name']);
	$project_property->set_device_id($device_id);
	$project_property->set_start_time($options['start_time']);
	$project_property->set_step($options['step']);

	try {
		$project_property->check();

		$project_operator = sw_orm::operator_factory('rrd', 'device_project');
		$project_operator->add_device_project($project_property);
	} catch (sw_exception $e) {
		throw new exception($e);	
	} 

	$condition = sw_orm::condition_factory('rrd', 'device_project:get_device_project');
	$condition->set_columns(array('project_id'));
	$condition->set_eq('project_name');
	$condition->set_eq('device_id');
	$condition->set_project_name($options['project_name']);
	$condition->set_device_id($device_id);
	try {
		$device_operator = sw_orm::operator_factory('rrd', 'device_project');
		$arr = $device_operator->get_device_project($condition);
	} catch (sw_exception $e) {
		throw new exception($e);	
	}

	return $arr;
}

/*
try {
	require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_create_rrd.class.php';
	require_once PATH_SWAN_LIB . 'rrd/sw_rrd_project.class.php';

	$project = new sw_rrd_project();
	$project->set_project_id(1)->process_key();

	$create_rrd = new sw_rrd_protocol_create_rrd();
	$create_rrd->create($project);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}*/


/* }}}
 * {{{ 创建 rrd 数据库
+------------------------------------------------------------------
try {
	require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_create_rrd.class.php';
	require_once PATH_SWAN_LIB . 'rrd/sw_rrd_project.class.php';

	$project = new sw_rrd_project();
	$project->set_project_id(1)->process_key();

	$create_rrd = new sw_rrd_protocol_create_rrd();
	$create_rrd->create($project);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

*/

/* }}}
 * {{{ 更新 rrd 数据库
+------------------------------------------------------------------
try {
	require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_update_rrd.class.php';
	require_once PATH_SWAN_LIB . 'rrd/sw_rrd_project.class.php';

	$project = new sw_rrd_project();
	$project->set_project_id(1)->process_key();

	$update = new sw_rrd_protocol_update_rrd($project);
	$update->set_update_time(time())
		   ->set_field('cpu_idel', 22)
		   ->update();
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}


*/
/* }}}
 * {{{ 修改 rrd 数据库
+------------------------------------------------------------------
try {
	require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_tune_rrd.class.php';
	require_once PATH_SWAN_LIB . 'rrd/sw_rrd_project.class.php';

	$project = new sw_rrd_project();
	$project->set_project_id(1)->process_key();

	$update = new sw_rrd_protocol_tune_rrd($project);
	$update->set_data_source('cpu_idel', array('minimum' => 30))
		   ->tune();
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

*/
/* }}}
 * {{{ 遍历 rrd 数据库
+------------------------------------------------------------------
try {
	require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_fetch_rrd.class.php';
	require_once PATH_SWAN_LIB . 'rrd/sw_rrd_project.class.php';

	$project = new sw_rrd_project();
	$project->set_project_id(1)->process_key();

	$fetch = new sw_rrd_protocol_fetch_rrd($project);
	$fetch->set_start_time(time() - 30)
		  ->set_end_time(time())
		  ->set_cf(sw_rrd_protocol_fetch_rrd::CF_AVERAGE)
		  ->fetch();
} catch (sw_exception $e) {
	echo $e->getMessage();	
	exit;
}

*/

/* }}}
*/

$project = insert_project(1, array('start_time' => time(), 'project_name' => 'test1', 'step' => 2));
print_r($project);
