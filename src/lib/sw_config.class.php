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

require_once PATH_SWAN_LIB . 'config/sw_config_exception.class.php';  
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
	// {{{ members
	
	const INI_PATH = 'config/swansoft.ini';
	/**
	 * 配置参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__param = array();

	// }}} end members
	// {{{ functions
	// {{{ public function __construct
	
	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		if (!file_exists(self::INI_PATH)) {
			throw new sw_config_exception('ini config file not exists！');
		}
		
		$this->__param = parse_ini_file(self::INI_PATH);
	}

	// }}}
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
		
		// sw_config::get('db');
		if (strpos($string, ':') === false) {
			if (isset($this->__param[$string])) {
				return $this->__param[$string];	
			}
		}

		// sw_config::get('db:username');
		$temp_arr = explode($string, ':');
		if (isset($this->__param[$temp_arr[0]][$temp_arr[1]])) {
			return $this->__param[$temp_arr[0]][$temp_arr[1]];	
		}
		return false;
	}

	// }}}
	// }}} end functions
}
