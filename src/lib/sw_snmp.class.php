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
* 处理snmp
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_snmp
{
	// {{{ const

	/**
	 * snmp的版本  
	 */
	const VERSION_1  = 'version_one';
	const VERSION_2  = 'version_two';
	const VERSION_3  = 'version_three';

	// }}}
	// {{{ public function get_snmp()

	/**
	 * 获取snmp对象 
	 * 
	 * @param string $version 
	 * @access public
	 * @return sw_snmp_abstract
	 */
	public function get_snmp($version = self::VERSION_1)
	{
		$class_name = 'sw_snmp_' . $version;
		if (!class_exists($class_name)) {
			$class_path = PATH_SWAN_LIB . 'snmp/' . $class_name . '.class.php';
			if (is_readable($class_path)) {
				require_once $class_path;	
			} else {
				require_once PATH_SWAN_LIB . 'snmp/sw_snmp_exception.class.php';	
				throw new sw_snmp_exception("snmp get class failed. ");
			}
		}

		return new $class_name();
	}

	// }}}
}
