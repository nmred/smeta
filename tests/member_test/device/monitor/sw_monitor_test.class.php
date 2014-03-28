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
 
namespace member_test\device\monitor;
use swan\test\sw_test_db;
use \lib\member\sw_member;

/**
+------------------------------------------------------------------------------
* 设备 monitor 测试 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_monitor_test extends sw_test_db
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
	// {{{ public function test_add_monitor()

	/**
	 * test_add_monitor 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_add_monitor()
	{
		$device_property_key     = sw_member::property_factory('device_key', array('device_id' => '2'));
		$madapter_property_basic = sw_member::property_factory('madapter_basic', array('madapter_id' => '1'));
		$device_property_monitor = sw_member::property_factory('device_monitor', array('monitor_name' => 'apache_web1'));
		$device_property_monitor->set_madapter_basic($madapter_property_basic);
		$monitor_params[] = sw_member::property_factory('madapter_params', array('attr_id' => '1', 'value' => 'http://www.apache_web1.com/server_status'));
		$monitor_params[] = sw_member::property_factory('madapter_params', array('attr_id' => '2', 'value' => 'http://www.apache_web2.com/server_status'));
		$device_property_monitor->set_monitor_params($monitor_params);
		$device     = sw_member::operator_factory('device', $device_property_key);
		$monitor_id = $device->get_operator('monitor')->add_monitor($device_property_monitor);

		$this->assertEquals(1, $monitor_id);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_monitor', 'select * from device_monitor');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('device_monitor');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_get_monitor()

	/**
	 * test_get_monitor 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_monitor()
	{
		$condition = sw_member::condition_factory('get_device_monitor');
		$condition->set_in('device_id');
		$condition->set_device_id(1);
		$device = sw_member::operator_factory('device');
		$device_monitor = $device->get_operator('monitor')->get_monitor($condition);

		$query_table  = $this->array_to_dbset(array('device_monitor' => $device_monitor))
							 ->getTable('device_monitor');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('device_monitor');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_mod_monitor()

	/**
	 * test_mod_monitor 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_mod_monitor()
	{
		$device_property_key = sw_member::property_factory('device_key', array('device_id' => '1'));
		$madapter_property_basic  = sw_member::property_factory('madapter_basic', array('madapter_id' => '1'));
		$monitor_params[] = sw_member::property_factory('madapter_params', array('value' => 'http://www.apache_web1_mod.com/server_status', 'attr_id' => '1'));
		$monitor_params[] = sw_member::property_factory('madapter_params', array('value' => 'http://www.apache_web1_mod.com/server_status', 'attr_id' => '2'));
		$device_property_monitor = sw_member::property_factory('device_monitor');
		$device_property_monitor->set_madapter_basic($madapter_property_basic);
		$device_property_monitor->set_monitor_params($monitor_params);
		$device_property_monitor->set_monitor_id(1);
		$condition = sw_member::condition_factory('mod_device_monitor');
		$condition->set_property($device_property_monitor);
		$device = sw_member::operator_factory('device', $device_property_key);
		$monitor_id = $device->get_operator('monitor')->mod_monitor($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_monitor_params', 'select * from device_monitor_params');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('device_monitor_params');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_del_monitor()

	/**
	 * test_del_monitor 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_del_monitor()
	{
		$device_property_key = sw_member::property_factory('device_key', array('device_id' => '1'));
		$condition = sw_member::condition_factory('del_device_monitor');
		$condition->set_in('monitor_id');
		$condition->set_monitor_id(1);
		$device = sw_member::operator_factory('device', $device_property_key);
		$device->get_operator('monitor')->del_monitor($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_monitor', 'select * from device_monitor');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('device_monitor');
		$this->assertTablesEqual($expect, $query_table);

		$query_params = $this->getConnection()
			                 ->CreateQueryTable('device_monitor_params', 'select * from device_monitor_params');
		$expect_params = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('device_monitor_params');
		$this->assertTablesEqual($expect_params, $query_params);
	}

	// }}}
	// {{{ public function test_get_monitor_params()

	/**
	 * test_get_monitor_params 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_monitor_params()
	{
		$condition = sw_member::condition_factory('get_device_monitor_params');
		$condition->set_in('device_id');
		$condition->set_device_id(1);
		$condition->set_in('monitor_id');
		$condition->set_monitor_id(1);
		$device = sw_member::operator_factory('device');
		$device_monitor = $device->get_operator('monitor')->get_monitor_params($condition);

		$query_table  = $this->array_to_dbset(array('monitor_params' => $device_monitor))
							 ->getTable('monitor_params');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_params_result.xml')
			           ->getTable('monitor_params');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
