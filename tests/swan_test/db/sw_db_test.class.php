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

namespace swan_test\db;
use lib\test\sw_test;
use lib\db\sw_db;

/**
+------------------------------------------------------------------------------
* sw_db_expr_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db 
+------------------------------------------------------------------------------
*/
class sw_db_test extends sw_test
{
	// {{{ functions
	// {{{ public function test_factory()
	
	/**
	 * test_factory 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_factory()
	{
		$db = sw_db::factory('mysql');

		$this->assertInstanceOf('lib\db\adapter\sw_mysql', $db);
	}

	// }}}
	// }}}	
}
