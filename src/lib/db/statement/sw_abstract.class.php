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
use PDO;

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

	/**
	 * 绑定的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bind_param = array();

	/**
	 * 获取数据集模式 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__fetch_mode = PDO::FETCH_ASSOC;

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
	public function bind_param($parameter, &$variable = null, $type = null, $length = null, $options = null)
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
	// {{{ public function execute()

	/**
	 * 执行 sql 语言 
	 * 
	 * @param array $params 
	 * @access public
	 * @return boolean
	 */
	public function execute(array $params = null)
	{
		if ($this->__query_id === null) {
			return $this->_execute($params);	
		}
		
		$prof = $this->__adapter->get_profiler();
		$qp = $prof->get_query_profile($this->__query_id);
		if ($qp->has_ended()) {
			$this->__query_id = $prof->query_clone($qp);	
			$qp = $prof->get_query_profile($this->__query_id);
		}

		if ($params !== null) {
			$qp->bind_params($params);	
		} else {
			$qp->bind_params($this->__bind_param);	
		}

		$qp->start($this->__query_id);

		$retval = $this->_execute($params);

		$prof->query_end($this->__query_id);

		return $retval;
	}

	// }}}
	// {{{ protected function _execute()

	/**
	 * execute 
	 * 
	 * @param array $params 
	 * @access protected
	 * @return void
	 */
	protected function _execute(array $params = null)
	{
		try {
			if (null === $params) {
				$this->__stmt->execute();
			} else {
				$this->__stmt->execute($params);
			}
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function fetch()

	/**
	 * 获取数据集 
	 * 
	 * @param mixed $style 
	 * @param mixed $offset 
	 * @access public
	 * @return void
	 */
	public function fetch($style = null, $cursor = null, $offset = null)
	{
		if ($style === null) {
			$style = $this->__fetch_mode;	
		}

		try {
			return $this->__stmt->fetch($style, $cursor, $offset);	
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function fetch_all()

	/**
	 * 遍历所有的数据集 
	 * 
	 * @param mixed $style 
	 * @param mixed $col 
	 * @access public
	 * @return array
	 */
	public function fetch_all($style = null, $col = null)
	{
		if ($style === null) {
			$style = $this->__fetch_mode;	
		}

		try {
			if ($style == PDO::FETCH_COLUMN) {
				if ($col === null) {
					$col = 0;	
				}	

				return $this->__stmt->fetchAll($style, $col);
			} else {
				return $this->__stmt->fetchAll($style);	
			}
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ public function fetch_column()
	
	/**
	 * 获取一行中的指定的一列 
	 * 
	 * @param int $col 
	 * @access public
	 * @return string
	 */
	public function fetch_column($col = 0)
	{
		try {
			return $this->__stmt->fetchColumn($col);	
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function get_attribute()

	/**
	 * 获取属性值 
	 * 
	 * @param string $key 
	 * @access public
	 * @return mixed
	 */
	public function get_attribute($key)
	{
		try {
			return $this->__stmt->getAttribute($key);	
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function set_attribute()

	/**
	 * 设置属性值 
	 * 
	 * @param string $key 
	 * @param mixed $val 
	 * @access public
	 * @return boolean
	 */
	public function set_attribute($key, $val)
	{
		try {
			return $this->__stmt->setAttribute($key, $val);	
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function set_fetch_mode()

	/**
	 * 设置 fetch 的模式 
	 * 
	 * @param integer $mode 
	 * @access public
	 * @return void
	 */
	public function set_fetch_mode($mode)
	{
		$this->__fetch_mode = $mode;
		try {
			return $this->__stmt->setFetchMode($mode);
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function get_adapter()

	/**
	 * 获取适配器对象 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_adapter()
	{
		return $this->__adapter;	
	}

	// }}}
	// {{{ public function get_stmt()

	/**
	 * 获取 stmt 对象 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_stmt()
	{
		return $this->__stmt;	
	}

	// }}}
	// {{{ public function column_count()

	/**
	 * 获得结果集的记录字段数 
	 * 
	 * @access public
	 * @return integer
	 */
	public function column_count()
	{
		try {
			return $this->__stmt->columnCount();	
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ 
	// }}}
}
