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

namespace lib\member\operator\alert;
use \lib\member\operator\alert\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 告警 basic 
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
	 * 添加告警 basic 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_basic($alert_id = null)
	{
		$property_basic = $this->get_alert_operator()->get_alert_basic_property();
		$alert_name = $property_basic->get_alert_name();
		$this->_validate($alert_name);

		if (!isset($alert_id)) {
			$alert_id = \lib\sequence\sw_sequence::get_next_global(SWAN_TBN_alert_BASIC);	
		}

		$property_basic->set_alert_id($alert_id);
		$property_basic->set_alert_name($alert_name);
		$attributes = $property_basic->attributes();
		$require_fields = array('alert_id', 'alert_name');

		$this->_check_require($attributes, $require_fields);

        $this->__db->insert(SWAN_TBN_alert_BASIC, $attributes);

		return $alert_id;
	}
	
	// }}}
	// {{{ public function get_basic()

	/**
	 * 获取告警 basic 信息 
	 * 
	 * @param \lib\member\condition\get\sw_alert_basic $condition 
	 * @access public
	 * @return void
	 */
	public function get_basic(\lib\member\condition\get\sw_alert_basic $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
							 ->from(SWAN_TBN_alert_BASIC, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}
	
	// }}}
	// {{{ public function get_info()

	/**
	 * 获取告警的详细信息 
	 * 
	 * @param int $alert_id 
	 * @access public
	 * @return array
	 */
	public function get_info($alert_id)
	{
		$bind_data = array($alert_id);
		$select = $this->__db->select()
							 ->from(SWAN_TBN_alert_BASIC, '*')
							 ->where(' alert_id = ?');

		$result = $this->__db->fetch_all($select, $bind_data);
		if (!isset($result[0])) {
			throw new sw_exception('get alert basic info fail.');
		}

		return $result[0];
	}

	// }}}
	// {{{ public function mod_basic()

	/**
	 * mod_basic 
	 * 
	 * @param \lib\member\condition\mod\sw_alert_basic $condition 
	 * @access public
	 * @return void
	 */
	public function mod_basic(\lib\member\condition\mod\sw_alert_basic $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$attributes = $condition->get_property()->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return; 
		}

		$this->__db->update(SWAN_TBN_alert_BASIC, $attributes, $where);
	}

	// }}}
	// {{{ public function del_basic()

	/**
	 * 删除 alert 设备信息 
	 * 
	 * @param \lib\member\condition\del\sw_alert_basic $condition 
	 * @access public
	 * @return void
	 */
	public function del_basic(\lib\member\condition\del\sw_alert_basic $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return; 
		}

		$this->__db->delete(SWAN_TBN_alert_BASIC, $where);
	}

	// }}}
	// {{{ public function add_alert_handler()

	/**
	 * 添加告警的处理器 
	 * 
	 * @param \lib\member\property\sw_alert_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function add_alert_handler($property = null)
	{

	}

	// }}}
	// {{{ public function mod_alert_handler()

	/**
	 * 修改告警的处理器 
	 * 
	 * @param \lib\member\property\sw_alert_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function mod_alert_handler($property = null)
	{
		
	}

	// }}}
	// {{{ public function del_alert_handler()

	/**
	 * 删除告警的处理器 
	 * 
	 * @param \lib\member\property\sw_alert_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function del_alert_handler($property = null)
	{
	}

	// }}}
	// {{{ protected function _validate()

	/**
	 * _validate 
	 * 
	 * @param mixed $alert_name 
	 * @access protected
	 * @return void
	 */
	protected function _validate($alert_name)
	{
		$parrent = '/^[a-zA-Z]+[0-9a-zA-Z_]{2,}$/is';
		if (!preg_match($parrent, $alert_name)) {
			throw new sw_exception("告警的格式必须是首个字符是字母，由数字、字母、下划线组成,并且至少3位");  
		}

		$is_exists = $this->__db->fetch_one($this->__db->select()
								->from(SWAN_TBN_alert_BASIC, array('alert_id'))
								->where('alert_name= ?'), $alert_name);

		if ($is_exists) {
			throw new sw_exception("`$alert_name` alert name already exists.");
		}
	}

	// }}}
	// }}}
}
