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
* 监控适配器 metric 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_metric extends sw_abstract
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function add_metric()

	/**
	 * 添加监控适配器 metric 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_metric(\lib\member\property\sw_madapter_metric $property)
	{
		$property_basic = $this->get_madapter_operator()->get_madapter_basic_property();
		$attributes = $property_basic->attributes();
		if (!isset($attributes['madapter_id'])) {
			throw new sw_exception("unknow madapter id.");	
		}

		$madapter_id = $attributes['madapter_id'];
		$attributes = $property->attributes();
		$this->_validate($madapter_id, $attributes['metric_name']);

		if (!isset($attributes['metric_id'])) {
			$metric_id = \lib\sequence\sw_sequence::get_next_madapter($madapter_id, SWAN_TBN_MADAPTER_METRIC);	
		} else {
			$metric_id = $attributes['metric_id'];		
		}

		$property->set_metric_id($metric_id);
		$property->set_metric_name($attributes['metric_name']);
		$property->set_madapter_id($madapter_id);
		$attributes = $property->attributes();
		$require_fields = array('metric_id', 'madapter_id', 'metric_name', 'collect_every', 'time_threshold');

		$this->_check_require($attributes, $require_fields);

        $this->__db->insert(SWAN_TBN_MADAPTER_METRIC, $attributes);

		return $metric_id;
	}
	
	// }}}
	// {{{ public function get_metric()

	/**
	 * get_metric 
	 * 
	 * @param \lib\member\condition\get\sw_madapter_metric $condition 
	 * @access public
	 * @return void
	 */
	public function get_metric(\lib\member\condition\get\sw_madapter_metric $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
							 ->from(SWAN_TBN_MADAPTER_METRIC, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}

	// }}}
	// {{{ public function mod_metric()

	/**
	 * mod_metric 
	 * 
	 * @param \lib\member\condition\mod\sw_madapter_metric $condition 
	 * @access public
	 * @return void
	 */
	public function mod_metric(\lib\member\condition\mod\sw_madapter_metric $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$attributes = $condition->get_property()->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return; 
		}

		$this->__db->update(SWAN_TBN_MADAPTER_METRIC, $attributes, $where);
	}

	// }}}
	// {{{ public function del_metric()

	/**
	 * 删除 madapter 数据项 
	 * 
	 * @param \lib\member\condition\del\sw_madapter_metric $condition 
	 * @access public
	 * @return void
	 */
	public function del_metric(\lib\member\condition\del\sw_madapter_metric $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return; 
		}

		$this->__db->delete(SWAN_TBN_MADAPTER_METRIC, $where);
	}

	// }}}
	// {{{ public function add_madapter_handler()

	/**
	 * 添加监控适配器 
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
	 * 修改监控适配器 
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
	 * 删除监控适配器 
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
	 * @param mixed $metric_name 
	 * @param int $madapter_id 
	 * @access protected
	 * @return void
	 */
	protected function _validate($madapter_id, $metric_name)
	{
		$parrent = '/^[a-zA-Z]+[0-9a-zA-Z_\-]{2,}$/is';
		if (!preg_match($parrent, $metric_name)) {
			throw new sw_exception("监控适配器，由数字、字母、下划线组成,并且至少3位");  
		}

		$is_exists = $this->__db->fetch_one($this->__db->select()
								->from(SWAN_TBN_MADAPTER_BASIC, array('madapter_id'))
								->where('madapter_id= ?'), $madapter_id);

		if (!$is_exists) {
			throw new sw_exception("`$madapter_id` madapter id not  exists.");
		}

		$is_exists = $this->__db->fetch_one($this->__db->select()
								->from(SWAN_TBN_MADAPTER_METRIC, array('metric_id'))
								->where('metric_name= ?'), $metric_name);

		if ($is_exists) {
			throw new sw_exception("`$metric_name` madapter metric name already  exists.");
		}
	}

	// }}}
	// }}}
}
