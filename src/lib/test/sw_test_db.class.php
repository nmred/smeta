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

namespace lib\test;
use lib\db\sw_db;
use lib\test\exception\sw_exception;
use PDO;

/**
+------------------------------------------------------------------------------
* sw_db 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_test_db extends \PHPUnit_Extensions_Database_TestCase
{
	// {{{ members

	/**
	 * 数据库连接 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__db = null;

	// }}}
	// {{{ functions
	// {{{ protected function setUp()

	/**
	 * setUp 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function setUp()
	{	
		$this->__db = sw_db::singleton();
		parent::setUp();
	}

	// }}}
	// {{{ protected function getConnection()

	/**
	 * 获取 
	 * 
	 * @access protecetd
	 * @return \PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	protected function getConnection()
	{
		$conn = $this->__db->get_connection();
		return $this->createDefaultDBConnection($conn);				
	}

	// }}}
	// {{{ protected function getDataSet()
	
	/**
	 * 设置测试的数据集 
	 * 
	 * @access protected
	 * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet()
	{
		$xml_data = $this->get_data_set();
		if (!isset($xml_data)) {
			throw new sw_exception("must defined data xml.");
		}
		
		if (is_array($xml_data)) {
			$composite_ds = new \PHPUnit_Extensions_Database_DataSet_CompositeDataSet(array());	
			foreach ($xml_data as $file_path) {
				$ds = $this->createXMLDataSet($file_path);
				$composite_ds->addDataSet($ds);
			}

			return $composite_ds;
		} else {
			return $this->createXMLDataSet($xml_data);	
		}
	}

	// }}}
	// {{{ abstract public function get_data_set()

	/**
	 * 获取数据集文件 
	 * 
	 * @access public
	 * @return mixed
	 */
	abstract public function get_data_set();

	// }}}
	// }}}
}
