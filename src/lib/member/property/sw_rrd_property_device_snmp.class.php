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
 
require_once PATH_SWAN_LIB . 'property/sw_property_adapter_abstract.class.php';

/**
+------------------------------------------------------------------------------
* sw_property_rrd_device 
+------------------------------------------------------------------------------
* 
* @uses sw_property_adapter_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_property_rrd_device extends sw_property_adapter_abstract
{
	// {{{ members

	/**
	 * 允许设置的元素列表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__allow_attributes = array(
		'device_id'           => true,
		'device_name'         => true,
		'device_display_name' => true,
		'host'                => true,
		'port'                => true,
		'snmp_version'        => true,
		'snmp_method'         => true,
		'snmp_protocol'       => true,
		'snmp_community'      => true,
		'snmp_timeout'        => true,
		'snmp_retries'        => true,
		'security_name'       => true,
		'security_level'      => true,
		'auth_protocol'       => true,	
		'auth_passphrase'     => true,
		'priv_protocol'       => true,
		'priv_passphrase'     => true,
	);

	/**
	 * 主键 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__key_attributes = array('device_id');

	/**
	 * 整形类型 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__int_fields = array(
		'port',
		'snmp_timeout',
		'snmp_retries',
	);

	/**
	 * 枚举类型 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__int_enum_fields = array(
		'snmp_version'   => array(0, 1, 2),
		'snmp_method'    => array(0, 1),
		'snmp_protocol'  => array(0, 1),
		'security_level' => array(0, 1, 2),
		'auth_protocol'  => array(0, 1),
		'priv_protocol'  => array(0, 1),
	);
		
	// }}}		
	// {{{ functions
	// {{{ public function check()

	/**
	 * 检查参数 
	 * 
	 * @access public
	 * @return void
	 */
	public function check()
	{
		parent::check();
		$attributes = $this->attributes();

		if (isset($attributes['host'])) {	
			require_once PATH_SWAN_LIB . 'sw_validate.class.php';
			sw_validate::validate_ip($attributes['host']);	
		}	
	}

	// }}}
	// }}}
}
