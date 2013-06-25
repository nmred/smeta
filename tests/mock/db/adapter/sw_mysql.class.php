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
 
namespace mock\db\adapter\sw_mysql;
use lib\db\adapter\sw_mysql as sw_mock_mysql;

/**
+------------------------------------------------------------------------------
* sw_sw_mysql 
+------------------------------------------------------------------------------
* 
* @uses sw_mock_mysql
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_mysql extends sw_mock_mysql
{
	// {{{ functions

	/**
	 * get_namespaces 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_profiler()
	{
		return $this->__profiler;	
	}

	// }}}	
}
