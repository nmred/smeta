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
use lib\db\sw_db_expr;

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
class sw_db_expr_test extends sw_test
{
	// {{{ functions
	
	/**
	 * test_object 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_object()
	{
		$expr = new sw_db_expr('age = age + 1');
		$expected = 'age = age + 1';
		$this->assertAttributeEquals($expected, '__expression', $expr);
		$this->assertSame($expected, (string) $expr);
	}

	// }}}	
}
