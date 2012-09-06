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
class sw_config_ini
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
		$file_path = PATH_SWAN_LIB . self::INI_PATH;
		if (!file_exists($file_path)) {
			throw new sw_config_exception('ini config file not exists！');
		}
		
		$this->__param = parse_ini_file($file_path, true);
	}

	// }}}
	// {{{ public  function get()

	/**
	 * 获取配置参数 
	 * 
	 * @param string $string 格式：db:username  其中db为节点名，username是db节点内的配置项
	 * @access public
	 * @return void
	 */
	public  function get()
	{
		return $this->__param;
	}

	// }}}
	// }}} end functions
}
