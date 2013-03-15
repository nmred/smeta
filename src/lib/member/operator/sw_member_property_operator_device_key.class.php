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
 
require_once PATH_SWAN_LIB . 'member/operator/sw_member_property_operator_device_abstract.class.php';

/**
+------------------------------------------------------------------------------
* device_key 操作对象 
+------------------------------------------------------------------------------
* 
* @uses sw_operator_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_member_property_operator_device_key extends sw_member_property_operator_device_abstract
{
	// {{{ functions
	// {{{ public function process_key()

	/**
	 * 对device_key进行处理，同时返回此操作接口 
	 * 
	 * @param int $device_id 默认自动生成 
	 * @access public
	 * @return sw_member_property_operator_device_key
	 */
	public function process_key()
	{
		try {
			$device_key_property = $this->get_device_operator()->get_device_key_property();
		} catch (sw_member_operator_exception $e) {
			// TODO	
		}

		$attributes = $device_key_property->attributes();
		if (!isset($attributes['device_id']) && !isset($attributes['device_name'])) {
			require_once PATH_SWAN_LIB . 'member/operator/sw_condition_adapter_member_operator_device_exception.class.php';
			throw new sw_condition_adapter_member_operator_device_exception('Undefined device_id and device_name.');	
		}
		
		if (isset($attributes['device_name'])) {
			$device_key = $this->get_key_by_name($attributes['device_name']);	
		} else {
			$device_key = $this->get_device_id($attributes['device_id']);	
		}
		
		// 设置 key 属性
		$device_key_property->set_device_id($device_key['device_id']);
		$device_key_property->set_device_name($device_key['device_name']);
	}

	// }}}
	// {{{ public function add_key()

	/**
	 * 添加设备关键信息 
	 * 
	 * @param int $device_id 默认自动生成 
	 * @access public
	 * @return void
	 */
	public function add_key($device_id = null)
	{
		$device_key_property = $this->get_device_operator()->get_device_key_property();
		$device_name = $device_key_property->get_device_name();
		$this->_validate($device_name);

		// 自动生成 ID
		if (!isset($device_id)) {
			$device_id = sw_sequence::get_next_global(SWAN_TBN_SEQUENCE_GLOBAL);
		}
		
		// 设置 key 属性
		$device_key_property->set_device_id($device_id);
		$device_key_property->set_device_name($device_name);

		$attributes = $device_key_property->attributes();
		$require_fields = array('device_name', 'device_id');
		$this->_check_require($attributes, $require_fields);

		$this->__db->insert(SWAN_TBN_DEVICE_KEY, $attributes);
	}

	// }}}
	// {{{ public function get_key()

	/**
	 * 获取所有的设备信息 
	 * 
	 * @param  $condition 
	 * @access public
	 * @return array
	 */
	public function get_key(sw_condition_adapter_member_operator_device_key_get_key $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
						     ->from(SWAN_TBN_DEVICE_KEY, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());
	}

	// }}}
	// {{{ public function get_key_by_id()

	/**
	 * 获取所有的设备关键信息通过 id
	 * 
	 * @param int $device_id
	 * @access public
	 * @return array
	 */
	public function get_key_by_id($device_id)
	{
		$select = $this->__db->select()
						     ->from(SWAN_TBN_DEVICE_KEY, null)
							 ->where('device_id=?');
		return $this->__db->fetch_row($select, $device_id);
	}

	// }}}
	// {{{ public function get_key_by_name()

	/**
	 * 获取所有的设备关键信息通过 name
	 * 
	 * @param int $device_name
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
	// {{{ protected function _validate()

	/**
	 * 检查属性的正确性 
	 * 
	 * @param string $device_name 
	 * @access protected
	 * @return boolean
	 */
	protected function _validate($device_name)
	{
		$parrent = '/^[a-zA-Z]+[0-9a-zA-Z_]{5,}$/is';
		if (!preg_match($parrent, $device_name)) {
			require_once PATH_SWAN_LIB . 'operator/sw_operator_exception.class.php';
			throw new sw_operator_exception("设备名的格式必须是首个字符是字母，由数字、字母、下划线组成,并且至少6位");	
		}

		$is_exists = $this->__db->fetch_one($this->__db->select()
													   ->from(SWAN_TBN_DEVICE_KEY, array('device_id'))
													   ->where('device_name= ?'), $device_name);

		if ($is_exists) {
			require_once PATH_SWAN_LIB . 'operator/sw_operator_exception.class.php';
			throw new sw_operator_exception("`$device_name` device name already exists.");
		}
	}

	// }}}
	// {{{ public function add_device_handler()

	/**
	 * 添加设备的时候运行的处理器 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_device_handler($property = null)
	{
		$this->add_key();
	}

	// }}}
	// {{{ public function del_device_handler()

	/**
	 * 删除设备的时候运行的处理器 
	 * 
	 * @param sw_member_property_abstract $property 
	 * @access public
	 * @return void
	 */
	public function del_device_handler($property)
	{
		return;
	}

	// }}}
	// {{{ public function mod_device_handler()

	/**
	 * 修改设备的时候运行的处理器 
	 * 
	 * @param sw_member_property_abstract $property 
	 * @access public
	 * @return void
	 */
	public function mod_device_handler($property)
	{
		return ;
	}

	// }}}
	// {{{ public function clear_device_handler()

	/**
	 * 清除设备的时候运行的处理器 
	 * 
	 * @param sw_member_property_abstract $property 
	 * @access public
	 * @return void
	 */
	public function clear_device_handler($property)
	{
		return;
	}

	// }}}
	// {{{ public function recover_device_handler()

	/**
	 * 恢复设备的时候运行的处理器 
	 * 
	 * @param sw_member_property_abstract $property 
	 * @access public
	 * @return void
	 */
	public function recover_device_handler($property)
	{
		return;
	}

	// }}}
	// }}}	
} 
