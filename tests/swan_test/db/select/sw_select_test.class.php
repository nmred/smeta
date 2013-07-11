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

	const INNER_JOIN     = 'inner join';
	const LEFT_JOIN      = 'left join';
	const RIGHT_JOIN     = 'right join';
	const FULL_JOIN      = 'full join';
	const CROSS_JOIN     = 'cross join';
	const NATURAL_JOIN   = 'natural join';

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
	// {{{ public function test_bind()

	/**
	 * test_bind 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_bind()
	{
		$mock = new sw_select($this->__db);
		
		// 1
		$mock->bind(array('test'));
		$rev = $mock->get_bind();
		$this->assertEquals(array('test'), $rev);
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
	// {{{ public function test_from()

	/**
	 * test_from 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_from()
	{
		$mock = new sw_select($this->__db);
		$mock->init_parts();
		$mock->from(array('user', 'group'), array('name', 'id'));
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
	}

	// }}}
	// {{{ public function test_union()

	/**
	 * test_union 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_union()
	{
		$mock = new sw_select($this->__db);
		$mock->init_parts();
		$select = array('select * from unit_host');
		$mock->union($select);
		$rev = $mock->get_parts();
		$expect = array(
			array(
				'select * from unit_host',
				self::SQL_UNION,	
			),
		);
		$this->assertEquals($expect, $rev[self::UNION]);

		$mock->init_parts();
		$select = 'select * from unit_host';
		try {
			$mock->union($select);
			$rev = $mock->get_parts();
		} catch (sw_exception $e) {
			$this->assertContains('union() only accepts an array of sw_select', $e->getMessage());		
		}

		$mock->init_parts();
		$select = array('select * from unit_host');
		try {
			$mock->union($select, 'invalid type');
			$rev = $mock->get_parts();
		} catch (sw_exception $e) {
			$this->assertContains('Invalid union type', $e->getMessage());		
		}
	}

	// }}}
	// {{{ public function test_join()

	/**
	 * test_join 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_join()
	{
		$mock = new sw_select($this->__db);
		$mock->init_parts();
		$mock->join(array('user'), 'user.id = group.id');
		$rev = $mock->get_parts();
		$expect_from = array(
			'user' => array(
				'join_type'      => self::INNER_JOIN,
				'schema'         => null,
				'table_name'     => 'user',
				'join_condition' => 'user.id = group.id',
			),
		);
		$this->assertEquals($expect_from, $rev[self::FROM]);
	}

	// }}}
	// {{{ public function test_join_inner()

	/**
	 * test_join_inner
	 * 
	 * @access public
	 * @return void
	 */
	public function test_join_inner()
	{
		$mock = new sw_select($this->__db);
		$mock->init_parts();
		$mock->join_inner(array('user'), 'user.id = group.id');
		$rev = $mock->get_parts();
		$expect_from = array(
			'user' => array(
				'join_type'      => self::INNER_JOIN,
				'schema'         => null,
				'table_name'     => 'user',
				'join_condition' => 'user.id = group.id',
			),
		);
		$this->assertEquals($expect_from, $rev[self::FROM]);
	}

	// }}}
	// {{{ public function test_join_left()

	/**
	 * test_join_left
	 * 
	 * @access public
	 * @return void
	 */
	public function test_join_left()
	{
		$mock = new sw_select($this->__db);
		$mock->init_parts();
		$mock->join_left(array('user'), 'user.id = group.id');
		$rev = $mock->get_parts();
		$expect_from = array(
			'user' => array(
				'join_type'      => self::LEFT_JOIN,
				'schema'         => null,
				'table_name'     => 'user',
				'join_condition' => 'user.id = group.id',
			),
		);
		$this->assertEquals($expect_from, $rev[self::FROM]);
	}

	// }}}
	// {{{ public function test_join_right()

	/**
	 * test_join_right
	 * 
	 * @access public
	 * @return void
	 */
	public function test_join_right()
	{
		$mock = new sw_select($this->__db);
		$mock->init_parts();
		$mock->join_right(array('user'), 'user.id = group.id');
		$rev = $mock->get_parts();
		$expect_from = array(
			'user' => array(
				'join_type'      => self::RIGHT_JOIN,
				'schema'         => null,
				'table_name'     => 'user',
				'join_condition' => 'user.id = group.id',
			),
		);
		$this->assertEquals($expect_from, $rev[self::FROM]);
	}

	// }}}
	// {{{ public function test_join_full()

	/**
	 * test_join_full
	 * 
	 * @access public
	 * @return void
	 */
	public function test_join_full()
	{
		$mock = new sw_select($this->__db);
		$mock->init_parts();
		$mock->join_full(array('user'), 'user.id = group.id');
		$rev = $mock->get_parts();
		$expect_from = array(
			'user' => array(
				'join_type'      => self::FULL_JOIN,
				'schema'         => null,
				'table_name'     => 'user',
				'join_condition' => 'user.id = group.id',
			),
		);
		$this->assertEquals($expect_from, $rev[self::FROM]);
	}

	// }}}
	// {{{ public function test_join_cross()

	/**
	 * test_join_cross
	 * 
	 * @access public
	 * @return void
	 */
	public function test_join_cross()
	{
		$mock = new sw_select($this->__db);
		$mock->init_parts();
		$mock->join_cross(array('user'), 'user.id = group.id');
		$rev = $mock->get_parts();
		$expect_from = array(
			'user' => array(
				'join_type'      => self::CROSS_JOIN,
				'schema'         => null,
				'table_name'     => 'user',
				'join_condition' => 'user.id = group.id',
			),
		);
		$this->assertEquals($expect_from, $rev[self::FROM]);
	}

	// }}}
	// {{{ public function test_join_natural()

	/**
	 * test_join_natural
	 * 
	 * @access public
	 * @return void
	 */
	public function test_join_natural()
	{
		$mock = new sw_select($this->__db);
		$mock->init_parts();
		$mock->join_natural(array('user'), 'user.id = group.id');
		$rev = $mock->get_parts();
		$expect_from = array(
			'user' => array(
				'join_type'      => self::NATURAL_JOIN,
				'schema'         => null,
				'table_name'     => 'user',
				'join_condition' => 'user.id = group.id',
			),
		);
		$this->assertEquals($expect_from, $rev[self::FROM]);
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
	// {{{ public function test__where()

	/**
	 * test__where 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__where()
	{
		$mock = new sw_select($this->__db);
		// 1
		$mock->set_parts(self::UNION, array('test union'));
		try {
			$mock->mock_where('name > ?', 2);	
		} catch (sw_exception $e) {
			$this->assertContains('Invalid use of where clause with', $e->getMessage());	
		}

		// 2
		$mock->init_parts();
		$rev = $mock->mock_where('name > ?', 'test');
		$this->assertEquals('(name > \'test\')', $rev);

		// 3
		$mock->init_parts();
		$rev = $mock->mock_where('name > 3');
		$this->assertEquals('(name > 3)', $rev);

		// 4
		$mock->set_parts(self::WHERE, array('(id > 2)'));
		$rev = $mock->mock_where('name > 3');
		$this->assertEquals('AND (name > 3)', $rev);
	}

	// }}}
	// {{{ public function test_where()

	/**
	 * where 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_where()
	{
		$mock = new sw_select($this->__db);
		// 1
		$mock->init_parts();
		$mock->where('name > ?', 'test');
		$mock->where('name > 3');
		$expect = array(
			'(name > \'test\')',
			'AND (name > 3)',
		);
		$rev = $mock->get_parts();
		$this->assertEquals($expect, $rev[self::WHERE]);
	}

	// }}}
	// {{{ public function test_or_where()

	/**
	 * or_where 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_where_or()
	{
		$mock = new sw_select($this->__db);
		// 1
		$mock->init_parts();
		$mock->or_where('name > ?', 'test');
		$mock->or_where('name > 3');
		$expect = array(
			'(name > \'test\')',
			'OR (name > 3)',
		);
		$rev = $mock->get_parts();
		$this->assertEquals($expect, $rev[self::WHERE]);
	}

	// }}}
	// {{{ public function test_group()

	/**
	 * test_group 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_group()
	{
		$mock = new sw_select($this->__db);
		// 1
		$mock->init_parts();
		$group = array(
			'user',
		);
		$mock->group($group);
		$rev = $mock->get_parts();
		$this->assertEquals(array('user'), $rev[self::GROUP]);

		$mock->init_parts();
		$group = array(
			'(id = id + 1)',
		);
		$mock->group($group);
		$rev = $mock->get_parts();
		$this->assertInstanceOf('\lib\db\sw_db_expr', $rev[self::GROUP][0]);
	}

	// }}}
	// {{{ public function test_having()

	/**
	 * having
	 * 
	 * @access public
	 * @return void
	 */
	public function test_having()
	{
		$mock = new sw_select($this->__db);
		// 1
		$mock->init_parts();
		$mock->having('name > ?', 'test');
		$mock->having('name > 3');
		$expect = array(
			'(name > \'test\')',
			'AND (name > 3)',
		);
		$rev = $mock->get_parts();
		$this->assertEquals($expect, $rev[self::HAVING]);
	}

	// }}}
	// {{{ public function test_or_having()

	/**
	 * or_having 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_or_having()
	{
		$mock = new sw_select($this->__db);
		// 1
		$mock->init_parts();
		$mock->or_having('name > ?', 'test');
		$mock->or_having('name > 3');
		$expect = array(
			'(name > \'test\')',
			'OR (name > 3)',
		);
		$rev = $mock->get_parts();
		$this->assertEquals($expect, $rev[self::HAVING]);
	}

	// }}}
	// {{{ public function test_order()

	/**
	 * test_order 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_order()
	{
		$mock = new sw_select($this->__db);
		// 1
		$mock->init_parts();
		$order = array(
			'name',
		);
		$mock->order($order);
		$rev = $mock->get_parts();
		$this->assertEquals(array(array('name', self::SQL_ASC)), $rev[self::ORDER]);

		// 2
		$mock->init_parts();
		$order = array(
			'(id = id + 1)',
		);
		$mock->order($order);
		$rev = $mock->get_parts();
		$this->assertInstanceOf('\lib\db\sw_db_expr', $rev[self::ORDER][0][0]);
	}

	// }}}
	// {{{ public function test_limit()

	/**
	 * test_limit 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_limit()
	{
		$mock = new sw_select($this->__db);
		$mock->limit(1, 20);
		$rev = $mock->get_parts();
		$this->assertEquals(1, $rev[self::LIMIT_COUNT]);
		$this->assertEquals(20, $rev[self::LIMIT_OFFSET]);
	}

	// }}}
	// {{{ public function test_limit_page()

	/**
	 * test_limit_page
	 * 
	 * @access public
	 * @return void
	 */
	public function test_limit_page()
	{
		$mock = new sw_select($this->__db);
		$mock->limit_page(1, 20);
		$rev = $mock->get_parts();
		$this->assertEquals(1, $rev[self::LIMIT_COUNT]);
		$this->assertEquals(20, $rev[self::LIMIT_OFFSET]);
	}

	// }}}
	// {{{ public function test_for_update()

	/**
	 * test_for_update 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_for_update()
	{
		$mock = new sw_select($this->__db);
		$mock->for_update(false);
		$rev = $mock->get_parts();
		$this->assertEquals(false, $rev[self::FOR_UPDATE]);
	}

	// }}}
	// {{{ public function test_get_part()

	/**
	 * 获取子句 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_part()
	{
		$mock = new sw_select($this->__db);
		
		// 1
		$mock->set_parts(self::FROM, array('test'));
		$rev = $mock->get_part(self::FROM);
		$this->assertEquals(array('test'), $rev);

		// 2
		try {
			$mock->get_part('invalid');	
		} catch (sw_exception $e) {
			$this->assertContains('Invalid Select part', $e->getMessage());	
		}
	}

	// }}}
	// {{{ public function test_query()
	
	/**
	 * test_query 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_query()
	{
		
	}
	 
	// }}}
	// {{{ public function test_assemble()

	/**
	 * test_assemble 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_assemble()
	{
	}

	// }}}
	// {{{ public function test_reset()

	/**
	 * test_reset 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_reset()
	{
		$mock = new sw_select($this->__db);

		$mock->set_parts(self::FROM, array('test'));
		$mock->reset(self::FROM);

		$rev = $mock->get_part(self::FROM);
		$this->assertEquals(array(), $rev);
	}

	// }}}
	// {{{ public function test__get_quoted_schema()

	/**
	 * test__get_quoted_schema 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__get_quoted_schema()
	{
		$mock = new sw_select($this->__db);
		$rev = $mock->mock_get_quoted_schema('user');
		$this->assertEquals('`user`.', $rev);
	}

	// }}}
	// {{{ public function test__get_quoted_table()

	/**
	 * test__get_quoted_table 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__get_quoted_table()
	{
		$mock = new sw_select($this->__db);
		$rev = $mock->mock_get_quoted_table('user', 'U');
		$this->assertEquals('`user` AS `U`', $rev);
	}

	// }}}
	// {{{ public function test__render_columns()

	/**
	 * test__render_columns 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__render_columns()
	{
		$mock = new sw_select($this->__db);

		// 1
		$rev = $mock->mock_render_columns('select');
		$this->assertEquals(null, $rev);
		
		// 2
		$mock->init_parts();
		$mock->set_parts(self::FROM, array('user' => array()));
		$mock->columns(array('id', 'name'));
		$rev = $mock->mock_render_columns('select');
		$this->assertEquals('select `user`.`id`, `user`.`name`', $rev);

		// 3
		$mock->init_parts();
		$mock->set_parts(self::FROM, array('user' => array()));
		$mock->columns();
		$rev = $mock->mock_render_columns('select');
		$this->assertEquals('select `user`.*', $rev);

		// 4
		$mock->init_parts();
		$sw_expr = $this->getMockBuilder('lib\db\sw_db_expr')
						  ->setConstructorArgs(array('group_id = group + 1'))
						  ->getMock();
		$sw_expr->expects($this->once())
				  ->method('__toString')
				  ->will($this->returnValue('group_id = group + 1'));
		$mock->set_parts(self::FROM, array('user' => array()));
		$mock->columns($sw_expr);
		$rev = $mock->mock_render_columns('select');
		$this->assertEquals('select group_id = group + 1', $rev);
	}

	// }}}
	// {{{ public function test__render_from()

	/**
	 * test__render_from 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__render_from()
	{
		$mock = new sw_select($this->__db);

		// 1
		$rev = $mock->mock_render_from('select version();');
		$this->assertEquals('select version();', $rev);

		// 2
		$mock->from('user AS U', array('id', 'name'));
		$rev = $mock->mock_render_from('select `user`.`id`');
		$this->assertEquals('select `user`.`id` FROM `user` AS `U`', $rev);

		// 3
		$mock->init_parts();
		$mock->from('user AS U', array('id', 'name'))
			 ->columns()
			 ->join('group AS G', 'group.id = user.id');
		$rev = $mock->mock_render_from('select `user`.`id`');
		$this->assertEquals("select `user`.`id` FROM `user` AS `U`\n INNER JOIN `group` AS `G` ON group.id = user.id", $rev);
	}

	// }}}
	// {{{ public function test__render_union()

	/**
	 * test__render_union 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__render_union()
	{
		$mock = new sw_select($this->__db);
		
		// 1
		$sw_select = $this->getMockBuilder('lib\db\select\sw_select')
						  ->setConstructorArgs(array($this->__db))
						  ->getMock();
		$sw_select->expects($this->any())
				  ->method('assemble')
				  ->will($this->returnValue('user_id >= 1'));
		$union = array(
			$sw_select,
			'select * from group',
		);
		$mock->union($union);
		$rev = $mock->mock_render_union('select ');
		$this->assertEquals('select user_id >= 1 UNION select * from group', $rev);
	}

	// }}}
	// {{{ public function test__render_where()

	/**
	 * test__render_where 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__render_where()
	{
		$mock = new sw_select($this->__db);
		
		$mock->from('user', array('id', 'name'));
		$mock->where('id > ? ', '4')->where('name = ?', 'lily');
		$rev = $mock->mock_render_where('select * from `user` ');
		$this->assertEquals("select * from `user`  WHERE (id > '4' ) AND (name = 'lily')", $rev);	
	}

	// }}}
	// {{{ public function test__render_group()

	/**
	 * test__render_group 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__render_group()
	{
		$mock = new sw_select($this->__db);

		// 1
		$mock->from('foo', 'COUNT(id)');
		$mock->group('bar');
		$rev = $mock->mock_render_group(' ');
		$this->assertEquals('  GROUP BY `bar`', $rev);

		// 2
		$mock->init_parts();
		$mock->from('foo', 'COUNT(id)');
		$mock->group(array('bar', 'baz'));
		$rev = $mock->mock_render_group(' ');
		$this->assertEquals("  GROUP BY `bar`,\n\t`baz`", $rev);
	}

	// }}}
	// {{{ public function test__render_having()

	/**
	 * test__render_having 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__render_having()
	{
		$mock = new sw_select($this->__db);

		// 1
		$mock->from('foo', 'COUNT(id)');
		$mock->group('bar');
		$mock->having('id > ? ', 2);
		$rev = $mock->mock_render_having(' ');
		$this->assertEquals('  HAVING (id > 2 )', $rev);
	}

	// }}}
	// {{{ public function test__render_order()

	/**
	 * test__render_order 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__render_order()
	{
		$mock = new sw_select($this->__db);

		// 1
		$mock->from('foo', 'COUNT(id)');
		$mock->order('id');
		$mock->order('name DESC');
		$rev = $mock->mock_render_order(' ');
		$this->assertEquals('  ORDER BY `id` ASC, `name` DESC', $rev);
	}

	// }}}
	// {{{ public function test__render_limit()
	
	/**
	 * test__render_limit 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__render_limit()
	{	
	}

	// }}} 
	// {{{ public function test___call()

	/**
	 * test___call 
	 * 
	 * @access public
	 * @return void
	 */
	public function test___call()
	{	
		$mock = new sw_select($this->__db);

		// 1
		try {
			$mock->join_xxx_using();	
		} catch (sw_exception $e) {
			$this->assertContains('Unrecognized method ', $e->getMessage());
		}

		// 2
		try {
			$mock->join_cross_using();	
		} catch (sw_exception $e) {
			$this->assertContains('Cannot perform a join_using with method', $e->getMessage());
		}

		// 3
		try {
			$mock->join_inner_using('table2', array('id', 'name'));	
		} catch (sw_exception $e) {
			$this->assertContains('You can only perform a joinUsing after specifying a FROM table', $e->getMessage());
		}

		// 4 
		$mock->init_parts();
		$mock->from('table1');
		$mock->join_inner_using('table2', array('id', 'name'));
		$expect = array(
			'table1' => array(
				'join_type'  => self::FROM,
				'schema'     => null,
				'table_name' => 'table1',
				'join_condition' => null,
			),
			'table2' => array(
				'join_type'  => self::INNER_JOIN,
				'schema'     => null,
				'table_name' => 'table2',
				'join_condition' => '`table2`.id = `table1`.id AND `table2`.name = `table1`.name',
			),
		);
		$this->assertEquals($expect, $mock->get_part(self::FROM));
	}

	// }}}
	// }}}
}
