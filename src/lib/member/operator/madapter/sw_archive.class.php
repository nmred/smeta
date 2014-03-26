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
* 监控适配器 archive 
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
	 * 添加监控适配器 archive 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_archive(\lib\member\property\sw_madapter_archive $property)
	{
		$property_basic = $this->get_madapter_operator()->get_madapter_basic_property();
		$attributes = $property_basic->attributes();
		if (!isset($attributes['madapter_id'])) {
			throw new sw_exception("unknow madapter id.");	
		}

		$madapter_id = $attributes['madapter_id'];
		$attributes = $property->attributes();
		$this->_validate($madapter_id);

		if (!isset($attributes['archive_id'])) {
			$archive_id = \lib\sequence\sw_sequence::get_next_madapter($madapter_id, SWAN_TBN_MADAPTER_ARCHIVE);	
		} else {
			$archive_id = $attributes['archive_id'];		
		}

		$property->set_archive_id($archive_id);
		$property->set_madapter_id($madapter_id);
		$attributes = $property->attributes();
		$require_fields = array('archive_id', 'madapter_id', 'cf_type', 'xff', 'steps', 'rows', 'title');

		$this->_check_require($attributes, $require_fields);

        $this->__db->insert(SWAN_TBN_MADAPTER_ARCHIVE, $attributes);

		return $archive_id;
	}
	
	// }}}
	// {{{ public function get_archive()

	/**
	 * 获取监控适配器 archive 
	 * 
	 * @param \lib\member\condition\get\sw_madapter_archive $condition 
	 * @access public
	 * @return void
	 */
	public function get_archive(\lib\member\condition\get\sw_madapter_archive $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
							 ->from(SWAN_TBN_MADAPTER_ARCHIVE, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}

	// }}}
	// {{{ public function mod_archive()

	/**
	 * mod_archive 
	 * 
	 * @param \lib\member\condition\mod\sw_madapter_archive $condition 
	 * @access public
	 * @return void
	 */
	public function mod_archive(\lib\member\condition\mod\sw_madapter_archive $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$attributes = $condition->get_property()->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return; 
		}

		$this->__db->update(SWAN_TBN_MADAPTER_ARCHIVE, $attributes, $where);
	}

	// }}}
	// {{{ public function del_archive()

	/**
	 * 删除 madapter archive 
	 * 
	 * @param \lib\member\condition\del\sw_madapter_archive $condition 
	 * @access public
	 * @return void
	 */
	public function del_archive(\lib\member\condition\del\sw_madapter_archive $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return; 
		}

		$this->__db->delete(SWAN_TBN_MADAPTER_ARCHIVE, $where);
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
	 * @param int $madapter_id 
	 * @access protected
	 * @return void
	 */
	protected function _validate($madapter_id)
	{
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
