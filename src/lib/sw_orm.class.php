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
* 数据对象映射工厂
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_orm
{
	// {{{ functions
	// {{{ public static function property_factory()

	/**
	 * 属性工厂 
	 * 
	 * @param string $module 
	 * @param string $type 
	 * @param array $params 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function property_factory($module, $type, array $params = array())
	{
		require_once 'sw_property.class.php';
		return sw_property::factory($module, $type, $params);
	}

	// }}}
	// {{{ public static function condition_factory()

	/**
	 * 条件工厂 
	 * 
	 * @param string $module 
	 * @param string $type 
	 * @param array $params 
	 * @param array $query_opts 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function condition_factory($module, $type, array $params = array(), array $query_opts = array())
	{
		require_once 'sw_condition.class.php';
		return sw_condition::factory($module, $type, $params, $query_opts);
	}

	// }}}
	// {{{ public static function operator_factory()

	/**
	 * 操作对象工厂 
	 * 
	 * @param string $module   rrd 
	 * @param string $type     device:get
	 * @param array $params 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function operator_factory($module, $type, array $params = array())
	{
		if (empty($module) || empty($type)) {
			require_once PATH_SWAN_LIB . 'operator/sw_operator_exception.class.php';
			throw new sw_operator_exception("factory param error");	
		}

		$class_name = 'sw_operator_' . $module . '_' .  $type;

		if (!class_exists($class_name)) {
			require_once PATH_SWAN_LIB . $module . '/operator/' . $class_name . '.class.php';	
		}

		if (!class_exists($class_name)) {	
			require_once PATH_SWAN_LIB . 'operator/sw_operator_exception.class.php';
			throw new sw_operator_exception("can not load '$class_name'");	
		}

		return new $class_name($params);
	}

	// }}}
	// }}}
}
