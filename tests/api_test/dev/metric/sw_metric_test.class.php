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
 
namespace api_test\dev\metric;
use swan\test\sw_test_db;
use mock\api_test\dev\sw_metric as sw_mock_metric;
use mock\api_test\sw_request;

/**
+------------------------------------------------------------------------------
* 监控适配器 metric 测试 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_metric extends sw_test_db
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
	protected $__metric = null;

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
		$this->__metric = sw_mock_metric::get_instance($this);
		$this->__metric->init();
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
			'name' => 'mysql_select',
			'madapter_id'    => 2,
			'collect_every'  => 60,
			'time_threshold' => 2000,
			'tmax' => 600,
			'dst_type' => 1,
			'unit' => 'N',
			'title' => 'MySQL - Select 查询数',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__metric->action_add();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$this->assertEquals(1, $result['data']['metric_id']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_metric', 'select * from madapter_metric');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('madapter_metric');
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
			'metric_id' => 1,
			'madapter_id' => 1,
			'time_threshold' => 1000,
			'tmax' => 300,
			'vmax' => 300,
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__metric->action_mod();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_metric', 'select * from madapter_metric');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('madapter_metric');
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
			'metric_id'   => '1',
			'madapter_id' => '1',
		);	

		// 初始化 POST 参数
		sw_request::get_instance($post_data);
		$result = $this->__metric->action_del();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_metric', 'select * from madapter_metric');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('madapter_metric');
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
		$result = $this->__metric->action_json();	
		$expect = 10000; 
		$this->assertEquals($expect, $result['code']);
		$query_table = $this->array_to_dbset(array('madapter_metric' => $result['data']['result']))
							->getTable('madapter_metric');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('madapter_metric');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
