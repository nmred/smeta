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
 
namespace lib\member\condition\get;
use \lib\member\condition\get\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 获取设备条件 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_device extends sw_abstract
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

	/**
	 * 查询字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__columns = array(
		'device_id'   => SWAN_TBN_DEVICE_KEY,		
		'device_name' => SWAN_TBN_DEVICE_KEY,		
		'host_name'   => SWAN_TBN_DEVICE_BASIC,		
		'heartbeat_time'      => SWAN_TBN_DEVICE_BASIC,		
		'device_display_name' => SWAN_TBN_DEVICE_BASIC,		
	);

	// }}}
	// {{{ functions
	// }}}
}
