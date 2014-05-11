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
 
namespace api_test\dev\madapter;
use swan\test\sw_test_db;
use mock\api_test\dev\sw_madapter as sw_mock_madapter;
use mock\api_test\sw_request;

/**
+------------------------------------------------------------------------------
* 监控适配器 basic 测试 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_madapter extends sw_test_db
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
	protected $__madapter = null;

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
		$this->__madapter = sw_mock_madapter::get_instance($this);
		$this->__madapter->init();
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
			'name' => 'nginx',
			'display_name' => 'nginx监控适配器',
			'steps' => 300,
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__madapter->action_add();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$this->assertEquals(3, $result['data']['madapter_id']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_basic', 'select * from madapter_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('madapter_basic');
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
			'madapter_id' => '2',
			'steps' => 350,
			'name' => 'nginx',
			'display_name' => 'nginx监控适配器',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__madapter->action_mod();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_basic', 'select * from madapter_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('madapter_basic');
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
			'madapter_id' => '2',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__madapter->action_del();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_basic', 'select * from madapter_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('madapter_basic');
		$this->assertTablesEqual($expect, $query_table);
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
			'start' => 0,
			'length' => 10,
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__madapter->action_json();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->array_to_dbset(array('madapter_basic' => $result['data']['result']))
			                ->getTable('madapter_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('madapter_basic');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
