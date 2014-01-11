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
 
namespace lib\member;
use \swan\member\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_member 
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
	 * @param mixed $type 
	 * @param array $params 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function property_factory($type, array $params = array())
	{
		\swan\member\sw_member::set_namespace("\\lib\\member\\");
		return \swan\member\sw_member::property_factory('member', $type, $params);
	}

	// }}}
	// {{{ public static function condition_factory()

	/**
	 * 条件工厂 
	 * 
	 * @param string $type 
	 * @param array $params 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function condition_factory($type, array $params = array())
	{
		\swan\member\sw_member::set_namespace("\\lib\\member\\");
		return \swan\member\sw_member::condition_factory('member', $type, $params);
	}

	// }}}
	// {{{ public static function operater_factory()

	/**
	 * 属性工厂 
	 * 
	 * @param mixed $type 
	 * @param array $params 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function operater_factory($type, array $params = array())
	{
		\swan\member\sw_member::set_namespace("\\lib\\member\\");
		return \swan\member\sw_member::operater_factory('member', $type, $params);
	}

	// }}}
	// }}}
}
