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

namespace lib\config;

/**
+------------------------------------------------------------------------------
* sw_config 
+------------------------------------------------------------------------------
* 
* @package lib
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_config
{
	// {{{ members

	/**
	 * 存取配置文件配置项 
	 * 
	 * @var array
	 * @access protected
	 */
	protected static $__cfg = null;

	// }}}
	// {{{ functions

	/**
	 * 获取配置 
	 * 
	 * @param string $type 
	 * @access public
	 * @return mixed|null
	 */
	public static function get_config($type = null)
	{
		if (!isset(self::$__cfg)) {
			self::$__cfg = include(PATH_SWAN_CONF . 'config.php');
		}
		
		if (!isset($type)) {	
			return self::$__cfg;
		}

		if (isset(self::$__cfg[$type])) {
			return self::$__cfg[$type];	
		}

		return null;
	}

	// }}}		
}
