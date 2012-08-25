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
* DB工厂类 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_db
{
	// {{{ members

	/**
	 * 定义sql分析器引擎  
	 */
	const PROFILER = 'profiler';

	/**
	 * 定义CASE_FOLDING  
	 */
	const CASE_FOLDING = 'case_folding';

	/**
	 * 定义FETCH_MODE  
	 */
	const FETCH_MODE = 'fetch_mode';

	/**
	 * 定义AUTO_QUOTE_INDENTIFIERS  
	 */
	const AUTO_QUOTE_INDENTIFIERS = 'auto_quote_indentifiers';

	/**
	 * 定义ALLOW_SERIALIZATION  
	 */
	const ALLOW_SERIALIZATION = 'allow_serialization';

	/**
	 * 定义INT_TYPE,BIGINT_TYPE,FLOAT_TYPE在quote()中用
	 */
	const INT_TYPE    = 0;
	const BIGINT_TYPE = 1;
	const FLOAT_TYPE  = 2;

	/**
	 * 定义PDO中的常量
	 */
	//TODO
			
	// }}} end members
	// {{{ functions
	// {{{ public static function factory()
	
	public static function factory($type) 
	// }}}
	// }}} end functions
}
