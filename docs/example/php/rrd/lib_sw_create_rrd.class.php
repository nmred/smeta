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
 * {{{ get device
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
	exit;
}


/* }}}
*/
