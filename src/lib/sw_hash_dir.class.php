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
* 生成 hash dir 的类
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_hash_dir
{
	// {{{ const

	/**
	 * hash dir 的最小等级
	 *
	 * @const
	 */
	const HASH_LEVEL_MIN = 1;

	/**
	 * hash dir 的最大等级
	 *
	 * @const
	 */
	const HASH_LEVEL_MAX = 8;

	/**
	 * hash dir 的默认等级
	 *
	 * @const
	 */
	const HASH_LEVEL_DEFAULT = 2;

	// }}}
	// {{{ public static function get_hash_dir()

	/**
	 * 获取一个字符串的 hash 目录 
	 * 
	 * @param string $key
	 * @param intger $level
	 * @access public
	 * @return string
	 */
	public static function get_hash_dir($key, $level = null)
	{
		$level = self::get_hash_level($level);
		$hash_str = hash('crc32b', $key);

		for ($i = 0; $i < $level; $i++) {
			$hash_dir[] = substr($hash_str, $i, 1);	
		}

		return implode("/", $hash_dir) . "/";
	}

	// }}}
	// {{{ public static function get_hash_level()

	/**
	 * 获取一个 hash dir 的等级 
	 * 
	 * @param intger $level
	 * @access public
	 * @return intger
	 */
	public static function get_hash_level($level = null)
	{
		if (!isset($level) || $level < self::HASH_LEVEL_MIN || $level > self::HASH_LEVEL_MAX) {
			$level = self::HASH_LEVEL_DEFAULT;	
		}

		return $level;
	}

	// }}}
	// {{{ public static function make_hash_dir()

	/**
	 * 创建一个 hash dir
	 * 
	 * @param string $path_base 
	 * @param string $path_hash 
	 * @param boolean $is_return 
	 * @static
	 * @access public
	 * @return boolean
	 */
	public static function make_hash_dir($path_base, $path_hash, $is_return = true)
	{
		if ($is_return && !is_dir($path_base)) {
			return false;	
		}

		$path = $path_base . '/' . $path_hash;

		if (is_dir($path)) {
			return true;	
		}

		// 设置空的错误处理屏蔽错误

		set_error_handler('func_enjoy');

		$old_mask = umask(0);
		mkdir($path, 0777, true);
		umask($old_mask);

		// 恢复错误处理
		restore_error_handler();

		return $is_return ? !!is_dir($path) : true;
	}

	// }}}
}
