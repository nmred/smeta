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
 
namespace lib\member\condition;
use \lib\member\condition\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_get_device 
+------------------------------------------------------------------------------
* 
* @uses sw_get_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_get_device extends sw_get_abstract
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
		'device_display_name' => true,
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
		'device_display_name' => SWAN_TBN_DEVICE_BASIC,		
		'monitor_id'  => SWAN_TBN_DEVICE_MONITOR,		
		'attr_id'     => SWAN_TBN_DEVICE_MONITOR,		
		'value_id'    => SWAN_TBN_DEVICE_MONITOR,		
		'value'    => SWAN_TBN_DEVICE_MONITOR,		
	);

	// }}}
	// {{{ functions
	// }}}
}
