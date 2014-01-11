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

namespace lib\member\operater\device;
use \lib\member\operater\device\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 设备 basic 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_basic extends sw_abstract
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function add_basic()

	/**
	 * 添加设备 basic 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_basic(\lib\member\property\sw_device_basic $basic_property)
	{
		$property = $this->get_device_operater()->get_device_key_property();
		$key_attribute = $property->attributes();

		if (!isset($key_attribute['device_id'])) 

		self::$__db->insert(SWAN_TBN_DEVICE_KEY, $attributes);
	}
	
	// }}}
	// {{{ protected function _validate()

	/**
	 * _validate 
	 * 
	 * @param mixed $device_name 
	 * @access protected
	 * @return void
	 */
	protected function _validate($device_name)
	{
		return true;
		$parrent = '/^[a-zA-Z]+[0-9a-zA-Z_]{5,}$/is';
		if (!preg_match($parrent, $device_name)) {
			throw new sw_exception("设备名的格式必须是首个字符是字母，由数字、字母、下划线组成,并且至少6位");  
		}

		$is_exists = self::$__db->fetch_one(self::$__db->select()
								->from(SWAN_TBN_DEVICE_KEY, array('device_id'))
								->where('device_name= ?'), $device_name);

		if ($is_exists) {
			throw new sw_exception("`$device_name` device name already exists.");
		}
	}

	// }}}
	// {{{ public function add_device_handler()

	/**
	 * 添加设备的处理器 
	 * 
	 * @param \lib\member\property\sw_device_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function add_device_handler($property = null)
	{

	}

	// }}}
	// {{{ public function mod_device_handler()

	/**
	 * 修改设备的处理器 
	 * 
	 * @param \lib\member\property\sw_device_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function mod_device_handler($property = null)
	{
		
	}

	// }}}
	// {{{ public function del_device_handler()

	/**
	 * 删除设备的处理器 
	 * 
	 * @param \lib\member\property\sw_device_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function del_device_handler($property = null)
	{
		
	}

	// }}}
	// }}}
}
