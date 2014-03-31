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
 
namespace api_test\user\device;
use swan\test\sw_test_db;
use mock\api_test\user\sw_device as sw_mock_device;
use mock\api_test\sw_request;

/**
+------------------------------------------------------------------------------
* 设备测试 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_device extends sw_test_db
{
	// {{{ consts
	// }}}
	// {{{ members
	
	/**
	 * 操作对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__device = null;

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
		$this->__device = sw_mock_device::get_instance($this);
		$this->__device->init();
	}

	// }}}
	// {{{ public function test_action_add()
	
	/**
	 * test_action_add 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_action_add()
	{
		$post_data = array(
			'name' => 'lan-116',
			'host_name' => '192.168.1.116',
			'heartbeat_time' => 300,
			'display_name' => 'desc_lan-116',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__device->action_add();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$this->assertEquals(3, $result['data']['device_id']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_key', 'select * from device_key');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('device_key');
		$this->assertTablesEqual($expect, $query_table);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_basic', 'select * from device_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('device_basic');
		$this->assertTablesEqual($expect, $query_table);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_monitor', 'select * from device_monitor');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('device_monitor');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_action_mod()
	
	/**
	 * test_action_mod 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_action_mod()
	{
		$post_data = array(
			'device_id' => 2,
			'host_name' => '192.168.1.116',
			'heartbeat_time' => 300,
			'display_name' => 'desc_lan-116',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__device->action_mod();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_basic', 'select * from device_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('device_basic');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_action_del()
	
	/**
	 * test_action_del 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_action_del()
	{
		$post_data = array(
			'device_id'   => '3',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$this->__device->action_add();	
		$result = $this->__device->action_del();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_basic', 'select * from device_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('device_basic');
		$this->assertTablesEqual($expect, $query_table);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_key', 'select * from device_key');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('device_key');
		$this->assertTablesEqual($expect, $query_table);
		//$query_table = $this->getConnection()
		//	                ->CreateQueryTable('device_monitor', 'select * from device_monitor');
		//$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
		//	           ->getTable('device_monitor');
		//$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_action_json()
	
	/**
	 * test_action_json 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_action_json()
	{
		//$post_data = array(
		//	'page' => 1,
		//	'page_count' => 20,
		//	'madapter_id' => 1
		//);	

		//// 初始化 POST 参数
		//sw_request::get_instance($post_data);
		//$result = $this->__metric->action_json();	
		//$expect = 10000; 
		//$this->assertEquals($expect, $result['code']);
		//$query_table = $this->array_to_dbset(array('madapter_metric' => $result['data']['result']))
		//					->getTable('madapter_metric');
		//$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
		//	           ->getTable('madapter_metric');
		//$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
