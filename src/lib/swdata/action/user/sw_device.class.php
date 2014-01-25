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

namespace lib\swdata\action\user;
use \lib\swdata\action\sw_abstract;
use lib\swdata\action\user\exception\sw_exception;
use \lib\member\sw_member;

/**
+------------------------------------------------------------------------------
* 设备接口 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_device extends sw_abstract
{
	// {{{ functions
	// {{{ public function action_add()

	/**
	 * 添加设备 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_add()
	{
		$device_name = $this->__request->get_post('name', '');
		$device_display_name = $this->__request->get_post('display_name', '');
		if (!$device_name) {
			return $this->render_json(null, 10001, '`name` not allow is empty.');
		}

		$db = \swan\db\sw_db::singleton();
		$db->begin_transaction();
		// 添加 key
		try {
			$property_key = sw_member::property_factory('device_key', array('device_name' => $device_name)); 
			$device    = sw_member::operator_factory('device', $property_key);
			$device_id = $device->get_operator('key')->add_key();
		} catch (\swan\exception\sw_exception $e) {
			$db->rollback();
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		// 添加 device basic
		try {
			$property_key   = sw_member::property_factory('device_key', array('device_id' => $device_id)); 
			$property_basic = sw_member::property_factory('device_basic', array('device_display_name' => $device_display_name)); 
			$device = sw_member::operator_factory('device', $property_key);
			$device->get_operator('basic')->add_basic($property_basic);
		} catch (\swan\exception\sw_exception $e) {
			$db->rollback();
			return $this->render_json(null, 10003, $e->getMessage());	
		}

		$db->commit();
		return $this->render_json(array('device_id' => $device_id), 10000);
	}

	// }}}
	// {{{ public function action_del()
	
	/**
	 * 删除设备 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_del()
	{
		return $this->render_json(array('swan server!'), 10000);
	}

	// }}}
	// {{{ public function action_json()
	
	/**
	 * 获取设备列表 
	 * 
	 * @access public
	 * @return string
	 */
	public function action_json()
	{			
	}

	// }}}
}
