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

namespace lib\db;
use PDO;

/**
+------------------------------------------------------------------------------
* sw_db 
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
	// {{{ const

	/**
	 * 执行 quote 操作时传递的第二个参数  
	 */
	const INT_TYPE = 0;

	/**
	 * 执行 quote 操作时传递的第二个参数  
	 */
	const BIGINT_TYPE = 1;

	/**
	 * 执行 quote 操作时传递的第二个参数  
	 */
	const FLOAT_TYPE = 2;

	/**
	 * 强制列名小写
	 */
	 const CASE_LOWER = PDO::CASE_LOWER;

	/**
	 * 强制列名大写
	 */
	 const CASE_UPPER = PDO::CASE_UPPER;

	/**
	 * 不强制转换
	 */
	 const CASE_NATURAL = PDO::CASE_NATURAL;

	// }}}	
}
