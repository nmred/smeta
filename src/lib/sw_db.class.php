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
	// {{{ const

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

	// }}}
	// {{{ members
	
	/**
	 * 每种数据库一个单件  
	 */
	protected static $__db = array();

	// }}} end members
	// {{{ functions
	// {{{ public static function factory()
	
	/**
	 * factory 
	 * 
	 * @param mixed $type 
	 * @param array $options 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function factory($type = null, array $options = array())
	{
		if ($type === null) {
			$type = sw_config::get('db:type');	
		}
		
		$class_name = 'sw_db_adapter_' .  $type;

		if (!class_exists($class_name)) {
			require_once PATH_SWAN_LIB . 'db/' . $class_name . '.class.php';	
		}

		if (!class_exists($class_name)) {
			require_once PATH_SWAN_LIB . 'db/sw_db_exception.class.php';
			throw new sw_db_exception("can not load $class_name");	
		}

		return new $class_name($options);
		
	}
	 
	// }}}
	// {{{ public static function singleton()
	
	/**
	 * db的单件 
	 * 
	 * @param string $db_type 
	 * @param array $options 
	 * @static
	 * @access public
	 * @return sw_db_adapter_abstract
	 */
	public static function singleton($db_type = null, array $options = array())
	{
		$db_type = isset($db_type) ? $db_type : sw_config::get('db:type');
		
		if (isset(self::$__db[$db_type]) && self::$__db[$db_type] instanceof sw_db_adapter_abstract) {
			return self::$__db[$db_type];
		} 

		self::$__db[$db_type] = self::factory($db_type, $options);
		return self::$__db[$db_type];
	}

	// }}}
	// {{{ public static function singleton_clear()

	/**
	 * 清除单件，多进程下不能共用一个连接 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function singleton_clear()
	{
		self::$__db = array();	
	}

	// }}}
	// }}} end functions
}
