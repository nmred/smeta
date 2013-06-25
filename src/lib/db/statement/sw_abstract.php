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
 
namespace lib\db\statement;
use lib\db\sw_db;
use lib\db\profiler\sw_profiler;
use lib\db\select\sw_select;
use lib\db\sw_db_expr;
use lib\db\statement\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_abstract 
+------------------------------------------------------------------------------
* 
* @package lib
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_abstract
{
	// {{{ members

	/**
	 * 数据库操作适配器 
	 * 
	 * @var lib\db\adapter\sw_abstract
	 * @access protected
	 */
	protected $__adapter = null;

	/**
	 * 查询日志对象 
	 * 
	 * @var lib\db\profiler\sw_profiler_query
	 * @access protected
	 */
	protected $__query_id = null;

	/**
	 * 预处理对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__stmt = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($adapter, $sql)
	{
		$this->__adapter = $adapter;
		if ($sql instanceof sw_select) {
			$sql = $sql->assemble();		
		}
		$this->_prepare($sql);

		$this->__query_id = $this->__adapter->get_profiler()->query_start($sql);
	}

	// }}}
	// {{{ protected function _prepare()

	/**
	 * 预处理命令 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return sw_abstract
	 */
	protected function _prepare($sql)
	{
		try {
			$this->__stmt = $this->__adapter->get_connection()->prepare($sql);	
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}	
	}

	// }}}
	// {{{ public function bind_column()

	/**
	 * 绑定查询字段 
	 * 
	 * @param string $column 
	 * @param mixed $param 
	 * @param integer $type 
	 * @access public
	 * @return boolean
	 */
	public function bind_column($column, &$param, $type = null)
	{
		try {
			if (null === $type) {
				return $this->__stmt->bindColumn($column, $param);	
			} else {
				return $this->__stmt->bindColumn($column, $param, $type);	
			}
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function bind_param()

	/**
	 * 绑定参数 
	 * 
	 * @param mixed $parameter 
	 * @param mixed $variable 
	 * @param mixed $type 
	 * @param integer $length 
	 * @param mixed $options 
	 * @access public
	 * @return boolean
	 */
	public function bind_param($parameter, &$variable, $type = null, $length = null, $options = null)
	{
		$this->__bind_param[$parameter] = &$variable;
		try {
			if ($type === null) {
				if (is_bool($variable)) {
					$type = PDO::PARAM_BOOL;	
				} elseif ($variable === null) {
					$type = PDO::PARAM_NULL;	
				} elseif (is_integer($variable)) {
					$type = PDO::PARAM_INT;	
				} else {
					$type = PDO::PARAM_STR;	
				}
			}

			return $this->__stmt->bindParam($parameter, $variable, $type, $length);
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}
	}
	// }}}
	// {{{ public function bind_value()

	/**
	 * 直接为占位符绑定值，推荐用 bind_param 
	 * 
	 * @param mixed $parameter 
	 * @param mixed $value 
	 * @param mixed $type 
	 * @access public
	 * @return void
	 */
	public function bind_value($parameter, $value, $type = null)
	{
		if (is_string($parameter) && $parameter[0] != ':') {
			$parameter = ":$parameter";	
		}

		$this->__bind_param[$parameter] = $value;

		try {
			if ($type === null) {
				return $this->__stmt->bindValue($parameter, $value);	
			} else {
				return $this->__stmt->bindValue($parameter, $value, $type);	
			}
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// }}}
}
