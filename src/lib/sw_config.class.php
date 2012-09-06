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
* LIB配置参数配置器 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_config
{
	// {{{ functions
	// {{{ public static function get()

	/**
	 * 获取配置参数 
	 * 
	 * @param string $string 格式：db:username  其中db为节点名，username是db节点内的配置项
	 * @static
	 * @access public
	 * @return void
	 */
	public static function get($string)
	{
		if (substr_count($string, ':') > 1) {
			throw new sw_config_exception('The parameters of the transmission format error');	
		}
		
		require_once PATH_SWAN_LIB . 'config/sw_config_ini.class.php';
		$global_config = include(PATH_SWAN_LIB . 'config/sw_config_map.class.php');
		$config_ini_object = new sw_config_ini();
		$config_ini = $config_ini_object->get();
		
		// sw_config::get('db');
		if (strpos($string, ':') === false) {
			$global_config_single = array();
			$config_ini_single    = array();
			if (isset($global_config[$string])) {
				$global_config_single = $global_config[$string];	
			}
			if (isset($config_ini[$string])) {
				$config_ini_single = $config_ini[$string];	
			}
			return array_merge($global_config_single, $config_ini_single);
		}

		// sw_config::get('db:username');
		$temp_arr = explode(':', $string);
		if (isset($config_ini[$temp_arr[0]][$temp_arr[1]])) {
			return $config_ini[$temp_arr[0]][$temp_arr[1]];	
		}
		if (isset($global_config[$temp_arr[0]][$temp_arr[1]])) {
			return $global_config[$temp_arr[0]][$temp_arr[1]];	
		}
		return false;
	}

	// }}}
	// }}} end functions
}
