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
* 监控适配器 attribute 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_attribute extends sw_abstract
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function add_attribute()

	/**
	 * 添加监控适配器 attribute 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_attribute(\lib\member\property\sw_madapter_attribute $property)
	{
		$property_basic = $this->get_madapter_operator()->get_madapter_basic_property();
		$attributes = $property_basic->attributes();
		if (!isset($attributes['madapter_id'])) {
			throw new sw_exception("unknow madapter id.");	
		}

		$madapter_id = $attributes['madapter_id'];
		$attributes = $property->attributes();
		$this->_validate($madapter_id, $attributes['attr_name']);

		if (!isset($attributes['attr_id'])) {
			$attr_id = \lib\sequence\sw_sequence::get_next_madapter($madapter_id, SWAN_TBN_MADAPTER_ATTRIBUTE);	
		} else {
			$attr_id = $attributes['attr_id'];		
		}

		$property->set_attr_id($attr_id);
		$property->set_attr_name($attributes['attr_name']);
		$property->set_madapter_id($madapter_id);
		$attributes = $property->attributes();
		$require_fields = array('attr_id', 'madapter_id', 'attr_name', 'form_type');

		$this->_check_require($attributes, $require_fields);

        $this->__db->insert(SWAN_TBN_MADAPTER_ATTRIBUTE, $attributes);

		return $attr_id;
	}
	
	// }}}
	// {{{ public function get_attribute()

	/**
	 * 获取监控适配器属性 
	 * 
	 * @param \lib\member\condition\sw_get_madapter_attribute $condition 
	 * @access public
	 * @return void
	 */
	public function get_attribute(\lib\member\condition\sw_get_madapter_attribute $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
							 ->from(SWAN_TBN_MADAPTER_ATTRIBUTE, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}

	// }}}
	// {{{ public function get_info()

	/**
	 * 获取监控适配器属性详细信息 
	 * 
	 * @param int $madapter_id 
	 * @param int $attr_id 
	 * @access public
	 * @return void
	 */
	public function get_info($madapter_id, $attr_id)
	{
		$bind_data = array($madapter_id, $attr_id);
		$select = $this->__db->select()
							 ->from(SWAN_TBN_MADAPTER_ATTRIBUTE, '*')
							 ->where(' madapter_id = ? AND attr_id = ?');

		$result = $this->__db->fetch_all($select, $bind_data);
		if (!isset($result[0])) {
			throw new sw_exception('get madapter attribute fail.');
		}

		return $result[0];
	}

	// }}}
	// {{{ public function mod_attribute()

	/**
	 * mod_attribute 
	 * 
	 * @param \lib\member\condition\sw_mod_madapter_attribute $condition 
	 * @access public
	 * @return void
	 */
	public function mod_attribute(\lib\member\condition\sw_mod_madapter_attribute $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$attributes = $condition->get_property()->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return; 
		}

		$this->__db->update(SWAN_TBN_MADAPTER_ATTRIBUTE, $attributes, $where);
	}

	// }}}
	// {{{ public function del_attribute()

	/**
	 * 删除 madapter 属性 
	 * 
	 * @param \lib\member\condition\sw_del_madapter_attribute $condition 
	 * @access public
	 * @return void
	 */
	public function del_attribute(\lib\member\condition\sw_del_madapter_attribute $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return; 
		}

		$this->__db->delete(SWAN_TBN_MADAPTER_ATTRIBUTE, $where);
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
	 * @param mixed $attr_name 
	 * @param int $madapter_id 
	 * @access protected
	 * @return void
	 */
	protected function _validate($madapter_id, $attr_name)
	{
		$parrent = '/^[a-zA-Z]+[0-9a-zA-Z_]{2,}$/is';
		if (!preg_match($parrent, $attr_name)) {
			throw new sw_exception("监控适配器属性名的格式必须是首个字符是字母，由数字、字母、下划线组成,并且至少3位");  
		}

		$is_exists = $this->__db->fetch_one($this->__db->select()
								->from(SWAN_TBN_MADAPTER_BASIC, array('madapter_id'))
								->where('madapter_id= ?'), $madapter_id);

		if (!$is_exists) {
			throw new sw_exception("`$madapter_id` madapter id not  exists.");
		}
	}

	// }}}
	// }}}
}
