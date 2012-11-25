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
 
require_once PATH_SWAN_LIB . 'sw_orm.class.php';

/**
+------------------------------------------------------------------------------
* 更新rrd数据库 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_rrd_update
{
	// {{{ functions
	// {{{ public function update_rrd()

	// }}}
	// {{{ protected function _get_device_info()
	
	/**
	 * 获取设备上的详细信息 
	 * 
	 * @access protected
	 * @return array
	 */
	protected function _get_device_info($device_id = null)
	{
		$condition = sw_orm::condition_factory('rrd', 'device:get_device');		
		if (null !== $device_id) {
			$condition->set_device_id($device_id);
		}
		
		$operator = sw_orm::operator_factory('rrd', 'device');
		$device_info = $operator->get_device($condition);

		return $device_info;
	}

	// }}}
	// {{{ protected function _get_device_project()
	
	/**
	 * 获取设备上的监控项目 
	 * 
	 * @access protected
	 * @return array
	 */
	protected function _get_device_project($device_id)
	{
		$condition = sw_orm::condition_factory('rrd', 'device_project:get_device_project');		
		$condition->set_device_id($device_id);
		
		$operator = sw_orm::operator_factory('rrd', 'device_project');
		$project_info = $operator->get_device_project($condition);
		return $project_info;
	}

	// }}}
	// {{{ protected function _get_ds_info()
	
	/**
	 * 获取设备项目中的数据源
	 * 
	 * @access protected
	 * @return array
	 */
	protected function _get_ds_info($project_id)
	{
		$condition = sw_orm::condition_factory('rrd', 'rrd_ds:get_rrd_ds');		
		$condition->set_project_id($project_id);
		
		$operator = sw_orm::operator_factory('rrd', 'rrd_ds');
		$ds_info = $operator->get_rrd_ds($condition);

		return $ds_info;
	}

	// }}}
	// }}}
}
