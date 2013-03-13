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
* 条件工厂 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_condition
{
	// {{{ functions
	// {{{ public static function factory()

	/**
	 * 条件对象工厂 
	 * 
	 * @param string $module   rrd 
	 * @param string $type     device:get
	 * @param array $params 
	 * @param array $query_opts 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function factory($module, $type, array $params = array(), array $query_opts = array())
	{
		if (empty($module) || empty($type) || false === strpos($type, ':')) {
			require_once PATH_SWAN_LIB . 'condition/sw_condition_exception.class.php';
			throw new sw_condition_exception("factory param error");	
		}

		$module_name = str_replace(':', '_', $module);
		$module_path = str_replace(':', '/', $module);
		$class_name = 'sw_condition_adapter_' . $module_name . '_' . str_replace(':', '_', $type) ;

		if (!class_exists($class_name)) {
			require_once PATH_SWAN_LIB . $module_path . '/condition/' . $class_name . '.class.php';	
		}

		if (!class_exists($class_name)) {	
			require_once PATH_SWAN_LIB . 'condition/sw_condition_exception.class.php';
			throw new sw_condition_exception("can not load '$class_name'");	
		}

		return new $class_name($params, $query_opts);
	}

	// }}}
	// }}}
}
