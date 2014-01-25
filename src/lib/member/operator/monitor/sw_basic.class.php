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

namespace lib\member\operator\monitor;
use \lib\member\operator\monitor\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 监控器 basic 
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
	 * 添加监控器 basic 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_basic($monitor_id = null)
	{
		$property_basic = $this->get_monitor_operator()->get_monitor_basic_property();
		$monitor_name = $property_basic->get_monitor_name();
		$this->_validate($monitor_name);

		if (!isset($monitor_id)) {
			$monitor_id = \lib\sequence\sw_sequence::get_next_global(SWAN_TBN_MONITOR_BASIC);	
		}

		$property_basic->set_monitor_id($monitor_id);
		$property_basic->set_monitor_name($monitor_name);
		$attributes = $property_basic->attributes();
		$require_fields = array('monitor_id', 'monitor_name');

		$this->_check_require($attributes, $require_fields);

        $this->__db->insert(SWAN_TBN_MONITOR_BASIC, $attributes);

		return $monitor_id;
	}
	
	// }}}
	// {{{ public function get_basic()

	/**
	 * get_basic 
	 * 
	 * @param \lib\member\condition\sw_get_monitor_basic $condition 
	 * @access public
	 * @return void
	 */
	public function get_basic(\lib\member\condition\sw_get_monitor_basic $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
							 ->from(SWAN_TBN_MONITOR_BASIC, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}

	// }}}
	// {{{ public function mod_basic()

	/**
	 * mod_basic 
	 * 
	 * @param \lib\member\condition\sw_mod_monitor_basic $condition 
	 * @access public
	 * @return void
	 */
	public function mod_basic(\lib\member\condition\sw_mod_monitor_basic $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$attributes = $condition->get_property()->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return; 
		}

		$this->__db->update(SWAN_TBN_MONITOR_BASIC, $attributes, $where);
	}

	// }}}
	// {{{ public function del_basic()

	/**
	 * 删除 monitor 设备信息 
	 * 
	 * @param \lib\member\condition\sw_del_monitor_basic $condition 
	 * @access public
	 * @return void
	 */
	public function del_basic(\lib\member\condition\sw_del_monitor_basic $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return; 
		}

		$this->__db->delete(SWAN_TBN_MONITOR_BASIC, $where);
	}

	// }}}
	// {{{ public function add_monitor_handler()

	/**
	 * 添加监控器的处理器 
	 * 
	 * @param \lib\member\property\sw_monitor_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function add_monitor_handler($property = null)
	{

	}

	// }}}
	// {{{ public function mod_monitor_handler()

	/**
	 * 修改监控器的处理器 
	 * 
	 * @param \lib\member\property\sw_monitor_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function mod_monitor_handler($property = null)
	{
		
	}

	// }}}
	// {{{ public function del_monitor_handler()

	/**
	 * 删除监控器的处理器 
	 * 
	 * @param \lib\member\property\sw_monitor_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function del_monitor_handler($property = null)
	{
		
	}

	// }}}
	// {{{ protected function _validate()

	/**
	 * _validate 
	 * 
	 * @param mixed $monitor_name 
	 * @access protected
	 * @return void
	 */
	protected function _validate($monitor_name)
	{
		$parrent = '/^[a-zA-Z]+[0-9a-zA-Z_]{5,}$/is';
		if (!preg_match($parrent, $monitor_name)) {
			throw new sw_exception("监控器的格式必须是首个字符是字母，由数字、字母、下划线组成,并且至少6位");  
		}

		$is_exists = $this->__db->fetch_one($this->__db->select()
								->from(SWAN_TBN_MONITOR_BASIC, array('monitor_id'))
								->where('monitor_name= ?'), $monitor_name);

		if ($is_exists) {
			throw new sw_exception("`$monitor_name` monitor name already exists.");
		}
	}

	// }}}
	// }}}
}
