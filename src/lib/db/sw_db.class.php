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
use lib\db\exception\sw_exception;
use lib\config\sw_config;
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
	// {{{ members
	
	/**
	 * 存储数据库的单件 
	 * 
	 * @var array
	 * @access protected
	 */
	protected static $__db = array(); 

	// }}}
	// {{{ functions
	// {{{ public static function factory()

	/**
	 * 数据库工厂 
	 * 
	 * @param string $type 
	 * @param array $options 
	 * @static
	 * @access public
	 * @return lib\db\sw_abstract
	 */
	public static function factory($type = null, array $options = array())
	{
		if (null === $type) {
			$options = sw_config::get_config('db');	
			if (!isset($options['type'])) {
				throw sw_exception("not config db type in config.php");	
			}

			$type = $options['type'];
		}

		$class_name = 'lib\db\adapter\sw_' . $type;

	//	if (!class_exists($class_name)) {
	//		use $class_name;
	//	}

	//	if (!class_exists($class_name)) {
	//		throw new sw_exception("can not load $class_name");	
	//	}

		return new $class_name($options);
	}

	// }}}
	// {{{ public static function singleton()

	/**
	 * 生成单件 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function singleton($db_type = null, array $options = array())
	{
		$db_type = isset($db_type) ? $db_type : sw_config::get_config('db:type');

		if (isset(self::$__db[$db_type]) && self::$__db[$db_type] instanceof lib\db\adapter\sw_abstract) {
			return self::$__db[$db_type];	
		}

		self::$__db[$db_type] = self::factory($db_type, $options);
		return self::$__db[$db_type];
	}

	// }}}
	// {{{ public static function singleton_clear()

	/**
	 * 清除单件，多进程不能共享一个连接 
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
	// }}}
}
