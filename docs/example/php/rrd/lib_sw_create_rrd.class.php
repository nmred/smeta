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
* device 建立 RRD 库操作的示例
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
 * {{{ 创建 rrd 数据库
+------------------------------------------------------------------
*/
try {
	require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_create_rrd.class.php';
	require_once PATH_SWAN_LIB . 'rrd/sw_rrd_project.class.php';

	$project = new sw_rrd_project();
	$project->set_project_id(1)->process_key();

	$create_rrd = new sw_rrd_protocol_create_rrd();
	$create_rrd->create($project);
} catch (sw_exception $e) {
	echo $e->getMessage();	
	//exit;
}


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
 * {{{ get_profiler()  
+------------------------------------------------------------------
*/
$__db = sw_db::singleton();

$profile = $__db->get_profiler()->get_query_profiles(null, true);
P($profile);

/* }}}
*/
