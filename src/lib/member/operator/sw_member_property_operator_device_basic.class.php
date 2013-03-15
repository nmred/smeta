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
* device_basic 操作对象 
+------------------------------------------------------------------------------
* 
* @uses sw_operator_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_member_property_operator_device_basic extends sw_member_property_operator_device_abstract
{
	// {{{ functions
	// {{{ public function add_basic()

	/**
	 * 添加设备基本信息 
	 * 
	 * @param sw_member_property_device_basic $property 
	 * @access public
	 * @return void
	 */
	public function add_basic(sw_member_property_device_basic $basic_property)
	{
		$device_key_property = $this->_get_device_operator()->get_device_key_property();
		$key_attributes = $property->attributes();

		if (!isset($key_attributes['device_id'])) {
			require_once PATH_SWAN_LIB . 'member/operator/sw_member_property_operator_device_exception.class.php';
			throw new sw_member_property_operator_device_exception('Unknow device id.');	
		}

		// 判断是否已经存在
		if ($this->exists($attributes['device_id'])) {
			require_once PATH_SWAN_LIB . 'member/operator/sw_member_property_operator_device_exception.class.php';
			throw new sw_member_property_operator_device_exception('device already exists');	
		}
		
		$attributes = $basic_property->attributes();
		$attributes['device_id'] = $key_attributes['device_id'];
		$require_fields = array('device_id', 'device_display_name', 'host');
		$this->_check_require($attributes, $require_fields);

		$this->__db->insert(SWAN_TBN_DEVICE_BASIC, $attributes);
	}

	// }}}
	// {{{ public function get_key()

	/**
	 * 获取所有的设备信息 
	 * 
	 * @param sw_condition_adapter_member_operator_device_basic_get_basic $condition 
	 * @access public
	 * @return array
	 */
	public function get_basic(sw_condition_adapter_member_operator_device_basic_get_basic $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
						     ->from(SWAN_TBN_DEVICE_BASIC, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());
	}

	// }}}
	// {{{ public function exists()

	/**
	 * 查看是否存在该设备的基本信息 
	 * 
	 * @param int $device_id 
	 * @access public
	 * @return boolean
	 */
	public function exists($device_id)
	{
		$select = $this->__db->select()
						     ->from(SWAN_TBN_DEVICE_BASIC, 'count(*)')
							 ->where('device_id=?')
							 ->where('is_delete=0');
		return $this->__db->fetch_one($select, $device_id) > 0;
	}

	// }}}
	// {{{ protected function _validate()

	/**
	 * 检查属性的正确性 
	 * 
	 * @param mixed $attributes 
	 * @access protected
	 * @return void
	 */
	protected function _validate($attributes)
	{
		$parrent = '/^[a-zA-Z]+[0-9a-zA-Z_]{5,}$/is';
		if (!preg_match($parrent, $attributes['device_name'])) {
			require_once PATH_SWAN_LIB . 'operator/sw_operator_exception.class.php';
			throw new sw_operator_exception("设备名的格式必须是首个字符是字母，由数字、字母、下划线组成,并且至少6位");	
		}

		$is_exists = $this->__db->fetch_one($this->__db->select()
													   ->from(SWAN_TBN_DEVICE_KEY, array('device_id'))
													   ->where('device_name= ?'), $attributes['device_name']);

		if ($is_exists) {
			require_once PATH_SWAN_LIB . 'operator/sw_operator_exception.class.php';
			throw new sw_operator_exception($attributes['device_name'] . "设备名已经存在");
		}
	}

	// }}}
	// {{{ public function add_device_handler()

	/**
	 * 添加设备 
	 * 
	 * @param sw_member_property_abstract $property 
	 * @access public
	 * @return void
	 */
	public function add_device_handler($property)
	{
		$this->add_key($property);
	}

	// }}}
	// {{{ public function del_device_handler()

	/**
	 * 删除设备 
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
	 * 修改设备 
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
	 * 清除设备 
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
	 * 恢复设备 
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
