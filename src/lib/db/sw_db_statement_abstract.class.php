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
* DB类中stmt抽象类
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_db_statement_abstract
{
	// {{{ members

	/**
	 * stmt对象 
	 * 
	 * @var stmt object
	 * @access protected
	 */
	protected $__stmt = null;

	/** 
	 * 
	 * @var sw_db_adapter_abstract
	 * @access protected
	 */
	protected $__adapter = null;
	
	/**
	 * __fetch_mode 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__fetch_mode = PDO::FETCH_ASSOC;

	/**
	 * 属性 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__attribute = array();

	/**
	 * 绑定的字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bind_column = array();

	/**
	 * 绑定的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bind_param = array();

	/**
	 * 以分隔符将sql语句分隔为数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__sql_split = array();

	/**
	 * SQL语句中对应的占位符参数值 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__sql_param = array();

	/** 
	 * 
	 * @var sw_db_profiler_query
	 * @access protected
	 */
	protected $__query_id = null;

	// }}}	
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * __construct 
	 * 
	 * @param sw_db_adapter_abstract $adapter 
	 * @param sw_db_select $sql 
	 * @access public
	 * @return void
	 */
	public function __construct($adapter, $sql)
	{
		$this->__adapter = $adapter;
		if ($sql instanceof sw_db_select) {
			$sql = $sql->assemble();	
		}
		$this->_parse_parameters($sql);
		$this->_prepare($sql);

		$this->__query_id = $this->__adapter->get_profiler()->query_start($sql);
	}

	// }}}
	// {{{ public function _parse_parameters()

	/**
	 * 从sql语句中提取绑定参数 
	 * 
	 * @param string $sql 
	 * @access public
	 * @return void
	 */
	public function _parse_parameters($sql)
	{
		$sql = $this->_strip_quoted($sql);
		
		//分隔占位符和参数
		$this->__sql_split = preg_split('/(\?|\:[a-zA-Z0-9_]+)/',
			$sql, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
		
		$this->__sql_param = array();
		foreach ($this->__sql_split as $key => $val) {
			if ($val == '?') {
				if ($this->__adapter->supports_parameters('positional') === false) {
					require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
					throw new sw_db_statement_exception("Invalid bind-variable position '$val'");	
				}
			} else if ($val[0] == ':') {
				if ($this->__adapter->supports_parameters('named') === false) {
					require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
					throw new sw_db_statement_exception("Invalid bind-variable position '$val'");	
				}	
			}
			$this->__sql_param[] = $val;
		}		

		$this->__bind_param = array();
	}

	// }}}
	// {{{ public function _strip_quoted()

	/**
	 * 去除sql语句的quote字符 
	 * 
	 * @param string $sql 
	 * @access public
	 * @return string
	 */
	public function _strip_quoted($sql)
	{
		//获取引用边界符，一般是双引号，mysql中是反引号
		$d = $this->__adapter->quote_indentifier('a');
		$d = $d[0];
		
		// get the value used as an escaped delimited id quote,
		// e.g. \" or "" or \`
		$de = $this->__adapter->quote_indentifier($d);
		$de = substr($qe, 1, 2);
		$qe = str_replace('\\', '\\\\', $qe);
		
		$sql = preg_replace("/$q($qe|\\\\{2}|[^$q])*$q/", '', $sql);
		if (!empty($q)) {
			$sql = preg_replace("/$q($qe|[^$q])*$q/", '', $sql);	
		}	

		return $sql;
	}

	// }}}
	// {{{ protected function _prepare()

	/**
	 * 预处理命令 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return void
	 * @throws sw_db_statement_exception
	 */
	protected function _prepare($sql)
	{
		try {
			$this->__stmt = $this->__adapter->get_connection()->prepare($sql);
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ public function bind_column()

	/**
	 * 绑定字段 
	 * 
	 * @param string $column 
	 * @param mixed $param 
	 * @param integer $type 
	 * @access public
	 * @return boolean
	 * @throws sw_db_statement_exception
	 */
	public function bind_column($column, &$param, $type = null)
	{
		try {
			if ($type === null) {
				return $this->__stmt->bindColumn($column, $param);	
			} else {
				return $this->__stmt->bindColumn($column, $param, $type);	
			}
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ public function bind_param()

	/**
	 * 绑定参数 
	 * 
	 * @param integer| string $parameter 
	 * @param mixed $variable 
	 * @param mixed $type 
	 * @param mixed $length 
	 * @param mixed $options 
	 * @access public
	 * @return boolean
	 */
	public function bind_param($parameter, &$variable, $type = null, $length = null, $options = null)
	{
		if (!is_int($parameter) && !is_string($parameter)) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception('Invalid bind-variable position');	
		}

		$position = null;
		if (($intval = (int) $parameter) > 0 && $this->__adapter->supports_parameters('positional')) {
			if ($intval >= 1 || $intval <= count($this->__sql_param)) {
				$position = $intval;	
			}
		} else if ($this->__adapter->supports_parameters('named')) {
			if ($parameter[0] != ':') {
				$parameter = ':' . $parameter;	
			}	
			if (in_array($parameter, $this->__sql_param) !== false) {
				$position = $parameter;	
			}
		}

		if ($position === null) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception("Invalid bind-variable position '$parameter'");		
		}

		$this->__bind_param[$position] =& $variable;
		return $this->_bind_param($position, $variable, $type, $length, $options);
	}

	// }}}
	// {{{ protected function _bind_param()

	/**
	 * _bind_param 
	 * 
	 * @param mixed $parameter 
	 * @param mixed $variable 
	 * @param mixed $type 
	 * @param mixed $length 
	 * @param mixed $options 
	 * @access protected
	 * @return boolean
	 * @throws sw_db_statement_exception
	 */
	protected function _bind_param($parameter, &$variable, $type = null, $length = null, $options = null) {
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
			return $this->__stmt->bindParam($parameter, $variable, $type, $length, $options);
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ public function bind_value()

	/**
	 * 直接为占位符绑定值 
	 * 
	 * @param mixed $parameter 
	 * @param mixed $value 
	 * @param mixed $type 
	 * @access public
	 * @return boolean
	 * @throws sw_db_statement_exception
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
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ public function execute()

	/**
	 * 执行sql语句 
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
		$qp = $prof->get_query_profiler($this->__query_id);
		if ($qp->has_ended()) {
			$this->__query_id = $prof->query_clone($qp);
			$qp = $prof->get_query_profiler($this->__query_id);	
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
	// {{{ public function fetch()

	/**
	 * fetch 
	 * 
	 * @param int $style 
	 * @param int $cursor 
	 * @param int $offset 
	 * @access public
	 * @return array|object
	 * @throws sw_db_statement_exception
	 */
	public function fetch($style = null, $cursor = null, $offset = null)
	{
		if ($style === null) {
			$style = $this->__fetch_mode;	
		}	
		try {
			return $this->__stmt->fetch($style, $cursor, $offset);	
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ public function fetch_all()

	/**
	 * fetch_all 
	 * 
	 * @param mixed $style 
	 * @param mixed $col 
	 * @access public
	 * @return array
	 * @throws sw_db_statement_exception
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
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
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
	 * @throws sw_db_statement_exception
	 */
	public function fetch_column($col = 0)
	{
		try {
			return $this->__stmt->fetchColumn($col);	
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function fetch_object()

	/**
	 * fetch_object 
	 * 
	 * @param string $class 
	 * @param array $config 
	 * @access public
	 * @return object
	 * @throw sw_db_statement_exception
	 */
	public function fetch_object($class = 'stdClass', array $config = array())
	{
		try {
			return $this->__stmt->fetchObject($class, $config);	
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ pulic function get_attribute()

	/**
	 * 获取属性值 
	 * 
	 * @param string $key 
	 * @access public
	 * @return mixed
	 * @throws sw_db_statement_exception
	 */
	public function get_attribute($key)
	{
		try {
			return $this->__stmt->getAttribute($key);	
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';	
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function set_attribute()

	/**
	 * 设置stmt属性 
	 * 
	 * @param string $key 
	 * @param mixed $val 
	 * @access public
	 * @return bool
	 */
	public function set_attribute($key, $val)
	{
		try {
			return $this->__stmt->setAttribute($key);	
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';	
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function set_fetch_mode()

	/**
	 * 设置fetch模式 
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
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';	
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ public function _fetch_bound()

	/**
	 * _fetch_bound 
	 * 
	 * @param array $row 
	 * @access public
	 * @return boolean
	 */
	public function _fetch_bound($row)
	{
		foreach ($row as $key => $value) {
			if (is_int($key)) {
				$key++;	
			}
			if (isset($this->__bind_column[$key])) {
				$this->__bind_column[$key] = $value;	
			}
		}
		return true;
	}

	// }}}
	// {{{ public function get_adapter()

	/** 
	 * 
	 * @access public
	 * @return sw_db_adapter_abstract
	 */
	public function get_adapter()
	{
		return $this->__adapter;	
	}

	// }}}
	// {{{ public function get_driver_statement()

	/**
	 * get_driver_statement 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_driver_statement()
	{
		return $this->__stmt;	
	}

	// }}}
	// {{{ public function close_cursor()

	/**
	 * close_cursor 
	 * 
	 * @access public
	 * @return boolean
	 * @throws sw_db_statement_exception
	 */
	public function close_cursor()
	{
		try {
			return $this->__stmt->closeCursor();	
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ public function column_count()

	/**
	 * 获得结果集中的记录字段数 
	 * 
	 * @access public
	 * @return integer
	 * @throws sw_db_statement_exception
	 */
	public function column_count()
	{
		try {
			return $this->__stmt->columnCount();	
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ public function error_code()

	/**
	 * 获取stmt对象的错误码 
	 * 
	 * @access public
	 * @return string
	 * @throws sw_db_statement_exception
	 */
	public function error_code()
	{
		try {
			return $this->__stmt->errorCode();
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ public function error_info()

	/**
	 * 获取stmt对象的错误信息
	 * 
	 * @access public
	 * @return string
	 * @throws sw_db_statement_exception
	 */
	public function error_info()
	{
		try {
			return $this->__stmt->errorInfo();
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ protected function _execute()

	/**
	 * 执行 
	 * 
	 * @param array $params 
	 * @access protected
	 * @return boolean
	 * @throws sw_db_statement_exception
	 */
	protected function _execute(array $params = null)
	{
		try {	
			if ($params !== null) {
				return $this->__stmt->execute($params);	
			} else {
				return $this->__stmt->exeute();	
			}
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception($e->getMessage(), (int) $e->getCode(), $e);	
		}
	}

	// }}}
	// {{{ pulic function get_column_meta()

	/**
	 * 获取COLUMN_META
	 * 
	 * @param int $column 
	 * @access public
	 * @return mixed
	 * @throws sw_db_statement_exception
	 */
	public function get_column_meta($column)
	{
		try {
			return $this->__stmt->getColumnMeta($column);	
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';	
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ pulic function next_row_set()

	/**
	 * 
	 * @access public
	 * @return boolean
	 * @throws sw_db_statement_exception
	 */
	public function next_row_set()
	{
		try {
			return $this->__stmt->nextRowset();	
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';	
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// {{{ pulic function row_count()

	/**
	 * 
	 * @access public
	 * @return int
	 * @throws sw_db_statement_exception
	 */
	public function row_count()
	{
		try {
			return $this->__stmt->rowCount();	
		} catch (PDOException $e) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';	
			throw new sw_db_statement_exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	// }}}
	// }}} end functions
}
