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
 
namespace member_test\madapter\attribute;
use swan\test\sw_test_db;
use \lib\member\sw_member;

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
class sw_attribute_test extends sw_test_db
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
	// {{{ public function test_add_attribute()

	/**
	 * test_add_attribute 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_add_attribute()
	{
		$data = array(
			'attr_name' => 'username',
			'attr_display_name' => '用户名',
			'form_type'    => 1,
			'attr_default' => 'root',
		);

		$basic_property = sw_member::property_factory('madapter_basic', array('madapter_id' => 2));
		$attribute_property = sw_member::property_factory('madapter_attribute', $data);
		$madapter = sw_member::operator_factory('madapter', $basic_property);
		$attr_id = $madapter->get_operator('attribute')->add_attribute($attribute_property);
		
		$this->assertEquals(1, $attr_id);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_attribute', 'select * from madapter_attribute');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			           ->getTable('madapter_attribute');
		$this->assertTablesEqual($expect, $query_table);
		$query_seq = $this->getConnection()
			              ->CreateQueryTable('sequence_madapter', 'select * from sequence_madapter');
		$expect_seq = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
			               ->getTable('sequence_madapter');
		$this->assertTablesEqual($expect_seq, $query_seq);
	}

	// }}}
	// {{{ public function test_get_attribute()

	/**
	 * test_get_attribute 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_attribute()
	{
		$condition = sw_member::condition_factory('get_madapter_attribute');
		$madapter  = sw_member::operator_factory('madapter');
		$madapter_attribute = $madapter->get_operator('attribute')->get_attribute($condition);
		$query_table  = $this->array_to_dbset(array('madapter_attribute' => $madapter_attribute))
							 ->getTable('madapter_attribute');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('madapter_attribute');
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
		    'attr_id' => '1',
		    'madapter_id' => '1',
		    'attr_name' => 'url',
		    'attr_display_name' => 'URL地址',
		    'form_type' => '1',
		    'form_data' => '',
		    'attr_default' => '',
		);
		$madapter = sw_member::operator_factory('madapter');
		$info = $madapter->get_operator('attribute')->get_info(1, 1);
		$this->assertEquals($data, $info);
	}

	// }}}
	// {{{ public function test_mod_attribute()

	/**
	 * test_mod_attribute 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_mod_attribute()
	{
		$data = array(
			'attr_name' => 'url_b',
			'attr_display_name' => 'URL B 地址',
		);

		$property_attribute = sw_member::property_factory('madapter_attribute', $data);
		$condition = sw_member::condition_factory('mod_madapter_attribute', array('attr_id' => 2, 'madapter_id' => 1));
		$condition->set_property($property_attribute);
		$condition->set_in('madapter_id');
		$condition->set_in('attr_id');
		$madapter = sw_member::operator_factory('madapter');
		$madapter->get_operator('attribute')->mod_attribute($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_attribute', 'select * from madapter_attribute');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/mod_result.xml')
			           ->getTable('madapter_attribute');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_del_attribute()

	/**
	 * test_del_attribute 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_del_attribute()
	{
		$condition = sw_member::condition_factory('del_madapter_attribute', array('madapter_id' => 1, 'attr_id' => 1));
		$condition->set_in('madapter_id');
		$condition->set_in('attr_id');
		$madapter = sw_member::operator_factory('madapter');
		$madapter->get_operator('attribute')->del_attribute($condition);

		$query_table = $this->getConnection()
			                ->CreateQueryTable('madapter_attribute', 'select * from madapter_attribute');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('madapter_attribute');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
