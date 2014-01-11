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

namespace lib\member\operator\device;
use \lib\member\operator\device\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 设备KEY 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_key extends sw_abstract
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function add_key()

	/**
	 * 添加设备 key 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_key($device_id = null)
	{
		$property = $this->get_device_operator()->get_device_key_property();
		$device_name = $property->get_device_name();
		$this->_validate($device_name);

		if (!isset($device_id)) {
			$device_id = \lib\sequence\sw_sequence::get_next_global(SWAN_TBN_DEVICE_KEY);	
		}

		$property->set_device_id($device_id);
		$attibutes = $property->attributes();
		$require_fields = array('device_name', 'device_id');

		$this->_check_require($attibutes, $require_fields);

		self::$__db->insert(SWAN_TBN_DEVICE_KEY, $attributes);

		return $device_id;
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
