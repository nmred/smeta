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
* 设备 监控器 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_monitor extends sw_abstract
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function add_monitor()

	/**
	 * 添加设备 监控器 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_monitor(\lib\member\property\sw_device_monitor $monitor_property)
	{
		$property_key = $this->get_device_operator()->get_device_key_property();
		$key_attributes = $property_key->attributes();

        if (!isset($key_attributes['device_id'])) {
            throw new sw_exception('Unknow device id.');
        }

		$monitor_basic_property		= $monitor_property->get_monitor_basic();
		$monitor_attribute_property = $monitor_property->get_monitor_attribute(); 
		$monitor_basic		= $monitor_basic_property->attributes();
		$monitor_attributes = $monitor_attribute_property->attributes();
        $attributes		    = $monitor_property->attributes();

        // 判断是否已经存在
		$this->exists($key_attributes['device_id'], $monitor_basic['monitor_id'], $monitor_attributes['attr_id']);

        $select = $this->__db->select()
                             ->from(SWAN_TBN_DEVICE_MONITOR, 'count(*)')
                             ->where('device_id=? AND monitor_id=? AND attr_id=?');
        if ($this->__db->fetch_one($select, array($key_attributes['device_id'], $monitor_basic['monitor_id'], $monitor_attributes['attr_id'])) > 0) {
			throw new sw_exception('already exists this item.');	
		}

		if (!isset($attributes['attr_id'])) {
			$value_id = \lib\sequence\sw_sequence::get_next_device($key_attributes['device_id'], SWAN_TBN_DEVICE_MONITOR);	
		} else {
			$value_id = $attributes['value_id'];		
		}
        
        $attributes['device_id']  = $key_attributes['device_id'];
        $attributes['monitor_id'] = $monitor_basic['monitor_id'];
        $attributes['attr_id']    = $monitor_attributes['attr_id'];
        $attributes['value_id']   = $value_id;
        $require_fields = array('value_id', 'device_id', 'monitor_id', 'attr_id', 'value');
        $this->_check_require($attributes, $require_fields);

        $this->__db->insert(SWAN_TBN_DEVICE_MONITOR, $attributes);
		return $value_id;
	}
	
	// }}}
	// {{{ public function get_monitor()

	/**
	 * get_monitor 
	 * 
	 * @param \lib\member\condition\sw_get_device_monitor $condition 
	 * @access public
	 * @return void
	 */
	public function get_monitor(\lib\member\condition\sw_get_device_monitor $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
							 ->from(SWAN_TBN_DEVICE_MONITOR);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}

	// }}}
	// {{{ public function mod_monitor()

	/**
	 * mod_monitor 
	 * 
	 * @param \lib\member\condition\sw_mod_device_monitor $condition 
	 * @access public
	 * @return void
	 */
	public function mod_monitor(\lib\member\condition\sw_mod_device_monitor $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$monitor_property = $condition->get_property();
		$attributes = $monitor_property->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return; 
		}

		$property_key = $this->get_device_operator()->get_device_key_property();
		$key_attributes = $property_key->attributes();

        if (!isset($key_attributes['device_id'])) {
            throw new sw_exception('Unknow device id.');
        }

		$monitor_basic_property		= $monitor_property->get_monitor_basic();
		$monitor_attribute_property = $monitor_property->get_monitor_attribute(); 
		$monitor_basic		= $monitor_basic_property->attributes();
		$monitor_attributes = $monitor_attribute_property->attributes();
        $attributes		    = $monitor_property->attributes();

        // 判断是否已经存在
		$this->exists($key_attributes['device_id'], $monitor_basic['monitor_id'], $monitor_attributes['attr_id']);

        $attributes['device_id']  = $key_attributes['device_id'];
        $attributes['monitor_id'] = $monitor_basic['monitor_id'];
        $attributes['attr_id']    = $monitor_attributes['attr_id'];

		$this->__db->update(SWAN_TBN_DEVICE_MONITOR, $attributes, $where);
	}

	// }}}
	// {{{ public function del_monitor()

	/**
	 * 删除 device 设备信息 
	 * 
	 * @param \lib\member\condition\sw_del_device_monitor $condition 
	 * @access public
	 * @return void
	 */
	public function del_monitor(\lib\member\condition\sw_del_device_monitor $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return; 
		}

		$this->__db->delete(SWAN_TBN_DEVICE_MONITOR, $where);
	}

	// }}}
    // {{{ public function exists()

    /**
     * 查看是否存在该设备的基本信息 
     * 
     * @param integer $device_id 
     * @param integer $monitor_id 
     * @param integer $attr_id 
     * @access public
     * @return void
     */
    public function exists($device_id, $monitor_id, $attr_id)
    {
        $select = $this->__db->select()
                             ->from(SWAN_TBN_DEVICE_KEY, 'count(*)')
                             ->where('device_id=?');
        if (false == $this->__db->fetch_one($select, $device_id) > 0) {
			throw new sw_exception('device id not exists.');	
		}

        $select = $this->__db->select()
                             ->from(SWAN_TBN_MONITOR_BASIC, 'count(*)')
                             ->where('monitor_id=?');
        if (false == $this->__db->fetch_one($select, $monitor_id) > 0) {
			throw new sw_exception('monitor id not exists.');	
		}

        $select = $this->__db->select()
                             ->from(SWAN_TBN_MONITOR_ATTRIBUTE, 'count(*)')
                             ->where('attr_id=?');
        if (false == $this->__db->fetch_one($select, $attr_id) > 0) {
			throw new sw_exception('attribute id not exists.');	
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
