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
 
namespace lib\db\adapter;
use lib\db\adapter\sw_abstract as sw_abstract;
/**
+------------------------------------------------------------------------------
* sw_mysql 
+------------------------------------------------------------------------------
* 
* @package lib
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_mysql extends sw_abstract
{
	// {{{ members

	/**
	 * PDO 驱动的类型 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__pdo_type = 'mysql';

	// }}}
	// {{{ functions
	// {{{ protected function _dsn()

	/**
	 * 生成连接数据库的 dsn 字符串 
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _dsn()
	{
		$dsn = $this->__config;
		
		$dsn_arr = array();
		if (isset($dsn['unix_socket']) && '' !== $dsn['unix_socket']) {
			$dsn_arr['unix_socket'] = 'unix_socket=' . $dsn['unix_socket'];
		} else if (isset($dsn['host'])) {
			$dsn_arr['host'] = 'host=' . $dsn['host'];	
		}

		if (isset($dsn['dbname']) && '' !== $dsn['dbname']) {
			$dsn_arr['dbname'] = 'dbname=' . $dsn['dbname'];	
		}

		return $this->__pdo_type . ':' . implode(';', $dsn_arr);
	}

	// }}}
	// {{{ public function get_quote_indentifier_symbol()

	/**
	 * 获取标识符 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_quote_indentifier_symbol()
	{
		return '`';	
	}

	// }}}
	// }}}	
}
