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
 
require_once PATH_SWAN_LIB . 'condition/sw_condition_adapter_get_abstract.class.php';

/**
+------------------------------------------------------------------------------
*  获取设备关键信息条件对象
+------------------------------------------------------------------------------
* 
* @uses sw_condition_adapter_get_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_condition_adapter_member_operator_device_key_get_key extends sw_condition_adapter_get_abstract
{
	// {{{ members

	/**
	 * 允许设置的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__allow_params = array(
		'device_id'   => true,
		'device_name' => true,
	);

	// }}}
	// {{{ functions
	// }}}
}
