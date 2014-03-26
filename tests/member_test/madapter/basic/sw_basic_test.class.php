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
 
namespace member_test\madapter\basic;
use swan\test\sw_test_db;
use \lib\member\sw_member;

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
			'steps' => 300,
			'madapter_name' => 'nginx',
			'madapter_display_name' => 'nginx监控适配器',
			'store_type'    => 2,
			'madapter_type' => 2,
		);
		$basic_property = sw_member::property_factory('madapter_basic', $data);
		$madapter = sw_member::operator_factory('madapter', $basic_property);
		$madapter_id = $madapter->get_operator('basic')->add_basic();

		$this->assertEquals(3, $madapter_id);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_basic', 'select * from madapter_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('madapter_basic');
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
		$condition = sw_member::condition_factory('get_madapter_basic');
		$madapter  = sw_member::operator_factory('madapter');
		$madapter_basic = $madapter->get_operator('basic')->get_basic($condition);
		$query_table  = $this->array_to_dbset(array('madapter_basic' => $madapter_basic))
							 ->getTable('madapter_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('madapter_basic');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_get_info()

	/**
	 * test_get_info 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_info()
	{
		$data = array (
		     'madapter_id' => '1',
		     'madapter_name' => 'apache',
		     'madapter_display_name' => 'apache监控适配器',
		     'steps' => '300',
		     'store_type' => '2',
		     'madapter_type' => '2',
		);
		$madapter = sw_member::operator_factory('madapter');
		$info = $madapter->get_operator('basic')->get_info(1);
		$this->assertEquals($data, $info);
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
			'steps' => 350,
			'madapter_name' => 'ngnix',
			'madapter_display_name' => 'ngnix监控适配器',
			'store_type'    => 2,
			'madapter_type' => 2,
		);

		$property_basic = sw_member::property_factory('madapter_basic', $data);
		$condition = sw_member::condition_factory('mod_madapter_basic', array('madapter_id' => 2));
		$condition->set_property($property_basic);
		$condition->set_in('madapter_id');
		$madapter = sw_member::operator_factory('madapter');
		$madapter->get_operator('basic')->mod_basic($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_basic', 'select * from madapter_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('madapter_basic');
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
		$condition = sw_member::condition_factory('del_madapter_basic', array('madapter_id' => 2));
		$condition->set_in('madapter_id');
		$madapter = sw_member::operator_factory('madapter');
		$madapter->get_operator('basic')->del_basic($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_basic', 'select * from madapter_basic');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('madapter_basic');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
