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
use lib\db\sw_db as sw_db;
use lib\config\sw_config as sw_config;
use lib\db\profiler\sw_profiler as sw_profiler;
use lib\db\select\sw_select as sw_select;
use lib\db\sw_db_expr as sw_expr;
use lib\db\adapter\exception\sw_exception as sw_exception;

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
class sw_abstract
{
	// {{{ members

	/**
	 * 连接数据库的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__config = array();

	/**
	 *  SQL 语句操作分析器对象 
	 * 
	 * @var lib\db\profiler\sw_profiler
	 * @access protected
	 */
	protected $__profiler = null;

	/**
	 * 连接数据库的类型 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__pdo_type;

	/**
	 * PDO 对象 
	 * 
	 * @var PDO
	 * @access protected
	 */
	protected $__connection = null;

	/**
	 * SQL 语句列名的显示方式 
	 *  
	 * sw_db::CASE_NATURAL,sw_db::CASE_LOWER,sw_db::CASE_UPPER 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__case_folding = sw_db::CASE_NATURAL;

	/**
	 * 执行 quote 操作的类型 map 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__numeric_data_types = array(
		sw_db::INT_TYPE    => sw_db::INT_TYPE,
		sw_db::BIGINT_TYPE => sw_db::BIGINT_TYPE,
		sw_db::FLOAT_TYPE  => sw_db::FLOAT_TYPE,
	);

	/**
	 * 默认 stmt 处理对象 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_stmt_class = 'lib\db\statement\sw_standard';

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param array $config 
	 * @access public
	 * @return void
	 */
	public function __construct($config = null)
	{
		$config_default = sw_config::get_config('db');
		if (!isset($config) || is_array($config)) {
			$this->__config = array_merge($config_default, (array) $config);
			$this->_check_required_options($this->__config);	
		} else {
			throw new sw_exception('config param must is array');	
		}	

		// 开启 SQL 分析器
		$this->set_profiler(true);
	}

	// }}}
	// {{{ protected function _check_required_options()

	/**
	 * 创建对象时对参数进行必要的检测
	 * 
	 * @param array $config 
	 * @access protected
	 * @return void
	 * @throws lib\db\adapter\exception\sw_exception
	 */
	protected function _check_required_options(array $config)
	{
		if (!array_key_exists('dbname', $config)) {
			throw new sw_exception('Configuration array must have a key for `dbname` that names the database instance');	
		}

		if (!array_key_exists('username', $config)) {
			throw new sw_exception('Configuration array must have a key for `username` that names the database instance');	
		}

		if (!array_key_exists('password', $config)) {
			throw new sw_exception('Configuration array must have a key for `password` that names the database instance');	
		}
	}

	// }}}
	// {{{ public function set_profiler()

	/**
	 * 设置和创建 SQL 分析器对象 
	 * 
	 * @param boolean $enable 
	 * @access public
	 * @return lib\db\adapter\sw_abstract
	 */
	public function set_profiler($enable = false)
	{
		if (!isset($this->__profiler)) {
			$this->__profiler = new sw_profiler($enable);	
			return $this;
		}

		$this->__profiler->set_enabled($enable);
		return $this;
	}

	// }}}
	// {{{ public function get_profiler()

	/**
	 *  获取 SQL 分析器对象 
	 * 
	 * @access public
	 * @return lib\db\profiler\sw_profiler
	 */
	public function get_profiler()
	{
		return $this->__profiler;	
	}

	// }}}
	// {{{ public function get_statement_class()

	/**
	 * 获取 statement 处理类名 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_statement_class()
	{
		return $this->__default_stmt_class;	
	}

	// }}}
	// {{{ public function set_statement_class()

	/**
	 * 设置 statement 处理类名 
	 * 
	 * @params string $class
	 * @access public
	 * @return lib\db\adpater\sw_abstract
	 */
	public function set_statement_class($class)
	{
		$this->__default_stmt_class = $class;
		return $this;
	}

	// }}}
	// {{{ pprotected function _connect()

	/**
	 * 连接数据库 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _connect()
	{
		if ($this->__connection) {
			return;	
		}	

		$dsn = $this->_dsn();
		
		if (!extension_loaded('pdo')) {
			throw new sw_exception('The PDO extension is required for this adapter but the extension is not loaded');
		}

		// 检查 PDO 驱动是否存在
		if (!in_array($this->__pdo_type, \PDO::getAvailableDrivers())) {
			throw new sw_exception('The ' . $this->__pdo_type . ' driver is not currently installed');	
		}

		$q = $this->__profiler->query_start('connect', sw_profiler::CONNECT);
		if (isset($this->__config['persistent']) && ($this->__config['persistent'] == true)) {
			$this->__config['driver_options'][\PDO::ATTR_PERSISTENT] = true;	
		}

		try {
			$this->__connection = new \PDO(
				$dsn,
				$this->__config['username'],
				$this->__config['password'],
				$this->__config['driver_options']
			);

			$this->__profiler->query_end($q);
			$this->__connection->setAttribute(\PDO::ATTR_CASE, $this->__case_folding);
			$this->__connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			throw new sw_exception($e->getMessage(), $e->getCode(), $e);
		} 
	}

	// }}}
	// {{{ public function get_connection()
	
	/**
	 * 获取数据库连接 
	 * 
	 * @access public
	 * @return PDO
	 */
	public function get_connection()
	{
		$this->_connect();
		return $this->__connection;	
	}

	// }}} 
	// {{{ public function is_connected()

	/**
	 * 判断是否连接上数据库 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_connected()
	{
		return ((bool) ($this->__connection instanceof \PDO));
	}

	// }}}
	// {{{ public function close_connection()

	/**
	 * 关闭连接 
	 * 
	 * @access public
	 * @return void
	 */
	public function close_connection()
	{
		$this->__connection = null;	
	}

	// }}}
	// {{{ public function get_config()
	
	/**
	 * 获取配置 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_config()
	{
		return $this->__config;	
	}

	// }}}
	// {{{ public function begin_transaction()

	/**
	 * 开启事务 
	 * 
	 * @access public
	 * @return lib\db\adpater\sw_abstract
	 */
	public function begin_transaction()
	{
		$this->_connect();
		$q = $this->__profiler->query_start('begin', sw_profiler::TRANSACTION);
		$this->_begin_transaction();
		$this->__profiler->query_end($q);
		return $this;	
	}

	// }}}
	// {{{ public function commit()

	/**
	 * 事务提交操作 
	 * 
	 * @access public
	 * @return lib\db\adapter\sw_abstract
	 */
	public function commit()
	{
		$this->_connect();
		$q = $this->__profiler->query_start('commit', sw_profiler::TRANSACTION);
		$this->_commit();
		$this->__profiler->query_end($q);
		return $this;	
	}

	// }}}
	// {{{ public function rollback()

	/**
	 * 回滚事务 
	 * 
	 * @access public
	 * @return lib\db\adapter\sw_abstract
	 */
	public function rollback()
	{
		$this->_connect();	
		$q = $this->__profiler->query_start('rollback', sw_profiler::TRANSACTION);
		$this->_rollback();
		$this->__profiler->query_end($q);
		return $this;
	}

	// }}}
	// {{{ protected function _begin_transaction()

	/**
	 * 开启事务 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _begin_transaction()
	{
		$this->_connect();
		$this->__connection->beginTransaction();	
	}

	// }}}
	// {{{ protected function _commit()

	/**
	 * 开启事务 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _commit()
	{
		$this->_connect();
		$this->__connection->commit();	
	}

	// }}}
	//adapter {{{ protected function _rollback()
	
	/**
	 * 事务回滚 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _rollback()
	{
		$this->_connect();
		$this->__connection->rollBack();	
	}

	// }}}
	// {{{ protected function _quote()

	/**
	 * 转义 SQL 语句 
	 * 
	 * @param mixed $value 
	 * @access protected
	 * @return mixed
	 */
	protected function _quote($value)
	{
		if (is_int($value) || is_float($value)) {
			return $value;	
		}	
		$this->_connect();
		return $this->__connection->quote($value);
	}

	// }}}
	// {{{ public function quote()

	/**
	 * 执行转义操作 防止攻击数据库
	 * <code>
	 * $db->quote('asasasa\'sasass');
	 * 返回 asasasa\'sasass
	 * $db->quote(array('a', 'b', 'c'));
	 * 返回 "a, b, c"
	 * </code> 
	 * @param mixed $value 
	 * @param int $type //转义的数据类型 
	 * @access public
	 * @return mixed
	 */
	public function quote($value, $type = null)
	{
		$this->_connect();
		
		if ($value instanceof sw_select) {
			return '(' . $value->assemble() . ')';			
		}
		
		if ($value instanceof sw_expr) {
			return $value->__toString();	
		}	

		if (is_array($value)) {
			foreach ($value as &$val) {
				$val = $this->quote($val, $type);	
			}	
			return implode(', ', $value);
		}

		if ($type !== null && array_key_exists($type, $this->__numeric_data_types)) {
			$quoted_value = '0';
			switch ($this->__numeric_data_types[$type]) {
				case sw_db::INT_TYPE: // 32-bit int
					$quoted_value = (string) intval($value);
					break;	

				case sw_db::BIGINT_TYPE: // 64-bit int
					if (preg_match('/^(
						[+-]?
						(?:
							0[Xx][\da-fA-F]+
							|\d+
							(?:[eE][+-]?\d+)?
						)
					)/x', $value, $matches)) {
					$quoted_value = $matches[1];
					}
					break;
				case sw_db::FLOAT_TYPE:
					$quoted_value = sprintf('%F', $value);
					break;
			}
			return $quoted_value;
		}

		return $this->_quote($value);
	}

	// }}}
	// {{{ public function quote_into()

	/**
	 * 对占位符替换成安全的值 
	 * <code>
	 * $text = "WHERE date < ?";
	 * $date = ';2012-01-12';
	 * $safe = $sql->quote_into($text, $data);
	 * 返回： WHERE date < '2012-01-12';
	 * </code>
	 * @param string $text 
	 * @param mixed $value 
	 * @param mixed $type 
	 * @param integer $count 
	 * @access public
	 * @return string
	 */
	public function quote_into($text, $value, $type = null, $count = null)
	{
		if ($count === null) {
			return str_replace('?', $this->quote($value, $type), $text);
		} else {
			while ($count > 0) {
				if (strpos($text, '?') !== false) {
					$text = substr_replace($text, $this->quote($value, $type), strpos($text, '?'), 1);	
				}
				--$count;
			}	
			return $text;
		}	
	}

	// }}}
	// {{{ public function quote_table_as()

	/**
	 * 为表名添加标识符 
	 * 
	 * @param string|array|sw_expr $ident 
	 * @param string $alias 
	 * @param boolean $auto 
	 * @access public
	 * @return string
	 */
	public function quote_table_as($ident, $alias = null, $auto = false)
	{
		return $this->_quote_identifier_as($ident, $alias, $auto);
	}

	// }}}
	// {{{ public function quote_column_as()

	/**
	 * 为列名添加标识符 
	 * 
	 * @param string|array|sw_expr $ident 
	 * @param string $alias 
	 * @param boolean $auto 
	 * @access public
	 * @return string
	 */
	public function quote_column_as($ident, $alias = null, $auto = false)
	{
		return $this->_quote_identifier_as($ident, $alias, $auto);
	}

	// }}}
	// {{{ public function quote_indentifier()

	/**
	 * 添加标识符 
	 * 
	 * @param string|array|sw_expr $ident 
	 * @param boolean $auto 
	 * @access public
	 * @return string
	 */
	public function quote_indentifier($ident, $auto = false)
	{
		return $this->_quote_identifier_as($ident, null, $auto);
	}

	// }}}
	// {{{ protected function _quote_identifier()

	/**
	 * 将一个字符串两端加标识符 
	 * 
	 * @param string $value 
	 * @param boolean $auto 
	 * @access protected
	 * @return string
	 */
	protected function _quote_identifier($value, $auto = false)
	{
		if ($auto === false || $this->__auto_quote_indentifiers === true) {
			$q = $this->get_quote_indentifier_symbol();
			return ($q . str_replace("$q", "$q$q", $value) . $q);	
		}	
		
		return $value;
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
		return '"';	
	}

	// }}}
	// {{{ protected function _quote_identifier_as()

	/**
	 * 添加标识符 
	 * 
	 * @param mixed $ident 
	 * @param string $alias 
	 * @param boolean $auto 
	 * @param string $as 
	 * @access protected
	 * @return string
	 */
	protected function _quote_identifier_as($ident, $alias = null, $auto = false, $as = ' AS ')
	{
		if ($ident instanceof sw_expr) {
			$quoted = $ident->__toString();	
		} else if ($ident instanceof sw_select) {
			$quoted = '(' . $ident->assemble() . ')';	
		} else {
			if (is_string($ident)) {
				$ident = explode('.', $ident);	
			}

			if (is_array($ident)) {
				$segments = array();
				foreach ($ident as $segment) {
					if ($segment instanceof sw_expr) {
						$segments[] = $segment->__toString();	
					} else {
						$segments[] = $this->_quote_identifier($segment, $auto);	
					}
				}

				if ($alias !== null && end($ident) == $alias) {
					$alias = null;	
				}
				$quoted = implode('.', $segments);
			} else {
				$quoted = $this->_quote_identifier($ident, $auto);	
			}
		}

		if (null !== $alias) {
			$quoted .= $as . $this->_quote_identifier($alias, $auto);	
		}
		return $quoted;
	}

	// }}}
	// {{{ public function fold_case()

	/**
	 * 转换大小写 
	 * 
	 * @param string $key 
	 * @access public
	 * @return string
	 */
	public function fold_case($key)
	{
		var_dump($this->__case_folding);
		var_dump(sw_db::CASE_LOWER);
		switch ($this->__case_folding) {
			case sw_db::CASE_LOWER:
				$value = strtolower((string) $key);
				break;
			case sw_db::CASE_UPPER:
				$value = strtoupper((string) $key);
				break;
			case sw_db::CASE_NATURAL:
			default:
				$value = (string) $key;
		}	

		return $value;
	}

	// }}}
	// {{{ public function supports_parameters()

	/**
	 * 判断某种绑定方式是否支持 
	 * 
	 * @param string $type 
	 * @access public
	 * @return boolean
	 */
	public function supports_parameters($type)
	{
		$supports = array ('named', 'positional');
		return in_array($type, $supports);
	}

	// }}}
	// {{{ public function get_server_version()

	/**
	 * 获取服务器的版本 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_server_version()
	{
		$this->_connect();
		try {
			$version = $this->__connection->getAttribute(\PDO::ATTR_SERVER_VERSION);	
		} catch (PDOException $e) {
			return null;	
		}
		$matches = null;
		if (preg_match('/((?:[0-9]{1,2}\.){1,3}[0-9]{1,2})/', $version, $matches)) {
			return $matches[1];
		} else {
			return null;	
		}
	}

	// }}}
	// }}}	
}
