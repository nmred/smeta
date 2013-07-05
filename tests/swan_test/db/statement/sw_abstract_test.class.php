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
use lib\db\adapter\sw_mysql;
use lib\db\statement\sw_standard;
use lib\db\statement\exception\sw_exception;
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
class sw_abstract_test extends \PHPUnit_Extensions_Database_TestCase
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
		$this->__data_set = $this->getConnection()->createDataSet(array('guestbook'));
		parent::setUp();
	}

	// }}}
	// {{{ public function tearDown()

	/**
	 * setUp 
	 * 
	 * @access public
	 * @return void
	 */
	public function tearDown()
	{
		$this->getDatabaseTester()->setDataSet($this->__data_set);
		parent::tearDown();
		$this->__db = null;
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
			$stmt = new sw_standard($this->__db, 'select * from guestbook where id > ?;');
			$id = 1;
			$stmt->bind_param(1, $id);
			var_dump($stmt->execute());
			$a = $stmt->fetch_all();
			var_dump($a);
		} catch (sw_exception $e) {
		//	$this->assertContains('param error', $e->getMessage());	
		}

		$this->assertEquals(2, $this->getConnection()->getRowCount('guestbook'), "Pre-Condition");

		$this->assertEquals(2, $this->getConnection()->getRowCount('guestbook'), "Inserting failed");
	}

	// }}}
	// {{{ public function test_construct1()

	/**
	 * test_construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_construct1()
	{
		try {
			$stmt = new sw_standard($this->__db, 'select * from guestbook where id > ?;');
			$id = 2;
			$stmt->bind_param(1, $id);
			var_dump($stmt->execute());
			$a = $stmt->fetch_all();
			var_dump($a);
		} catch (sw_exception $e) {
		//	$this->assertContains('param error', $e->getMessage());	
		}

		$this->assertEquals(2, $this->getConnection()->getRowCount('guestbook'), "Pre-Condition");

		$this->assertEquals(2, $this->getConnection()->getRowCount('guestbook'), "Inserting failed");
	}

	// }}}
	// {{{ public function getConnection()
	
	/**
	 * 获取数据库连接 
	 * 
	 * @access public
	 * @return \PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	public function getConnection()
	{
		$conn = $this->__db->get_connection();	
		return $this->createDefaultDBConnection($conn);
	}

	// }}}
	// {{{ public function getDataSet()

	/**
	 * 获取数据集 
	 * 
	 * @access public
	 * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	public function getDataSet()
	{
		$data =  $this->createXMLDataSet(dirname(__FILE__) . '/_files/guestbook.xml');		
		return $data;
	}

	// }}}
	// }}}
}
