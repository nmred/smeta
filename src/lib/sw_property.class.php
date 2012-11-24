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
* 属性的工厂类
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_property
{
	// {{{ functions

	/**
	 * 属性工厂类 
	 * 
	 * @param string $module 
	 * @param string $type 
	 * @param array $params 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function factory($module, $type, array $params = array())
	{
		$class_name = 'sw_property_' . $module . '_' . $type;
		
		if (!class_exists($class_name)) {
			require_once PATH_SWAN_LIB . $module . '/property/'	. $class_name . '.class.php';
		}		

		if (!class_exists($class_name)) {
			require_once PATH_SWAN_LIB . 'property/sw_property_adapter_exception.class.php';
			throw new sw_property_adapter_exception("can not load '$class_name'");
		}

		return new $class_name($params);
	}

	// }}}		
}
