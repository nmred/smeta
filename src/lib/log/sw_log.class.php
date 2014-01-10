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
 
namespace lib\log;
use \lib\log\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 日志模块 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_log extends \swan\log\sw_log
{
	// {{{ consts

	const LOG_DEFAULT_ID = 1;
	const LOG_PHPD_ID    = 2;

	// }}}
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public static function L()
	
	/**
	 * log 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public function L($message, $level)
	{
		$options = array('log_id' => self::LOG_DEFAULT_ID);
		$options = array_merge($options, self::get_logsvr_config());
		$writer = parent::writer_factory('logsvr', $options);
		parent::add_writer($writer);
		parent::log($message, $level);
	}

	// }}}		
	// {{{ public static function get_logsvr_config()

	/**
	 * 获取 logsvr 的配置 
	 * 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function get_logsvr_config()
	{
		return array(
			'host' => \swan\config\sw_config::get_config('log:host'),
			'port' => \swan\config\sw_config::get_config('log:port'),
			'self' => \swan\config\sw_config::get_config('log:self'),
		);	
	}

	// }}}
	// }}}
}
