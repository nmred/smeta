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
* sw_log_format_abstract
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_log_format_abstract
{
	// {{{ members

	/**
	 *  初始化的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__options = array();

	// }}}
	// {{{ functions
	// {{{ public function formattor()
	
	/**
	 * 格式化日志信息 
	 * 
	 * @access public
	 * @return string
	 */
	public function formattor()
	{
		$str = time() . ':' . $message;
		
		return $str; 	
	}

	// }}}
	// {{{ abstract public function message()

	/**
	 * 格式化信息 
	 * 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function message();

	// }}}
	// }}}		
}
