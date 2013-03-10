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
 
require_once PATH_SWAN_LIB . 'operator/sw_operator_abstract.class.php';

/**
+------------------------------------------------------------------------------
* rrd rrs操作对象 
+------------------------------------------------------------------------------
* 
* @uses sw_operator_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_operator_rrd_rrd_rra extends sw_operator_abstract
{
	// {{{ functions
	// {{{ public funcction add_rrd_rra()

	/**
	 * 添加数据源 
	 * 
	 * @param sw_property_rrd_rrd_rra $property 
	 * @access public
	 * @return void
	 */
	public function add_rrd_rra(sw_property_rrd_rrd_rra $property)
	{
		$attributes = $property->attributes();
		$require_fields = array('project_id', 'device_id', 'rra_id');
		$this->_check_require($attributes, $require_fields);

		$this->__db->insert(SWAN_TBN_RRD_RRA, $attributes);
	}

	// }}}
	// {{{ public funcction get_rrd_rra()

	/**
	 * 获取数据源信息 
	 * 
	 * @param sw_condition_operator_rrd_rrd_rra_get_rrd_rra $condition 
	 * @access public
	 * @return array
	 */
	public function get_rrd_rra(sw_condition_operator_rrd_rrd_rra_get_rrd_rra $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
						     ->from(SWAN_TBN_RRD_RRA, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());
	}

	// }}}
	// {{{ public funcction mod_rrd_rra()

	/**
	 * 修改数据源信息 
	 * 
	 * @param sw_condition_operator_rrd_rrd_rra_mod_rrd_rra $condition 
	 * @access public
	 * @return array
	 */
	public function mod_rrd_rra(sw_condition_operator_rrd_rrd_rra_mod_rrd_rra $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$attributes = $condition->get_property()->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return;	
		}

		$this->__db->update(SWAN_TBN_RRD_RRA, $attributes, $where);
	}

	// }}}
	// {{{ public funcction del_rrd_rra()

	/**
	 * 删除数据源信息 
	 * 
	 * @param sw_condition_operator_rrd_rrd_rra_del_rrd_rra $condition 
	 * @access public
	 * @return void
	 */
	public function del_rrd_rra(sw_condition_operator_rrd_rrd_rra_del_rrd_rra $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return;	
		}

		$this->__db->delete(SWAN_TBN_RRD_RRA, $where);
	}

	// }}}
	// }}}	
} 
