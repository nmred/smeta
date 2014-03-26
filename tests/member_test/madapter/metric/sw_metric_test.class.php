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
 
namespace member_test\madapter\metric;
use swan\test\sw_test_db;
use \lib\member\sw_member;

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
class sw_metric_test extends sw_test_db
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
	// {{{ public function test_add_metric()

	/**
	 * test_add_metric 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_add_metric()
	{
		$data = array(
			'metric_name' => 'mysql_select',
			'collect_every' => 60,
			'time_threshold' => 2000,
			'tmax' => 600,
			'dst_type' => 1,
			'vmin'  => 'U',
			'vmax'  => 'U',
			'unit'  => 'N',
			'title' => 'MySQL - Select 查询数',
		);

		$basic_property = sw_member::property_factory('madapter_basic', array('madapter_id' => 2));
		$madapter = sw_member::operator_factory('madapter', $basic_property);
		$metric_property = sw_member::property_factory('madapter_metric', $data);
		$metric_id = $madapter->get_operator('metric')->add_metric($metric_property);
		
		$this->assertEquals(1, $metric_id);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_metric', 'select * from madapter_metric');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('madapter_metric');
		$this->assertTablesEqual($expect, $query_table);
		$query_seq = $this->getConnection()
			              ->CreateQueryTable('sequence_madapter', 'select * from sequence_madapter');
		$expect_seq = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			               ->getTable('sequence_madapter');
		$this->assertTablesEqual($expect_seq, $query_seq);
	}

	// }}}
	// {{{ public function test_get_metric()

	/**
	 * test_get_metric 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_metric()
	{
		$condition = sw_member::condition_factory('get_madapter_metric');
		$madapter  = sw_member::operator_factory('madapter');
		$madapter_metric = $madapter->get_operator('metric')->get_metric($condition);
		$query_table  = $this->array_to_dbset(array('madapter_metric' => $madapter_metric))
							 ->getTable('madapter_metric');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('madapter_metric');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_mod_metric()

	/**
	 * test_mod_metric 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_mod_metric()
	{
		$data = array(
			'time_threshold' => 1000,
			'tmax' => 300,
			'vmax'  => '300',
		);

		$property_metric = sw_member::property_factory('madapter_metric', $data);
		$condition = sw_member::condition_factory('mod_madapter_metric', array('metric_id' => 1, 'madapter_id' => 1));
		$condition->set_property($property_metric);
		$condition->set_in('madapter_id');
		$condition->set_in('metric_id');
		$madapter = sw_member::operator_factory('madapter');
		$madapter->get_operator('metric')->mod_metric($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_metric', 'select * from madapter_metric');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('madapter_metric');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_del_metric()

	/**
	 * test_del_metric 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_del_metric()
	{
		$condition = sw_member::condition_factory('del_madapter_metric', array('madapter_id' => 1, 'metric_id' => 1));
		$condition->set_in('madapter_id');
		$condition->set_in('metric_id');
		$madapter = sw_member::operator_factory('madapter');
		$madapter->get_operator('metric')->del_metric($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_metric', 'select * from madapter_metric');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('madapter_metric');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
