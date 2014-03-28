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
* 获取设备监控器的参数条件 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_device_monitor_params extends sw_abstract
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
		'madapter_id' => true,
	);

    /**
     * 查询字段 
     * 
     * @var array
     * @access protected
     */
    protected $__columns = array(
        'monitor_id'   => SWAN_TBN_DEVICE_MONITOR,       
        'monitor_name' => SWAN_TBN_DEVICE_MONITOR,       
        'device_id'    => SWAN_TBN_DEVICE_MONITOR,       
        'madapter_id'  => SWAN_TBN_DEVICE_MONITOR,       
        'attr_id'     => SWAN_TBN_MONITOR_PARAM,     
        'value'       => SWAN_TBN_MONITOR_PARAM,     
        'attr_name'    => SWAN_TBN_MADAPTER_ATTRIBUTE,     
        'form_type'    => SWAN_TBN_MADAPTER_ATTRIBUTE,     
        'form_data'    => SWAN_TBN_MADAPTER_ATTRIBUTE,     
        'attr_default' => SWAN_TBN_MADAPTER_ATTRIBUTE,     
        'attr_display_name' => SWAN_TBN_MADAPTER_ATTRIBUTE,     
    );

	/**
	 * 表的别名 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__table_alias = array(
		SWAN_TBN_DEVICE_MONITOR => 'd',
		SWAN_TBN_MONITOR_PARAM  => 'p',
		SWAN_TBN_MADAPTER_ATTRIBUTE => 'a',
	);

	// }}}
	// {{{ functions
	// }}}
}
