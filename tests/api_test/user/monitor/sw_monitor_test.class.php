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
 
namespace api_test\user\monitor;
use swan\test\sw_test_db;
use mock\api_test\user\sw_monitor as sw_mock_monitor;
use mock\api_test\sw_request;

/**
+------------------------------------------------------------------------------
* 监控器测试 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_monitor extends sw_test_db
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
	protected $__monitor = null;

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
		$this->__monitor = sw_mock_monitor::get_instance($this);
		$this->__monitor->init();
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
		$attr_data = array(
			array('attr_id' => 1, 'value' => 'http://www.apache_web1.com/server_status'),
			array('attr_id' => 2, 'value' => 'http://www.apache_web2.com/server_status'),
		);
		$post_data = array(
			'device_id' => '2',
			'madapter_id' => '1',
			'monitor_name' => 'apache_web1',
			'attr_data' => json_encode($attr_data, true),
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__monitor->action_add();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$this->assertEquals(1, $result['data']['monitor_id']);
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
		$attr_data = array(
			array('attr_id' => 1, 'value' => 'http://www.apache_web1_mod.com/server_status'),
			array('attr_id' => 2, 'value' => 'http://www.apache_web1_mod.com/server_status'),
		);
		$post_data = array(
			'device_id' => '1',
			'monitor_id' => '1',
			'attr_data' => json_encode($attr_data, true),
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__monitor->action_mod();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_monitor_params', 'select * from device_monitor_params');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('device_monitor_params');
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
			'device_id'   => '1',
			'monitor_id'   => '1',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__monitor->action_del();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
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
	// {{{ public function test_action_json()
	
	/**
	 * test_action_json 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_action_json()
	{
		$post_data = array(
			'page' => 1,
			'page_count' => 20,
			'device_id' => 1
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__monitor->action_json();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table  = $this->array_to_dbset(array('device_monitor' => $result['data']['result']))
							 ->getTable('device_monitor');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('device_monitor');
		$this->assertTablesEqual($expect, $query_table);
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
		$post_data = array(
			'page' => 1,
			'page_count' => 20,
			'device_id' => 1,
			'monitor_id' => 1
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__monitor->action_info();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table  = $this->array_to_dbset(array('monitor_params' => $result['data']['result']))
							 ->getTable('monitor_params');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_params_result.xml')
			           ->getTable('monitor_params');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
