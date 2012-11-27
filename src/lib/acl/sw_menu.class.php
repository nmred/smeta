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
 
/**
+------------------------------------------------------------------------------
* 常用菜单
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_menu
{
	// {{{ functions
	// {{{ public static function get_static_menu()

	public static function get_static_menu()
	{
		return array (
			// {{{ input
			'm_device' => array (
				'text' => gettext('监控设备管理'),
				'icons'=> 'icons_computer',
				'sub_categories' => array (
					'm_device_add' => array(
						'text' => gettext('添加设备'),
						'q'    => 'device_add',
					),
					'm_device_list' => array(
						'text' => gettext('设备列表'),
						'q'    => 'device_list',
					),
				),
			),
			'm_project' => array (
				'text' => gettext('监控项管理'),
				'icons'=> 'icons_search',
				'sub_categories' => array (
					'm_project_add' => array(
						'text' => gettext('添加监控项'),
						'q'    => 'project_add',
					),
					'm_project_list' => array(
						'text' => gettext('监控项列表'),
						'q'    => 'project_list',
					),
				),
			),
			'm_input_data' => array (
				'text' => gettext('数据输入管理'),
				'icons'=> 'icons_advanced',
				'sub_categories' => array (
					'm_input_ds_add' => array(
						'text' => gettext('数据源添加'),
						'q'    => '',
					),
					'm_input_ds_list' => array(
						'text' => gettext('数据源列表'),
						'q'    => '',
					),
					'm_input_rra_add' => array(
						'text' => gettext('归档策略添加'),
						'q'    => '',
					),
					'm_input_rra_list' => array(
						'text' => gettext('归档策略列表'),
						'q'    => '',
					),
				),
			),
			// }}}
		);
	}

	// }}}
	// }}}	
}
