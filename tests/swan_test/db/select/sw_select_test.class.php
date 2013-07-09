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
	// {{{ consts

	const DISTINCT       = 'distinct';
	const COLUMNS        = 'columns';
	const FROM           = 'from';
	const UNION          = 'union';
	const WHERE          = 'where';
	const GROUP          = 'group';
	const HAVING         = 'having';
	const ORDER          = 'order';
	const LIMIT_COUNT    = 'limit_count';
	const LIMIT_OFFSET   = 'limit_offset';
	const FOR_UPDATE     = 'for_update';

	const INNER_JOIN     = 'inner_join';
	const LEFT_JOIN      = 'left_join';
	const RIGHT_JOIN     = 'right_join';
	const FULL_JOIN      = 'full_join';
	const CROSS_JOIN     = 'cross_join';
	const NATURAL_JOIN   = 'natural_join';

	const SQL_WILDCARD   = '*';
	const SQL_SELECT     = 'SELECT';
	const SQL_UNION      = 'UNION';
	const SQL_UNION_ALL  = 'UNION ALL';
	const SQL_FROM       = 'FROM';
	const SQL_WHERE      = 'WHERE';
	const SQL_DISTINCT   = 'DISTINCT';
	const SQL_GROUP_BY   = 'GROUP BY';
	const SQL_ORDER_BY   = 'ORDER BY';
	const SQL_HAVING     = 'HAVING';
	const SQL_FOR_UPDATE = 'FOR UPDATE';
	const SQL_AND        = 'AND';
	const SQL_AS         = 'AS';
	const SQL_OR         = 'OR';
	const SQL_ON         = 'ON';
	const SQL_ASC        = 'ASC';
	const SQL_DESC       = 'DESC';

	// }}}
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
		$this->assertEquals(true, $rev[self::DISTINCT]);
	}

	// }}}
	// {{{ public function test_columns()

	/**
	 * test_columns 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_columns()
	{
		// 1
		$mock = new sw_select($this->__db);
		try {
			$mock->columns(array('id', 'name'));
		} catch (sw_exception $e) {
			$this->assertContains('No table has been specified for the FROM cla', $e->getMessage());		
		}

		// 2
		$mock->set_parts(self::FROM, array('user' => array(), 'group' => array()));
		$mock->columns(array('id', 'name'));
		$rev = $mock->get_parts();
		$expect = array(
			array('user', 'id', null),
			array('user', 'name', null),
		);
		$this->assertEquals($expect, $rev[self::COLUMNS]);
	}

	// }}}
	// {{{ public function test__unique_correlation()

	/**
	 * test__unique_correlation 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__unique_correlation()
	{
		$mock = new sw_select($this->__db);
		// 1
		$name = 'user';
		$rev = $mock->mock_unique_correlation($name);
		$this->assertEquals('user', $rev);

		// 2
		$mock->set_parts(self::FROM, array('user' => array()));
		$name = 'user';
		$rev = $mock->mock_unique_correlation($name);
		$this->assertEquals('user_2', $rev);

		// 3
		$mock->set_parts(self::FROM, array('user' => array()));
		$name = 'swan_soft.user';
		$rev = $mock->mock_unique_correlation($name);
		$this->assertEquals('swan_soft.user_2', $rev);

		// 4
		$mock->set_parts(self::FROM, array('user' => array()));
		$name = array('user' => array(), 'group');
		$rev = $mock->mock_unique_correlation($name);
		$this->assertEquals('user_2', $rev);

		// 5
		$mock->set_parts(self::FROM, array('user' => array()));
		$name = array('user', 'group');
		$rev = $mock->mock_unique_correlation($name);
		$this->assertEquals('group', $rev);
	}
	
	// }}}
	// {{{ public function test__table_cols()

	/**
	 * test_table_cols 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__table_cols()
	{
		$mock = new sw_select($this->__db);
		// 1
		$cols = array('id', 'user.id', 'id as D');
		$mock->mock_table_cols('user', $cols);
		$rev = $mock->get_parts();
		$expect = array(
			array('user', 'id', null),
			array('user', 'id', null),
			array('user', 'id', 'D'),
		);
		$this->assertEquals($expect, $rev[self::COLUMNS]);

		// 2
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
		$this->assertEquals($expect, $rev[self::COLUMNS]);

		// 3
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
		$this->assertEquals($expect, $rev[self::COLUMNS]);

		// 4
		$mock = new sw_select($this->__db);
		$cols = array('id', 'user.id', 'id as D');
		$mock->mock_table_cols(null, $cols);
		$rev = $mock->get_parts();
		$expect = array(
			array('', 'id', null),
			array('user', 'id', null),
			array('', 'id', 'D'),
		);
		$this->assertEquals($expect, $rev[self::COLUMNS]);
	}

	// }}}
	// {{{ public function test__join()

	/**
	 * test__join 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__join()
	{
		$mock = new sw_select($this->__db);
		// 1
		try {
			$mock->mock_join('invalid type', 'user', null, array('name', 'id'));
		} catch (sw_exception $e) {
			$this->assertContains('Invalid join type', $e->getMessage());
		}

		// 2
		$mock->init_parts();
		$mock->set_parts(self::UNION, array('sql1', 'sql2'));
		try {
			$mock->mock_join(self::FROM, 'user', null, array('name', 'id'));
		} catch (sw_exception $e) {
			$this->assertContains('Invalid use of table with', $e->getMessage());
		}

		// 3 name is empty
		$mock->init_parts();
		$mock->mock_join(self::FROM, null, null, array('name', 'id'));
		$rev = $mock->get_parts();
		$expect = array(
			array('', 'name', null),
			array('', 'id', null),
		);
		$this->assertEquals($expect, $rev[self::COLUMNS]);

		// 4
		$mock->init_parts();
		$mock->mock_join(self::FROM, array('user', 'group'), null, array('name', 'id'));
		$rev = $mock->get_parts();
		$expect_column = array(
			array('user', 'name', null),
			array('user', 'id', null),
		);
		$expect_from = array(
			'user' => array(
				'join_type'      => self::FROM,
				'schema'         => null,
				'table_name'     => 'user',
				'join_condition' => null,
			),
		);
		$this->assertEquals($expect_column, $rev[self::COLUMNS]);
		$this->assertEquals($expect_from, $rev[self::FROM]);

		// 5
		$mock->init_parts();
		$mock->mock_join(self::FROM, array('U' => 'user', 'G' => 'group'), null, array('name', 'id'));
		$rev = $mock->get_parts();
		$expect_column = array(
			array('U', 'name', null),
			array('U', 'id', null),
		);
		$expect_from = array(
			'U' => array(
				'join_type'      => self::FROM,
				'schema'         => null,
				'table_name'     => 'user',
				'join_condition' => null,
			),
		);
		$this->assertEquals($expect_column, $rev[self::COLUMNS]);
		$this->assertEquals($expect_from, $rev[self::FROM]);

		// 6
		$mock->init_parts();
		$sw_expr = $this->getMockBuilder('lib\db\sw_db_expr')
						  ->setConstructorArgs(array('group_id = group + 1'))
						  ->getMock();
		$sw_expr->expects($this->once())
				  ->method('__toString')
				  ->will($this->returnValue('group_id = group + 1'));
		$mock->mock_join(self::FROM, $sw_expr, null, null);
		$rev = $mock->get_parts();
		$expect_from = array(
			't' => array(
				'join_type'      => self::FROM,
				'schema'         => null,
				'table_name'     => 'group_id = group + 1',
				'join_condition' => null,
			),
		);
		$this->assertEquals($expect_from, $rev[self::FROM]);

		// 7
		$mock->init_parts();
		$sw_select = $this->getMockBuilder('lib\db\select\sw_select')
						  ->setConstructorArgs(array($this->__db))
						  ->getMock();
		$sw_select->expects($this->any())
				  ->method('__toString')
				  ->will($this->returnValue('user_id >= 1'));
		$mock->mock_join(self::FROM, $sw_select, null, null);
		$rev = $mock->get_parts();
		$expect_from = array(
			't' => array(
				'join_type'      => self::FROM,
				'schema'         => null,
				'table_name'     => 'user_id >= 1',
				'join_condition' => null,
			),
		);
		$this->assertEquals($expect_from, $rev[self::FROM]);

		// 8
		$mock->init_parts();
		$mock->mock_join(self::FROM, 'user AS U', null, array('name', 'id'));
		$rev = $mock->get_parts();
		$expect_column = array(
			array('U', 'name', null),
			array('U', 'id', null),
		);
		$expect_from = array(
			'U' => array(
				'join_type'      => self::FROM,
				'schema'         => null,
				'table_name'     => 'user',
				'join_condition' => null,
			),
		);
		$this->assertEquals($expect_column, $rev[self::COLUMNS]);
		$this->assertEquals($expect_from, $rev[self::FROM]);

		// 9
		$mock->init_parts();
		$mock->mock_join(self::FROM, 'swan_soft.user AS U', null, array('name', 'id'));
		$rev = $mock->get_parts();
		$expect_column = array(
			array('U', 'name', null),
			array('U', 'id', null),
		);
		$expect_from = array(
			'U' => array(
				'join_type'      => self::FROM,
				'schema'         => 'swan_soft',
				'table_name'     => 'user',
				'join_condition' => null,
			),
		);
		$this->assertEquals($expect_column, $rev[self::COLUMNS]);
		$this->assertEquals($expect_from, $rev[self::FROM]);

		// 10
		$mock->init_parts();
		$mock->set_parts(self::FROM, array('U' => array()));
		try {
			$mock->mock_join(self::FROM, 'swan_soft.user AS U', null, array('name', 'id'));
		} catch (sw_exception $e) {
			$this->assertContains('You cannot define a correlation name', $e->getMessage());	
		}

		// 11
		$mock->init_parts();
		$mock->set_parts(self::FROM, array('U' => array('join_type' => self::FROM), 'U1' => array('join_type' => self::INNER_JOIN)));
		$mock->mock_join(self::FROM, 'swan_soft.user AS U2', null, array('name', 'id'));
		$rev = $mock->get_parts();
		$expect_from = array(
			'U' => array(
				'join_type'      => self::FROM,
			),
			'U2' => array(
				'join_type'      => self::FROM,
				'schema'         => 'swan_soft',
				'table_name'     => 'user',
				'join_condition' => null,
			),
			'U1' => array(
				'join_type'      => self::INNER_JOIN,
			),
		);
		$this->assertEquals($expect_from, $rev[self::FROM]);
	}

	// }}}
	// }}}
}
