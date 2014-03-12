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
* sw_get_device_monitor 
+------------------------------------------------------------------------------
* 
* @uses sw_get_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_get_device_monitor extends sw_get_abstract
{
	// {{{ members

	/**
	 * 允许设置的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__allow_params = array(
		'dm_id'     => true,
		'device_id' => true,
	);

    /**
     * 查询字段 
     * 
     * @var array
     * @access protected
     */
    protected $__columns = array(
        'dm_id'       => SWAN_TBN_DEVICE_MONITOR,       
        'dm_name'     => SWAN_TBN_DEVICE_MONITOR,       
        'device_id'   => SWAN_TBN_DEVICE_MONITOR,       
        'monitor_id'  => SWAN_TBN_DEVICE_MONITOR,       
        'monitor_name'  => SWAN_TBN_MONITOR_BASIC,     
        'steps'         => SWAN_TBN_MONITOR_BASIC,     
        'store_type'    => SWAN_TBN_MONITOR_BASIC,     
        'monitor_type'  => SWAN_TBN_MONITOR_BASIC,     
        'monitor_display_name'  => SWAN_TBN_MONITOR_BASIC,     
    );

    /**
     * 表的别名 
     * 
     * @var array
     * @access protected
     */
    protected $__table_alias = array(
        SWAN_TBN_DEVICE_MONITOR => 'k',
        SWAN_TBN_MONITOR_BASIC => 'b',
    );

	// }}}
	// {{{ functions
	// }}}
}
