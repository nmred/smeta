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
 
namespace member_test\device\basic;
use swan\test\sw_test_db;
use \lib\member\sw_member;

/**
+------------------------------------------------------------------------------
* 设备 basic 测试 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_basic_test extends sw_test_db
{
	// {{{ consts
	// }}}
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function get_data_set()
	
	/**
	 * 初始化结果集 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_data_set() 
	{
		return array(
			dirname(__FILE__) . '/_files/prepare.xml',
		);
	}

	// }}}
	// {{{ public function setUp()

	/**
	 * setUp 
	 * 
	 * @access public
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
	}

	// }}}
	// {{{ public function test_add_basic()

	/**
	 * test_add_basic 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_add_basic()
	{
		$data = array(
			'device_display_name' => 'desc_lan-116',
			'host_name' => '192.168.1.116',
			'heartbeat_time' => 300,
		);
		$property_key   = sw_member::property_factory('device_key', array('device_id' => 3));
		$property_basic = sw_member::property_factory('device_basic', $data);
		$device    = sw_member::operator_factory('device', $property_key);
		$device_id = $device->get_operator('basic')->add_basic($property_basic);

		$this->assertEquals(3, $device_id);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_basic', 'select * from device_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('device_basic');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_get_basic()

	/**
	 * test_get_basic 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_basic()
	{
		$condition = sw_member::condition_factory('get_device_basic');
		$device    = sw_member::operator_factory('device');
		$device_basic = $device->get_operator('basic')->get_basic($condition);
		$query_table  = $this->array_to_dbset(array('device_basic' => $device_basic))
							 ->getTable('device_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('device_basic');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_mod_basic()

	/**
	 * test_mod_basic 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_mod_basic()
	{
		$data = array(
			'device_display_name' => 'desc_lan-116',
			'host_name' => '192.168.2.116',
			'heartbeat_time' => 350,
		);
		$property_basic = sw_member::property_factory('device_basic', $data);
		$condition = sw_member::condition_factory('mod_device_basic', array('device_id' => 2));
		$condition->set_property($property_basic);
		$condition->set_in('device_id');
		$device = sw_member::operator_factory('device');
		$device->get_operator('basic')->mod_basic($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_basic', 'select * from device_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('device_basic');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_del_basic()

	/**
	 * test_del_basic 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_del_basic()
	{
		$data = array(
			'device_display_name' => 'desc_lan-116',
			'host_name' => '192.168.2.116',
			'heartbeat_time' => 350,
		);
		$condition = sw_member::condition_factory('del_device_basic', array('device_id' => 2));
		$condition->set_in('device_id');
		$device = sw_member::operator_factory('device');
		$device->get_operator('basic')->del_basic($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_basic', 'select * from device_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('device_basic');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
