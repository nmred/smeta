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
 
namespace member_test\device\key;
use swan\test\sw_test_db;
use \lib\member\sw_member;

/**
+------------------------------------------------------------------------------
* 设备 key 测试 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_key_test extends sw_test_db
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
	// {{{ public function test_add_key()

	/**
	 * test_add_key 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_add_key()
	{
		$data = array(
			'device_name' => 'lan-116',
		);
		$property_key = sw_member::property_factory('device_key', $data);
		$device = sw_member::operator_factory('device', $property_key);
		$device_key = $device->get_operator('key')->add_key();

		$query_device_key_table = $this->getConnection()
						               ->CreateQueryTable('device_key', 'select * from device_key');
		$query_seq_g_table = $this->getConnection()
						          ->CreateQueryTable('sequence_global', 'select * from sequence_global where table_name="device_key"');
		$expect_device_key = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
					              ->getTable('device_key');
		$expect_seq_g = $this->createXMLDataSet(dirname(__FILE__) . '/_files/add_result.xml')
					         ->getTable('sequence_global');
		$this->assertTablesEqual($expect_device_key, $query_device_key_table);
		$this->assertTablesEqual($expect_seq_g, $query_seq_g_table);
	}

	// }}}
	// {{{ public function test_get_key()

	/**
	 * test_get_key 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_key()
	{
		$condition  = sw_member::condition_factory('get_device_key');
		$device     = sw_member::operator_factory('device');
		$device_key = $device->get_operator('key')->get_key($condition);

		$query_table = $this->array_to_dbset(array('device_key' => $device_key))
							->getTable('device_key');

		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/get_result.xml')
			           ->getTable('device_key');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_del_key()

	/**
	 * test_del_key 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_del_key()
	{
		$condition = sw_member::condition_factory('del_device_key', array('device_id' => 1));
		$condition->set_in('device_id');
		$device = sw_member::operator_factory('device');
		$device_key = $device->get_operator('key')->del_key($condition);
		$query_table = $this->getConnection()
			                ->CreateQueryTable('device_key', 'select * from device_key');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/del_result.xml')
			           ->getTable('device_key');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// }}}
}
