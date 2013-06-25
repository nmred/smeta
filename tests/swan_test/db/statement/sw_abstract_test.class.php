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
 
namespace swan_test\db\statement;
use lib\test\sw_test_db;
use lib\db\statement\sw_standard;
use lib\db\statement\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_abatract_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_abstract_test extends sw_test_db
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function test_construct()

	/**
	 * test_construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_construct()
	{
		try {
			$stmt = new sw_standard($this->__db, 'select * from unit_host where host_id > ?;');
			$id = 2;
			$stmt->bind_param(1, $id);
			var_dump($stmt->execute());
			$a = $stmt->fetch_all();
			var_dump($a);
		} catch (sw_exception $e) {
		//	$this->assertContains('param error', $e->getMessage());	
		}

		$this->assertEquals(2, $this->getConnection()->getRowCount('unit_host'), "Pre-Condition");

		$this->assertEquals(2, $this->getConnection()->getRowCount('unit_host'), "Inserting failed");
	}

	// }}}
	// {{{ public function get_data_set()

	/**
	 * 获取数据集 
	 * 
	 * @access public
	 * @return mixed
	 */
	public function get_data_set()
	{
		return dirname(__FILE__) . '/_files/unit_host.xml';
	}

	// }}}
	// }}}
}
