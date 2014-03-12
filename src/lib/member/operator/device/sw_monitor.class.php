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
use \lib\member\property\sw_monitor_params as sw_monitor_params;

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

		$monitor_params = $monitor_property->get_monitor_params(); 
		$monitor_basic_property	= $monitor_property->get_monitor_basic();
		$monitor_basic = $monitor_basic_property->attributes();
        $attributes	   = $monitor_property->attributes();

		if (!isset($attributes['dm_name'])) {
			throw new sw_exception('must defined `dm_name`');	
		}
		 
        // 判断是否已经存在
		$this->exists($key_attributes['device_id'], $monitor_basic['monitor_id']);

        $select = $this->__db->select()
                             ->from(SWAN_TBN_DEVICE_MONITOR, 'count(*)')
                             ->where('device_id=? AND dm_name=? ');
        if ($this->__db->fetch_one($select, array($key_attributes['device_id'], $attributes['dm_name'])) > 0) {
			throw new sw_exception('already exists this item.');	
		}

		$dm_id = \lib\sequence\sw_sequence::get_next_device($key_attributes['device_id'], SWAN_TBN_DEVICE_MONITOR);	

		$this->__db->begin_transaction();

		// 设备 监控器 主表
        $attributes['dm_id']   = $dm_id;
        $attributes['device_id']  = $key_attributes['device_id'];
        $attributes['monitor_id'] = $monitor_basic['monitor_id'];
		unset($attributes['monitor_params']);
        $require_fields = array('dm_id', 'device_id', 'monitor_id', 'dm_name');
		try {
			$this->_check_require($attributes, $require_fields);
        	$this->__db->insert(SWAN_TBN_DEVICE_MONITOR, $attributes);
		} catch (\swan\exception\sw_exception $e) {
			$this->__db->rollback();
			throw new sw_exception($e);
		}
		if (!isset($monitor_params) || empty($monitor_params)) {
			$this->__db->commit();
			return $dm_id;	
		}
		
		// 监控器参数值设置
		foreach ($monitor_params as $param) {
			$param_attributes = $param->attributes();
			if (!isset($param_attributes['attr_id'])) {
				$this->__db->rollback();
				throw new sw_exception('must define attr_id for add param');
			}
			$select = $this->__db->select()
					       ->from(SWAN_TBN_MONITOR_ATTRIBUTE, 'count(*)')
					  	   ->where('attr_id=? AND monitor_id=? ');
			if ($this->__db->fetch_one($select, array($param_attributes['attr_id'], $monitor_basic['monitor_id'])) == 0) {
				$this->__db->rollback();
				throw new sw_exception('not exists this attribute.');	
			}

			$param_attributes['device_id']  = $key_attributes['device_id'];
			$param_attributes['dm_id'] = $dm_id;
			$require_fields = array('dm_id', 'device_id', 'attr_id', 'value');
			try {
				$this->_check_require($param_attributes, $require_fields);
				$this->__db->insert(SWAN_TBN_MONITOR_PARAM, $param_attributes);
			} catch (\swan\exception\sw_exception $e) {
				$this->__db->rollback();
				throw new sw_exception($e);
			}
		}

		$this->__db->commit();
		return $dm_id;
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
		$params = $condition->params();
		$device_monitor_columns   = $condition->columns(SWAN_TBN_DEVICE_MONITOR);
		$monitor_basic_columns = $condition->columns(SWAN_TBN_MONITOR_BASIC);
		if (empty($monitor_basic_columns)) { // 没有指定查询关联的字段
		    $basic_columns = null;  
		}
		$select = $this->__db->select()
		                     ->from(array('k' => SWAN_TBN_DEVICE_MONITOR), $device_monitor_columns)
		                     ->join(array('b' => SWAN_TBN_MONITOR_BASIC), "k.monitor_id = b.monitor_id", $monitor_basic_columns);
		$condition->set_columns(array());
		$condition->where($select, false);
		return $this->_get($select, $condition->params()); 
	}

	// }}}
	// {{{ public function get_monitor_params()

	/**
	 * get_monitor 
	 * 
	 * @param \lib\member\condition\sw_get_device_monitor_params $condition 
	 * @access public
	 * @return void
	 */
	public function get_monitor_params(\lib\member\condition\sw_get_device_monitor_params $condition)
	{
        $condition->check_params();
		$params = $condition->params();
		
		$mparams_columns = $condition->columns(SWAN_TBN_MONITOR_PARAM);
		$attr_monitor_columns   = $condition->columns(SWAN_TBN_MONITOR_ATTRIBUTE);
		$device_monitor_columns = $condition->columns(SWAN_TBN_DEVICE_MONITOR);	
		if (empty($mparams_columns)) { // 没有指定查询关联的字段
		    $mparams_columns = null;  
		}
		if (empty($attr_monitor_columns)) { // 没有指定查询关联的字段
		    $attr_monitor_columns = null;  
		}

		$select = $this->__db->select()
		                     ->from(array('d' => SWAN_TBN_DEVICE_MONITOR), $device_monitor_columns)
		                     ->join(array('p' => SWAN_TBN_MONITOR_PARAM), "p.dm_id = d.dm_id", $mparams_columns)
		                     ->join(array('a' => SWAN_TBN_MONITOR_ATTRIBUTE), "a.attr_id = p.attr_id AND a.monitor_id = d.monitor_id", $attr_monitor_columns);
		$condition->set_columns(array());
		$condition->where($select, false);
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
		$property_key = $this->get_device_operator()->get_device_key_property();
		$key_attributes = $property_key->attributes();
        if (!isset($key_attributes['device_id'])) {
            throw new sw_exception('Unknow device id.');
        }

		$monitor_property = $condition->get_property();
		$monitor_basic_property	= $monitor_property->get_monitor_basic();
		$monitor_id = $monitor_basic_property->get_monitor_id();
        if (!isset($monitor_id)) {
            throw new sw_exception('Unknow monitor id.');
        }
		$monitor_params = $monitor_property->get_monitor_params(); 
		$dm_id = $monitor_property->get_dm_id();

        $select = $this->__db->select()
                             ->from(SWAN_TBN_DEVICE_MONITOR, array('dm_id'))
                             ->where('device_id=? AND dm_id=? ');
		$dm_id = $this->__db->fetch_one($select, array($key_attributes['device_id'], $dm_id));
        if (!$dm_id) {
			throw new sw_exception('not exists this device monitor.');	
		}
		
		$this->__db->begin_transaction();

		// 监控器参数值设置
		$condition->set_in('dm_id');
		$condition->set_dm_id($dm_id);
		$condition->set_in('device_id');
		$condition->set_device_id($key_attributes['device_id']);
		$condition->check_params();
		$params = $condition->params();
		foreach ($monitor_params as $param) {
			$param_attributes = $param->attributes();
			$attributes = $param->prepared_attributes();
			if (isset($param_attributes['attr_id'])) {
				$condition->set_in('attr_id');
				$condition->set_attr_id($param_attributes['attr_id']);	
			}
			$where = $condition->where();
			try {
				$this->__db->update(SWAN_TBN_MONITOR_PARAM, $attributes, $where);
			} catch (\swan\exception\sw_exception $e) {
				$this->__db->rollback();
				throw new sw_exception($e);
			}
		}

		$this->__db->commit();
		return $dm_id;
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

		$this->__db->begin_transaction();
		try {
			$cols = $this->__db->delete(SWAN_TBN_DEVICE_MONITOR, $where);
		} catch (\swan\exception\sw_exception $e) {
			$this->__db->rollback();
			throw new sw_exception($e);
		}
		try {
			$this->__db->delete(SWAN_TBN_MONITOR_PARAM, $where);
		} catch (\swan\exception\sw_exception $e) {
			$this->__db->rollback();
			throw new sw_exception($e);
		}

		$this->__db->commit();
		return $cols;
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
    public function exists($device_id, $monitor_id)
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
