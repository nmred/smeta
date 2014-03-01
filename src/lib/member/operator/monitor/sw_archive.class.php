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
* 监控器 archive 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_archive extends sw_abstract
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function add_archive()

	/**
	 * 添加监控器 archive 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_archive(\lib\member\property\sw_monitor_archive $property)
	{
		$property_basic = $this->get_monitor_operator()->get_monitor_basic_property();
		$attributes = $property_basic->attributes();
		if (!isset($attributes['monitor_id'])) {
			throw new sw_exception("unknow monitor id.");	
		}

		$monitor_id = $attributes['monitor_id'];
		$attributes = $property->attributes();
		$this->_validate($monitor_id);

		if (!isset($attributes['archive_id'])) {
			$archive_id = \lib\sequence\sw_sequence::get_next_monitor($monitor_id, SWAN_TBN_MONITOR_ARCHIVE);	
		} else {
			$archive_id = $attributes['archive_id'];		
		}

		$property->set_archive_id($archive_id);
		$property->set_monitor_id($monitor_id);
		$attributes = $property->attributes();
		$require_fields = array('archive_id', 'monitor_id', 'cf_type', 'xff', 'steps', 'rows', 'title');

		$this->_check_require($attributes, $require_fields);

        $this->__db->insert(SWAN_TBN_MONITOR_ARCHIVE, $attributes);

		return $archive_id;
	}
	
	// }}}
	// {{{ public function get_archive()

	/**
	 * 获取监控器 archive 
	 * 
	 * @param \lib\member\condition\sw_get_monitor_archive $condition 
	 * @access public
	 * @return void
	 */
	public function get_archive(\lib\member\condition\sw_get_monitor_archive $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
							 ->from(SWAN_TBN_MONITOR_ARCHIVE, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}

	// }}}
	// {{{ public function mod_archive()

	/**
	 * mod_archive 
	 * 
	 * @param \lib\member\condition\sw_mod_monitor_archive $condition 
	 * @access public
	 * @return void
	 */
	public function mod_archive(\lib\member\condition\sw_mod_monitor_archive $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$attributes = $condition->get_property()->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return; 
		}

		$this->__db->update(SWAN_TBN_MONITOR_ARCHIVE, $attributes, $where);
	}

	// }}}
	// {{{ public function del_archive()

	/**
	 * 删除 monitor archive 
	 * 
	 * @param \lib\member\condition\sw_del_monitor_archive $condition 
	 * @access public
	 * @return void
	 */
	public function del_archive(\lib\member\condition\sw_del_monitor_archive $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return; 
		}

		$this->__db->delete(SWAN_TBN_MONITOR_ARCHIVE, $where);
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
	 * @param int $monitor_id 
	 * @access protected
	 * @return void
	 */
	protected function _validate($monitor_id)
	{
		$is_exists = $this->__db->fetch_one($this->__db->select()
								->from(SWAN_TBN_MONITOR_BASIC, array('monitor_id'))
								->where('monitor_id= ?'), $monitor_id);

		if (!$is_exists) {
			throw new sw_exception("`$monitor_id` monitor id not  exists.");
		}
	}

	// }}}
	// }}}
}
