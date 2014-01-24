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
		$property_key = $this->get_device_operator()->get_device_key_property();
		$key_attributes = $property_key->attributes();

        if (!isset($key_attributes['device_id'])) {
            throw new sw_exception('Unknow device id.');
        }

        // 判断是否已经存在
        if ($this->exists($key_attributes['device_id'])) {
            throw new sw_exception('device already exists');
        }
        
        $attributes = $basic_property->attributes();
        $attributes['device_id'] = $key_attributes['device_id'];
        $require_fields = array('device_id', 'device_display_name');
        $this->_check_require($attributes, $require_fields);

        $this->__db->insert(SWAN_TBN_DEVICE_BASIC, $attributes);
		return $key_attributes['device_id'];
	}
	
	// }}}
	// {{{ public function get_basic()

	/**
	 * get_basic 
	 * 
	 * @param \lib\member\condition\sw_get_device_basic $condition 
	 * @access public
	 * @return void
	 */
	public function get_basic(\lib\member\condition\sw_get_device_basic $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
							 ->from(SWAN_TBN_DEVICE_BASIC);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}

	// }}}
	// {{{ public function mod_basic()

	/**
	 * mod_basic 
	 * 
	 * @param \lib\member\condition\sw_mod_device_basic $condition 
	 * @access public
	 * @return void
	 */
	public function mod_basic(\lib\member\condition\sw_mod_device_basic $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$attributes = $condition->get_property()->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return; 
		}

		$this->__db->update(SWAN_TBN_DEVICE_BASIC, $attributes, $where);
	}

	// }}}
	// {{{ public function del_basic()

	/**
	 * 删除 device 设备信息 
	 * 
	 * @param \lib\member\condition\sw_del_device_basic $condition 
	 * @access public
	 * @return void
	 */
	public function del_basic(\lib\member\condition\sw_del_device_basic $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return; 
		}

		$this->__db->delete(SWAN_TBN_DEVICE_BASIC, $where);
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
                             ->where('device_id=?');
        return $this->__db->fetch_one($select, $device_id) > 0;
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
