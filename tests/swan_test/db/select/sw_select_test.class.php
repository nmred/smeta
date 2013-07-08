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
 
namespace swan_test\db\select;
use lib\test\sw_test;
use lib\db\adapter\sw_mysql;
use lib\db\select\exception\sw_exception;
use mock\db\select\sw_select;

/**
+------------------------------------------------------------------------------
* sw_abatract_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_select_test extends sw_test
{
	// {{{ members

	/**
	 * __db 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__db = null;

	// }}}
	// {{{ functions
	// {{{ public function setUp()

	/**
	 * setUp 
	 * 
	 * @access public
	 * @return void
	 */
	public function setUp()
	{
		$this->__db = new sw_mysql();	
	}

	// }}}
	// {{{ public function test_construct()

	/**
	 * test_construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_construct()
	{
		$mock = new sw_select($this->__db);
		$rev = $mock->get_parts();
		$expect = $mock->get_init_part();

		$this->assertEquals($expect, $rev);
	}

	// }}}
	// {{{ public function test_get_adapter()

	/**
	 * test_get_adapter 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_adapter()
	{
		$mock = new sw_select($this->__db);
		$rev = $mock->get_adapter();

		$this->assertEquals($this->__db, $rev);
	}

	// }}}
	// {{{ public function test_distinct()

	/**
	 * test_distinct 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_distinct()
	{
		$mock = new sw_select($this->__db);
		$mock->distinct();
		$rev = $mock->get_parts();
		$this->assertEquals(true, $rev['distinct']);
	}

	// }}}
	// {{{ public function test_table_cols()

	/**
	 * test_table_cols 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_table_cols()
	{
		$mock = new sw_select($this->__db);
		$cols = array('id', 'user.id', 'id as D');
		$mock->mock_table_cols('user', $cols);
		$rev = $mock->get_parts();
		$expect = array(
			array('user', 'id', null),
			array('user', 'id', null),
			array('user', 'id', 'D'),
		);
		$this->assertEquals($expect, $rev['columns']);

		$cols = array('name', 'group.name', 'name as N');
		$mock->mock_table_cols('group', $cols, true);
		$rev = $mock->get_parts();
		$expect = array(
			array('group', 'name', null),
			array('group', 'name', null),
			array('group', 'name', 'N'),
			array('user', 'id', null),
			array('user', 'id', null),
			array('user', 'id', 'D'),
		);
		$this->assertEquals($expect, $rev['columns']);

		$cols = array('pass');
		$mock->mock_table_cols('user', $cols, 'group');
		$rev = $mock->get_parts();
		$expect = array(
			array('group', 'name', null),
			array('user', 'pass', null),
			array('group', 'name', null),
			array('group', 'name', 'N'),
			array('user', 'id', null),
			array('user', 'id', null),
			array('user', 'id', 'D'),
		);
		$this->assertEquals($expect, $rev['columns']);

		$mock = new sw_select($this->__db);
		$cols = array('id', 'user.id', 'id as D');
		$mock->mock_table_cols(null, $cols);
		$rev = $mock->get_parts();
		$expect = array(
			array('', 'id', null),
			array('user', 'id', null),
			array('', 'id', 'D'),
		);
		$this->assertEquals($expect, $rev['columns']);
	}

	// }}}
	// }}}
}
