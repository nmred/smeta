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
 
namespace lib\member\condition\mod;
use \lib\member\condition\mod\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 修改设备监控器条件 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_device_monitor extends sw_abstract
{
	// {{{ members

	/**
	 * 允许设置的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__allow_params = array(
		'device_id'  => true,
		'monitor_id' => true,
	);

	// }}}
	// {{{ functions
	// }}}
}
