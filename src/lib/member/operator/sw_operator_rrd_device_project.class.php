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
* device project操作对象 
+------------------------------------------------------------------------------
* 
* @uses sw_operator_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_operator_rrd_device_project extends sw_operator_abstract
{
	// {{{ functions
	// {{{ public funcction add_device_project()

	/**
	 * 添加设备项目 
	 * 
	 * @param sw_property_rrd_device_project $property 
	 * @access public
	 * @return void
	 */
	public function add_device_project(sw_property_rrd_device_project $property)
	{
		$attributes = $property->attributes();
		$require_fields = array('project_name', 'device_id', 'project_id');
		$this->_check_require($attributes, $require_fields);

		$this->__db->insert(SWAN_TBN_DEVICE_PROJECT, $attributes);
	}

	// }}}
	// {{{ public funcction get_device_project()

	/**
	 * 获取所有的设备项目信息 
	 * 
	 * @param sw_condition_operator_rrd_device_project_get_device_project $condition 
	 * @access public
	 * @return array
	 */
	public function get_device_project(sw_condition_operator_rrd_device_project_get_device_project $condition)
	{
		$condition->check_params();
		$select = $this->__db->select()
						     ->from(SWAN_TBN_DEVICE_PROJECT, null);
		$condition->where($select, true);
		return $this->_get($select, $condition->params());
	}

	// }}}
	// {{{ public funcction mod_device_project()

	/**
	 * 修改的设备项目信息 
	 * 
	 * @param sw_condition_operator_rrd_device_project_mod_device_project $condition 
	 * @access public
	 * @return array
	 */
	public function mod_device_project(sw_condition_operator_rrd_device_project_mod_device_project $condition)
	{
		$condition->check_params();
		$params = $condition->params();
		$attributes = $condition->get_property()->prepared_attributes();

		$where = $condition->where();
		if (!$where || !$attributes) {
			return;	
		}

		$this->__db->update(SWAN_TBN_DEVICE_PROJECT, $attributes, $where);
	}

	// }}}
	// {{{ public funcction del_device_project()

	/**
	 * 删除的设备项目信息 
	 * 
	 * @param sw_condition_operator_rrd_device_project_del_device_project $condition 
	 * @access public
	 * @return void
	 */
	public function del_device_project(sw_condition_operator_rrd_device_project_del_device_project $condition)
	{
		$condition->check_params();
		$where = $condition->where();
		if (!$where) {
			return;	
		}

		$this->__db->delete(SWAN_TBN_DEVICE_PROJECT, $where);
	}

	// }}}
	// }}}	
} 
