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

namespace lib\member\operator\madapter;
use \lib\member\operator\madapter\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 监控适配器 basic 
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
	 * 添加监控适配器 basic 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_basic($madapter_id = null)
	{
		$property_basic = $this->get_madapter_operator()->get_madapter_basic_property();
		$madapter_name = $property_basic->get_madapter_name();
		$this->_validate($madapter_name);

		if (!isset($madapter_id)) {
			$madapter_id = \lib\sequence\sw_sequence::get_next_global(SWAN_TBN_MADAPTER_BASIC);	
		}

		$property_basic->set_madapter_id($madapter_id);
		$property_basic->set_madapter_name($madapter_name);
		$attributes = $property_basic->attributes();
		$require_fields = array('madapter_id', 'madapter_name');

		$this->_check_require($attributes, $require_fields);

        $this->__db->insert(SWAN_TBN_MADAPTER_BASIC, $attributes);

		return $madapter_id;
	}
	
	// }}}
	// {{{ public function get_basic()

	/**
	 * 获取监控适配器 basic 信息 
	 * 
	 * @param \lib\member\condition\get\sw_madapter_basic $condition 
	 * @access public
	 * @return void
	 */
	public function get_basic(\lib\member\condition\get\sw_madapter_basic $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
							 ->from(SWAN_TBN_MADAPTER_BASIC, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}
	
	// }}}
	// {{{ public function get_info()

	/**
	 * 获取监控适配器的详细信息 
	 * 
	 * @param int $madapter_id 
	 * @access public
	 * @return array
	 */
	public function get_info($madapter_id)
	{
		$bind_data = array($madapter_id);
		$select = $this->__db->select()
							 ->from(SWAN_TBN_MADAPTER_BASIC, '*')
							 ->where(' madapter_id = ?');

		$result = $this->__db->fetch_all($select, $bind_data);
		if (!isset($result[0])) {
			throw new sw_exception('get madapter basic info fail.');
		}

		return $result[0];
	}

	// }}}
	// {{{ public function mod_basic()

	/**
	 * mod_basic 
	 * 
	 * @param \lib\member\condition\mod\sw_madapter_basic $condition 
	 * @access public
	 * @return void
	 */
	public function mod_basic(\lib\member\condition\mod\sw_madapter_basic $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$attributes = $condition->get_property()->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return; 
		}

		$this->__db->update(SWAN_TBN_MADAPTER_BASIC, $attributes, $where);
	}

	// }}}
	// {{{ public function del_basic()

	/**
	 * 删除 madapter 设备信息 
	 * 
	 * @param \lib\member\condition\del\sw_madapter_basic $condition 
	 * @access public
	 * @return void
	 */
	public function del_basic(\lib\member\condition\del\sw_madapter_basic $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return; 
		}

		$this->__db->delete(SWAN_TBN_MADAPTER_BASIC, $where);
	}

	// }}}
	// {{{ public function add_madapter_handler()

	/**
	 * 添加监控适配器的处理器 
	 * 
	 * @param \lib\member\property\sw_madapter_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function add_madapter_handler($property = null)
	{

	}

	// }}}
	// {{{ public function mod_madapter_handler()

	/**
	 * 修改监控适配器的处理器 
	 * 
	 * @param \lib\member\property\sw_madapter_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function mod_madapter_handler($property = null)
	{
		
	}

	// }}}
	// {{{ public function del_madapter_handler()

	/**
	 * 删除监控适配器的处理器 
	 * 
	 * @param \lib\member\property\sw_madapter_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	public function del_madapter_handler($property = null)
	{
	}

	// }}}
	// {{{ protected function _validate()

	/**
	 * _validate 
	 * 
	 * @param mixed $madapter_name 
	 * @access protected
	 * @return void
	 */
	protected function _validate($madapter_name)
	{
		$parrent = '/^[a-zA-Z]+[0-9a-zA-Z_]{2,}$/is';
		if (!preg_match($parrent, $madapter_name)) {
			throw new sw_exception("监控适配器的格式必须是首个字符是字母，由数字、字母、下划线组成,并且至少3位");  
		}

		$is_exists = $this->__db->fetch_one($this->__db->select()
								->from(SWAN_TBN_MADAPTER_BASIC, array('madapter_id'))
								->where('madapter_name= ?'), $madapter_name);

		if ($is_exists) {
			throw new sw_exception("`$madapter_name` madapter name already exists.");
		}
	}

	// }}}
	// }}}
}
