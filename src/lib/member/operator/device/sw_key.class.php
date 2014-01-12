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
		$property->set_device_name($device_name);
		$attributes = $property->attributes();
		$require_fields = array('device_name', 'device_id');

		$this->_check_require($attributes, $require_fields);

		$this->__db->insert(SWAN_TBN_DEVICE_KEY, $attributes);

		return $device_id;
	}
	
	// }}}
	// {{{ public function get_key()

	/**
	 * get_key 
	 * 
	 * @param \lib\member\condition\sw_get_device_key $condition 
	 * @access public
	 * @return void
	 */
	public function get_key(\lib\member\condition\sw_get_device_key $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
							 ->from(SWAN_TBN_DEVICE_KEY);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}

	// }}}
	// {{{ public function del_key()

	/**
	 * 删除 device 设备 KEY 
	 * 
	 * @param \lib\member\condition\sw_del_device_key $condition 
	 * @access public
	 * @return void
	 */
	public function del_key(\lib\member\condition\sw_del_device_key $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return; 
		}

		$this->__db->delete(SWAN_TBN_DEVICE_KEY, $where);
	}

	// }}}
	// {{{ public function process_key()

	/**
	 * 补全设备key 
	 * 
	 * @access public
	 * @return void
	 */
	public function process_key()
	{
		$device_key_property = $this->get_device_operator()->get_device_key_property();

		$attributes = $device_key_property->attributes();
		if (!isset($attributes['device_id']) && !isset($attributes['device_name'])) {
			throw new sw_exception('Undefined device_id and device_name.');
		}

		if (isset($attributes['device_name'])) {
			$device_key = $this->get_key_by_name($attributes['device_name']);
		} else {
			$device_key = $this->get_key_by_id($attributes['device_id']);
		}

		// 设置 key 属性
		$device_key_property->set_device_id($device_key['device_id']);
		$device_key_property->set_device_name($device_key['device_name']);		
	}

	// }}}
	// {{{ public function get_key_by_name()

	/**
	 * 通过设备名称获取设备 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_key_by_name($device_name)
	{
		$select = $this->__db->select()
			->from(SWAN_TBN_DEVICE_KEY, null)
			->where('device_name=?');
		return $this->__db->fetch_row($select, $device_name);
	}

	// }}}
	// {{{ public function get_key_by_id()

	/**
	 * 通过设备 ID 获取设备 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_key_by_id($device_id)
	{
		$select = $this->__db->select()
			->from(SWAN_TBN_DEVICE_KEY)
			->where('device_id=?');
		return $this->__db->fetch_row($select, $device_id);
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
		$parrent = '/^[a-zA-Z]+[0-9a-zA-Z_]{5,}$/is';
		if (!preg_match($parrent, $device_name)) {
			throw new sw_exception("设备名的格式必须是首个字符是字母，由数字、字母、下划线组成,并且至少6位");  
		}

		$is_exists = $this->__db->fetch_one($this->__db->select()
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
