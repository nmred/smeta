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
 
namespace member_test\madapter\archive;
use swan\test\sw_test_db;
use \lib\member\sw_member;

/**
+------------------------------------------------------------------------------
* 监控适配器 archive 测试 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_archive_test extends sw_test_db
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
	// {{{ public function test_add_archive()

	/**
	 * test_add_archive 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_add_archive()
	{
		$data = array(
			'title' => '2week',
			'cf_type' => 1,
			'xff'   => 0.5,
			'steps' => 6,
			'rows'  => 700,
		);

		$basic_property = sw_member::property_factory('madapter_basic', array('madapter_id' => 2));
		$madapter = sw_member::operator_factory('madapter', $basic_property);
		$archive_property = sw_member::property_factory('madapter_archive', $data);
		$archive_id = $madapter->get_operator('archive')->add_archive($archive_property);
		
		$this->assertEquals(1, $archive_id);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_archive', 'select * from madapter_archive');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('madapter_archive');
		$this->assertTablesEqual($expect, $query_table);
		$query_seq = $this->getConnection()
			              ->CreateQueryTable('sequence_madapter', 'select * from sequence_madapter');
		$expect_seq = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			               ->getTable('sequence_madapter');
		$this->assertTablesEqual($expect_seq, $query_seq);
	}

	// }}}
	// {{{ public function test_get_archive()

	/**
	 * test_get_archive 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_archive()
	{
		$condition = sw_member::condition_factory('get_madapter_archive');
		$madapter  = sw_member::operator_factory('madapter');
		$madapter_archive = $madapter->get_operator('archive')->get_archive($condition);
		$query_table  = $this->array_to_dbset(array('madapter_archive' => $madapter_archive))
							 ->getTable('madapter_archive');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('madapter_archive');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_mod_archive()

	/**
	 * test_mod_archive 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_mod_archive()
	{
		$data = array(
			'title' => '2month',
			'steps' => 24,
			'rows'  => 775,
		);

		$property_archive = sw_member::property_factory('madapter_archive', $data);
		$condition = sw_member::condition_factory('mod_madapter_archive', array('archive_id' => 2, 'madapter_id' => 1));
		$condition->set_property($property_archive);
		$condition->set_in('madapter_id');
		$condition->set_in('archive_id');
		$madapter = sw_member::operator_factory('madapter');
		$madapter->get_operator('archive')->mod_archive($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_archive', 'select * from madapter_archive');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('madapter_archive');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_del_archive()

	/**
	 * test_del_archive 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_del_archive()
	{
		$condition = sw_member::condition_factory('del_madapter_archive', array('madapter_id' => 1, 'archive_id' => 2));
		$condition->set_in('madapter_id');
		$condition->set_in('archive_id');
		$madapter = sw_member::operator_factory('madapter');
		$madapter->get_operator('archive')->del_archive($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_archive', 'select * from madapter_archive');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('madapter_archive');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
