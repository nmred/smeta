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
 
namespace api_test\dev\attr;
use swan\test\sw_test_db;
use mock\api_test\dev\sw_attribute as sw_mock_attribute;
use mock\api_test\sw_request;

/**
+------------------------------------------------------------------------------
* 监控适配器 attribute 测试 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_attribute extends sw_test_db
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
	protected $__attribute = null;

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
		$this->__attribute = sw_mock_attribute::get_instance($this);
		$this->__attribute->init();
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
			'name' => 'username',
			'madapter_id'  => 2,
			'display_name' => '用户名',
			'form_type' => 1,
			'attr_default' => 'root',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__attribute->action_add();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$this->assertEquals(1, $result['data']['attr_id']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_attribute', 'select * from madapter_attribute');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('madapter_attribute');
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
			'name' => 'url_b',
			'madapter_id' => 1,
			'attr_id' => 2,
			'display_name' => 'URL B 地址',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__attribute->action_mod();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_attribute', 'select * from madapter_attribute');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('madapter_attribute');
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
			'attr_id' => '1',
			'madapter_id' => '1',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__attribute->action_del();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_attribute', 'select * from madapter_attribute');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('madapter_attribute');
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
			'page' => 1,
			'page_count' => 20,
			'madapter_id' => 1
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__attribute->action_json();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->array_to_dbset(array('madapter_attribute' => $result['data']['result']))
							->getTable('madapter_attribute');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('madapter_attribute');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
