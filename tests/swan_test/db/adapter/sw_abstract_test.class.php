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
 
namespace swan_test\db\adapter;
use lib\db\adapter\sw_mysql;
use lib\db\adapter\exception\sw_exception;
use lib\db\sw_db;

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
class sw_abstract_test extends \PHPunit_FrameWork_TestCase
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
		try {
			$db = new sw_mysql('aa');
		} catch (sw_exception $e) {
			$this->assertContains('config param must is array', $e->getMessage());	
		}

		$array = array('testkey' => 0);
		$db = new sw_mysql($array);
		$this->assertArrayHasKey('testkey', $db->get_config());
		$profiler = $this->__db->get_profiler();
		$this->assertInstanceof('lib\db\profiler\sw_profiler', $profiler);
	}

	// }}}
	// {{{ public function test_connect()

	/**
	 * test_connect 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_connect()
	{
		$conns = $this->__db->get_connection();
		$this->assertInstanceof('\PDO', $conns);
	}

	// }}}
	// {{{ public function test_is_connected()

	/**
	 * test_is_connected 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_is_connected()
	{
		$conns = $this->__db->is_connected();
		$this->assertFalse($conns);

		$conns = $this->__db->get_connection();
		$conns = $this->__db->is_connected();
		$this->assertTrue($conns);
	}

	// }}}
	// {{{ public function test_close_connection()

	/**
	 * test_close_connection
	 * 
	 * @access public
	 * @return void
	 */
	public function test_close_connection()
	{
		$conns = $this->__db->get_connection();
		$conns = $this->__db->is_connected();
		$this->assertTrue($conns);

		$this->__db->close_connection();

		$conns = $this->__db->is_connected();
		$this->assertFalse($conns);
	}

	// }}}
	// {{{ public function test_quote()

	/**
	 * test_quote
	 * 
	 * @access public
	 * @return void
	 */
	public function test_quote()
	{
		$sw_select = $this->getMock('lib\db\select\sw_select');
		$sw_select->expects($this->any())
				  ->method('assemble')
				  ->will($this->returnValue('user_id >= 1'));

		$rev = $this->__db->quote($sw_select);
		$this->assertEquals('(user_id >= 1)', $rev);

		$sw_expr = $this->getMockBuilder('lib\db\sw_db_expr')
						  ->setConstructorArgs(array('aa'))
						  ->getMock();
		$sw_expr->expects($this->once())
				  ->method('__toString')
				  ->will($this->returnValue('aa'));

		$rev = $this->__db->quote($sw_expr);
		$this->assertEquals('aa', $rev);

		$arr = array('a', 'b');
		$rev = $this->__db->quote($arr);
		$this->assertEquals("'a', 'b'", $rev);

		$quote_value = 2;
		$rev = $this->__db->quote($quote_value, \lib\db\sw_db::INT_TYPE);
		$this->assertEquals(2, $rev);

		$quote_value = 2e5;
		$rev = $this->__db->quote($quote_value, \lib\db\sw_db::BIGINT_TYPE);
		$this->assertEquals(200000, $rev);

		$quote_value = -0x12;
		$rev = $this->__db->quote($quote_value, \lib\db\sw_db::BIGINT_TYPE);
		$this->assertEquals(-18, $rev);

		$quote_value = 2.111111111111;
		$rev = $this->__db->quote($quote_value, \lib\db\sw_db::FLOAT_TYPE);
		$this->assertEquals(2.111111, $rev);
	}

	// }}}
	// {{{ public function test_quote_into()

	/**
	 * test_quote_into
	 * 
	 * @access public
	 * @return void
	 */
	public function test_quote_into()
	{
		$text = 'WHERE date < ?';
		$value = '2012-01-01';
		$rev = $this->__db->quote_into($text, $value);

		$this->assertEquals('WHERE date < \'2012-01-01\'', $rev);

		$text = 'WHERE date < ? name > ?';
		$value = '2012-01-01';
		$rev = $this->__db->quote_into($text, $value, null, 1);

		$this->assertEquals('WHERE date < \'2012-01-01\' name > ?', $rev);
	}

	// }}}
	// {{{ public function test_quote_table_as()

	/**
	 * test_quote_table_as
	 * 
	 * @access public
	 * @return void
	 */
	public function test_quote_table_as()
	{
		$sw_expr = $this->getMockBuilder('lib\db\sw_db_expr')
						  ->setConstructorArgs(array('aa'))
						  ->getMock();
		$sw_expr->expects($this->once())
				  ->method('__toString')
				  ->will($this->returnValue('aa'));

		$rev = $this->__db->quote_table_as($sw_expr);
		$this->assertEquals('aa', $rev);

		$sw_select = $this->getMock('lib\db\select\sw_select');
		$sw_select->expects($this->any())
				  ->method('assemble')
				  ->will($this->returnValue('user_id >= 1'));

		$rev = $this->__db->quote_table_as($sw_select);
		$this->assertEquals('(user_id >= 1)', $rev);

		$str = 'user';
		$alias = 'U';
		$rev = $this->__db->quote_table_as($str);
		$this->assertEquals('`user`', $rev);
		$rev = $this->__db->quote_table_as($str, $alias);
		$this->assertEquals('`user` AS `U`', $rev);

		$ident = array('user', 'user_name');
		$rev = $this->__db->quote_table_as($ident);
		$this->assertEquals('`user`.`user_name`', $rev);

		$str = 'user.user_name';
		$alias = 'user_name';
		$rev = $this->__db->quote_table_as($str, $alias);
		$this->assertEquals('`user`.`user_name`', $rev);

		$str = 'user';
		$rev = $this->__db->quote_table_as($str, null, true);
		$this->assertEquals('user', $rev);
	}

	// }}}
	// {{{ public function test_fold_case()

	/**
	 * test_fold_case
	 * 
	 * @access public
	 * @return void
	 */
	public function test_fold_case()
	{
		$str = 'aBc';

		$this->__db->fold_case($str);
		$this->assertEquals('aBc', $str);
	}

	// }}}
	// {{{ public function test_supports_parameters()

	/**
	 * test_supports_parameters
	 * 
	 * @access public
	 * @return void
	 */
	public function test_supports_parameters()
	{
		$type = 'named';
		$rev = $this->__db->supports_parameters($type);
		$this->assertEquals(true, $rev);

		$type = 'namedi1';
		$rev = $this->__db->supports_parameters($type);
		$this->assertEquals(false, $rev);
	}

	// }}}
	// {{{ public function test_get_server_version()

	/**
	 * test_supports_parameters
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_server_version()
	{
		$rev = $this->__db->get_server_version();
		$this->assertContains('5.', $rev);
	}

	// }}}
	// }}}
}
