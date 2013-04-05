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
 
namespace swan_test\config;
use \lib\config;

/**
+------------------------------------------------------------------------------
* sw_config_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
+------------------------------------------------------------------------------
* @group sw_config
*/
class sw_config_test extends \PHPUnit_FrameWork_TestCase
{
	/**
	 * test_success 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_config()
	{
		$config = new \lib\config\sw_config();
		$result = $config->get_config('db');
		$this->assertNotEmpty($result);
	}	
}
