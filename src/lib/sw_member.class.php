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
* member 对象映射工厂
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_member
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
	public static function property_factory($type, array $params = array())
	{
		require_once 'sw_property.class.php';
		return sw_property::factory('member', $type, $params);
	}

	// }}}
	// {{{ public static function condition_factory()

	/**
	 * 条件工厂 
	 * 
	 * @param string $type device_key:add_key 
	 * @param array $params 
	 * @param array $query_opts 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function condition_factory($type, array $params = array(), array $query_opts = array())
	{
		require_once 'sw_condition.class.php';
		return sw_condition::factory('member:operator', $type, $params, $query_opts);
	}

	// }}}
	// {{{ public static function operator_factory()

	/**
	 * 操作对象工厂 
	 * 
	 * @param string $type     device_key
	 * @param sw_member_property_abstract $property
	 * @static
	 * @access public
	 * @return void
	 */
	public static function operator_factory($type, sw_member_property_abstract $property = null)
	{
		if (!class_exists('sw_member_operator_device')) {
			require_once PATH_SWAN_LIB . 'member/operator/sw_member_operator_device.class.php';
			if (!class_exists('sw_member_operator_device')) {
				require_once PATH_SWAN_LIB . 'member/operator/sw_member_operator_exception_.class.php';
				throw new sw_member_operator_exception("Can not load class `sw_member_operator_device`.");
			}
		}

		if (isset($property)) {
			$device = new sw_member_operator_device($property);	
		} else {
			$device = new sw_member_operator_device();	
		}

		if ('device' === $type) {
			return $device;	
		}

		if (!method_exists($device, $type)) {
			require_once PATH_SWAN_LIB . 'member/operator/sw_member_operator_exception_.class.php';
			throw new sw_member_operator_exception("Can not get `$type` operator object");
		}

		return $device->$type();
	}

	// }}}
	// }}}
}
