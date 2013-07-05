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
 
namespace swan_test\db\statement;
use lib\test\sw_test_db;
use lib\db\statement\sw_standard;
use lib\db\statement\exception\sw_exception;
use PDO;

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
class sw_abstract_test extends sw_test_db
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function get_data_set()

	/**
	 * 获取数据集 
	 * 
	 * @access public
	 * @return mixed
	 */
	public function get_data_set()
	{
		return array(
			dirname(__FILE__) . '/_files/statement_pre.xml',
		);
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
		$stmt = new sw_standard($this->__db, 'select * from unit_host where host_id > ?;');
		$pdo_stmt = $stmt->get_stmt();
		$this->assertInstanceOf('\PDOStatement', $pdo_stmt);
	}

	// }}}
	// {{{ public function test_execute()

	/**
	 * test_execute
	 * 
	 * @access public
	 * @return void
	 */
	public function test_execute()
	{
		$stmt = new sw_standard($this->__db, 'select * from unit_host where host_id > ?;');
		$id = 1;
		$stmt->bind_param(1, $id);
		$stmt->execute();
		$this->assertEquals(2, count($stmt->fetch_all()));
	}

	// }}}
	// {{{ public function test_attribute()

	/**
	 * test_attribute
	 * 
	 * @access public
	 * @return void
	 */
	public function test_attribute()
	{
		$stmt = new sw_standard($this->__db, 'select * from unit_host where host_id > ?;');

		try {
			$attr = $stmt->set_attribute(PDO::ATTR_AUTOCOMMIT, 0);
		} catch (sw_exception $e) {
			if (false !== strpos($e->getMessage(), 'SQLSTATE[IM001]: Driver does not support')) {
				$this->markTestSkipped('Driver does not support this function: This driver doesn\'t support getting attributes');
			}
		}

		try {
			$attr = $stmt->get_attribute(PDO::ATTR_AUTOCOMMIT);
		} catch (sw_exception $e) {
			if (false !== strpos($e->getMessage(), 'SQLSTATE[IM001]: Driver does not support')) {
				$this->markTestSkipped('Driver does not support this function: This driver doesn\'t support getting attributes');
			}
		}

		$this->assertEquals(0, $attr);
	}

	// }}}
	// {{{ public function test_fetch_mode()

	/**
	 * test_fetch_mode 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_fetch_mode()
	{	
		$stmt = new sw_standard($this->__db, 'select * from unit_host where host_id > ?;');
		$id = 1;
		$stmt->bind_param(1, $id);
		$stmt->execute();
		$stmt->set_fetch_mode(PDO::FETCH_NUM);
		$result = $stmt->fetch();

		$expect = array('2', '1', 'lan-114');
		$this->assertEquals($expect, $result);
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
		$stmt = new sw_standard($this->__db, 'select * from unit_host where host_id > ?;');
		$adapter = $stmt->get_adapter();
		$this->assertInstanceOf('\lib\db\adapter\sw_mysql', $adapter);
	}

	// }}}
	// {{{ public function test_column_count()

	/**
	 * test_column_count 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_column_count()
	{
		$stmt = new sw_standard($this->__db, 'select * from unit_host where host_id > ?;');
		$id = 1;
		$stmt->bind_param(1, $id);
		$stmt->execute();
		$cols = $stmt->column_count();
		$this->assertEquals(3, $cols);
	}
	// }}}
	// {{{ public function test_error_code()

	/**
	 * test_error_code 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_error_code()
	{
		$stmt = new sw_standard($this->__db, 'select * from product where host_id > ?;');
		$id = 1;
		try {
			$stmt->bind_param(1, $id);
			$stmt->execute();
		} catch (sw_exception $e) {
		}
		$this->assertEquals('42S02', $stmt->error_code());
		$error_info = $stmt->error_info();
		$this->assertContains('Table \'swan_soft.product\' doesn\'t exist', isset($error_info[2]) ? $error_info[2] : null);
	}

	// }}}
	// {{{ public function test_next_rowset()

	/**
	 * test_next_rowset
	 * 
	 * @access public
	 * @return void
	 */
	public function test_next_rowset()
	{
		$stmt = new sw_standard($this->__db, 'select * from unit_host;');
		$stmt->execute();
		$rev = $stmt->next_rowset();
		$this->assertEquals(false, $rev);
	}

	// }}}
	// {{{ public function test_row_count()

	/**
	 * test_row_count
	 * 
	 * @access public
	 * @return void
	 */
	public function test_row_count()
	{
		$stmt = new sw_standard($this->__db, 'select * from unit_host;');
		$stmt->execute();
		$rev = $stmt->row_count();
		$this->assertEquals(3, $rev);
	}

	// }}}
	// {{{ public function test_fetch()

	/**
	 * test_fetch 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_fetch()
	{
		$stmt = new sw_standard($this->__db, 'select * from unit_host;');
		$stmt->execute();

		// assoc
		$rev = $stmt->fetch();
		$expect = array(
			'host_id' => 1,
			'group_id' => 1,
			'host_name' => 'lan-127.0.0.1',
		);
		$this->assertEquals($expect, $rev);

		// num
		$rev = $stmt->fetch(PDO::FETCH_NUM);
		$expect = array(
			2, 1, 'lan-114',
		);
		$this->assertEquals($expect, $rev);

		//both
		$rev = $stmt->fetch(PDO::FETCH_BOTH);
		$expect = array(
			'host_id' => 3,
			0 => 3,
			'group_id' => '1',
			1 => 1,
			'host_name' => 'lan-115',
			2 => 'lan-115',
		);
		$this->assertEquals($expect, $rev);

		$stmt = new sw_standard($this->__db, 'select host_id, host_name from unit_host;');
		$stmt->execute();
		// bound
		$stmt->bind_column(1, $host_id);
		$stmt->bind_column(2, $host_name);
		$stmt->fetch(PDO::FETCH_BOUND);
		$this->assertEquals(1, $host_id);
		$this->assertEquals('lan-127.0.0.1', $host_name);
	}

	// }}}
	// }}}
}
