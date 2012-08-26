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
 
require_once PATH_SWAN_LIB . 'sw_db.class.php';
require_once PATH_SWAN_LIB . 'db/sw_db_select.class.php';
/**
+------------------------------------------------------------------------------
* sw_db_adapter_abstract 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_db_adapter_abstract
{
	// {{{ members

	/**
	 * 用户提供的配置 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__config = array();

	/**
	 * fetch的方式 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__fetch_mode = PDO::FETCH_ASSOC;

	/**
	 * 查询分析器，类型是sw_db_profiler或其子类 
	 * 
	 * @var sw_db_profiler
	 * @access protected
	 */
	protected $__profiler;

	/**
	 * 默认的pdo返回的statement对象类名 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_stmt_class = 'sw_db_statement';

	/**
	 * 默认查询分析器对象的类名 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_profiler_class = 'sw_db_profiler';

	/**
	 * 数据库连接对象 
	 * 
	 * @var object|resource|null
	 * @access protected
	 */
	protected $__connection = null;

	/**
	 * 查询时指定列名得方式
	 * Options
	 * PDO::CASE_NATURAL
	 * PDO::CASE_LOWER 
	 * PDO::CASE_UPPER 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__case_folding = PDO::CASE_NATURAL;

	/**
	 * 指定是否自动为SQL标识符添加引号 
	 * 如果指定true，所有的SQL语句将自动添加引号在标识符上，如果false则需要调用quote_identifier()
	 * 方法
	 *
	 * @var bool
	 * @access protected
	 */
	protected $__auto_quote_indentifiers = true;

	/**
	 * DB的数字类型
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__numeric_data_types = array(
		sw_db::INI_TYPE    => sw_db::INI_TYPE,
		sw_db::BIGINT_TYPE => sw_db::BIGINT_TYPE,
		sw_db::FLOAT_TYPE  => sw_db::FLOAT_TYPE,
	);	

	/**
	 * 是否允许对象序列化 
	 * 
	 * @var bool
	 * @access protected
	 */
	protected $__allow_serialization = true;

	/**
	 * 当反序列化后是否允许自动重新连接数据库 
	 * 
	 * @var bool
	 * @access protected
	 */
	protected $__auto_reconnect_on_unserialize = false;

	// }}}	
	// {{{ functions
	// {{{ protected function _check_required_options()

	/**
	 * 检查必要的参数 
	 * 
	 * @param array $config 
	 * @access protected
	 * @throws sw_db_adapter_exception
	 */
	protected function _check_required_options(array $config)
	{
		if (!array_key_exists('dbname', $config)) {
			require_once PATH_SWAN_LIB 'db/sw_db_adapter_exception.class.php';
			throw new sw_db_adapter_exception('Configuration array must have a key for `dbname` tha  t names the database instance');
		}		

		if (!array_key_exists('password', $config)) {
			require_once PATH_SWAN_LIB 'db/sw_db_adapter_exception.class.php';
			throw new sw_db_adapter_exception('Configuration array must have a key for `password` tha  t names the database instance');
		}		

		if (!array_key_exists('username', $config)) {
			require_once PATH_SWAN_LIB 'db/sw_db_adapter_exception.class.php';
			throw new sw_db_adapter_exception('Configuration array must have a key for `username` tha  t names the database instance');
		}		
	}

	// }}}
	// {{{ public function get_connection()

	/**
	 * 获取数据库底层连接对象或资源 
	 * 
	 * @access public
	 * @return object|resource|null
	 */
	public function get_connection()
	{
		$this->_connect();
		return $this->__connection;
	}

	// }}}
	// {{{ public function get_config()

	/**
	 * 获取配置参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_config()
	{
		return $this->__config;	
	}

	// }}}
	// {{{ public function set_profiler()

	/**
	 * 设置SQL分析器对象 
	 * 
	 * $profiler 可以是一个sw_db_profiler,布尔型的设置分析器是否开启，或数组
	 * 数组格式：
	 * array(
	 *	'enablde'  => true|false,
	 *  'class'    => ,
	 *  'instance' =>
	 * );
	 * @param mixed $profiler 
	 * @access public
	 * @return sw_db_adapter_abstract
	 * @throws sw_db_profiler_exception
	 */
	public function set_profiler($profiler)
	{
		$enabled           = null;
		$profiler_class    = $this->__default_profiler_class;
		$profiler_instance = null;

		if ($profiler_is_object = is_object($profiler) && $profiler instanceof sw_db_profiler) {
			$profiler_instance = $profiler;
		} else {
			require_once PATH_SWAN_LIB . 'db/sw_db_profiler_exception.class.php';
			throw new sw_db_profiler_exception("Profiler argument must be an instance of sw_db_profiler as an object");
		}

		if (is_array($profiler)) {
			if (isset($profiler['enabled'])) {
				$enabled = (bool) $profiler['enabled'];	
			}
			if (isset($profiler['class'])) {
				$profiler_class = $profiler['class'];	
			}
			if (isset($profiler['instance'])) {
				$profiler_instance = $profiler['instance'];	
			} 
		} else if (!$profiler_is_object) {
			$enabled = (bool) $profiler;	
		}

		if ($profiler_instance === null) {
			if (!class_exists($profiler_class))	{
				require_once PATH_SWAN_LIB . 'db/' . $profiler_class . '.class.php';
			}
			$profiler_instance = new $profiler_class();
		}

		if (!$profiler_instance instanceof sw_db_profiler) {
			require_once PATH_SWAN_LIB . 'db/sw_db_profiler_exception.class.php';
			throw new sw_db_profiler_exception('Class ' . get_class($profiler_instance) . ' does not   extend sw_db_profiler');	
		}

		if (null !== $enabled) {
			$profiler_instance->set_enabled($enabled);	
		}

		$this->__profiler = $profiler_instance;

		return $this;
	}

	// }}}
	// {{{ public function get_profiler()

	/**
	 * 获取分析器对象 
	 * 
	 * @access public
	 * @return sw_db_profiler
	 */
	public function get_profiler()
	{
		return $this->__profiler;	
	}

	// }}}
	// {{{ public function get_statement_class()

	/**
	 * 获取statement类名 
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
	 * 设置默认的statement类名 
	 * 
	 * @param string $class 
	 * @access public
	 * @return sw_db_adapter_abstract
	 */
	public function set_statement_class($class)
	{
		$this->__default_stmt_class = $class;
		return $this;	
	}

	// }}}
	// {{{ public function query()

	/**
	 * 执行查询 
	 * 
	 * @param sw_db_select | string $sql 
	 * @param array $bind 
	 * @access public
	 * @return sw_db_statement
	 */
	public function query($sql, $bind = array())
	{
		$this->_connect();
		
		if ($sql instanceof sw_db_select) {
			if (empty($bind)) {
				$bind = $sql->get_bind();	
			}

			$sql = $sql->assemble();
		}

		if (!is_array($bind)) {
			$bind = array($bind);	
		}

		//预处理，执行
		$stmt = $this->prepare($sql);
		$stmt->execute($bind);
		$stmt->set_fetch_mode($this->__fetch_mode);
		return $stmt;
	}

	// }}}
	// {{{ public function begin_transaction()

	/**
	 * 取消autocommit自动提交，开启事务 
	 * 
	 * @access public
	 * @return sw_db_adapter_abstract
	 */
	public function begin_transaction()
	{
		$this->__connect();
		$q = $this->__profiler->query_start('begin', sw_db_profiler::TRANSACTION);
		$this->_begin_transaction();
		$this->__profiler->query_end($q);
		return $this;	
	}

	// }}}
	// {{{ public function commit()

	/**
	 * 提交 
	 * 
	 * @access public
	 * @return sw_db_adapter_abstract
	 */
	public function commit()
	{
		$this->_connect();
		$q = $this->__profiler->query_start('commit', sw_db_profiler::TRANSACTION);
		$this->_commit();
		$this->__profiler->query_end($q);
		return $this;	
	}

	// }}}
	//{{{ public function rollback()

	/**
	 * 回滚 
	 * 
	 * @access public
	 * @return sw_db_adapter_abstract
	 */
	public function rollback()
	{
		$this->_connect();
		$q = $this->__profiler->query_start('rollback', sw_db_profiler::TRANSACTION);
		$this->_rollback();
		$this->__profiler->query_end($q);
		return $this;	
	}

	// }}}
	// {{{ public function insert()

	/**
	 * 插入操作 
	 * 
	 * $bind = array(
	 *		'column1' =>'value1',
	 *		'column2' =>'value2',
	 * );
	 *
	 * @param string $table 
	 * @param array $bind 
	 * @access public
	 * @return integer 插入影响行数
	 * @throws sw_db_adapter_abstract
	 */
	public function insert($table, array $bind)
	{
		$cols = array();
		$vars = array();		
		$i = 0;
		foreach ($bind as $col => $val) {
			$cols[] = $this->quote_identifier($col, true);
			if ($val instanceof sw_db_expr) {
				$vals[] = $val->__toString();
				unset($bind[$col]);	
			} else {
				//如果支持通过占位符绑定参数
				if ($this->supports_parameters('positional')) {
					$vals[] = '?';		
				} else {
					//如果通过name的方式绑定参数
					if ($this->supports_parameters('named')) {
						unset($bind[$col]);
						$bind[':col' . $i] = $val;
						$vals[] = ':col' . $i;
						$i++;
					} else {
						require_once PATH_SWAN_LIB . 'db/sw_db_adapter_exception.class.php';
						throw new sw_db_adapter_exception(get_class($this) ." doesn't support positional or named binding");
					}
				}
			}	
		}

		$sql = "INSERT INTO "
			 . $this->quote_identifier($table, true)
			 . ' (' . implode(', ', $cols) . ') '
			 . 'VALUES (' . implode(', ', $vals) . ')';

		if ($this->supports_parameters('positional')) {
			$bind = array_values($bind);	
		}
		$stmt = $this->query($sql, $bind);
		$result = $stmt->row_count();
		return $result;
	}

	// }}}
	// {{{ public function update()

	/**
	 * 更新 
	 * 
	 * @param string $table 
	 * @param array $bind 
	 * @param mixed $where 
	 * @access public
	 * @return int
	 * @throw sw_db_adapter_exception
	 */
	public function update($table, array $bind, $where = '')
	{
		$set = array();
		$i = 0;
		foreach ($bind as $col => $val) {
			if ($val instanceof sw_db_expr) {
				$val = $val->__toString();
				unset($bind[$col]);	
			} else {
				if ($this->supports_parameters('positional')) {
					$val = '?';	
				} else {
					if ($this->supports_parameters('named')) {
						unset($bind[$col]);
						$bind[':col'. $i] = $val;
						$val = 'col' . $i;
						$i++;	
					} else {
						require_once PATH_SWAN_LIB . 'db/sw_db_adapter_exception.class.php';
						throw new sw_db_adapter_exception(get_class($this) ." doesn't support positional or named binding");	
					}
				}	
			}
			$set[] = $this->quote_identifier($col, true) . ' = ' . $val;
		}
		
		$where = $this->_where_expr($where);

		$sql = "UPDATE "
			 . $this->quote_identifier($table, true)
			 . ' SET ' . implode(', ', $set)
			 . (($where) ? " WHERE $where" : '');
		if ($this->supports_parameters('positional')) {
			$bind = array_values($bind);
		}
		$stmt = $this->query($sql, $bind);
		$result = $stmt->row_count();
		return $result;
	}

	// }}}
	// {{{ public function delete()

	/**
	 * delete 
	 * 
	 * @param string $table 
	 * @param mixed $where 
	 * @access public
	 * @return integer
	 */
	public function delete($table, $where = '')
	{
		$where = $this->_where_expr($where);
		
		$sql = "DELETE FROM "
			 . $this->quote_identifier($table, true)
			 . (($where) ? " WHERE $where" : '');
		
		$stmt = $this->query($sql);
		$result = $stmt->row_count();
		return $result;	
	}

	// }}}
	// {{{ protected function _where_expr()

	/**
	 * 将字符串，数组，sw_db_expr类型的条件转化为字符串条件 
	 * 
	 * @param mixed $where 
	 * @access protected
	 * @return string
	 */
	protected function _where_expr($where)
	{
		if (empty($where)) {
			return $where;	
		}
		if (!is_array($where)) {
			$where = array($where);	
		}
		foreach ($where as $cond => &$term) {
			if (is_int($cond)) {
				if ($term instanceof sw_db_expr) {
					$term = $term->__toString();	
				}
			} else {
				$term = $this->quote_into($cond, $term);
			}
			$term = '(' . $term . ')';	
		}

		$where = implode(' AND ', $where);
		return $where;
	}

	// }}}
	// {{{ public function select()
	
	/**
	 * 返回一个sw_db_select对象 
	 * 
	 * @access public
	 * @return sw_db_select
	 */
	public function select()
	{
		return new sw_db_select($this);	
	}

	// }}}
	// {{{ public function get_fetch_mode()
	
	/**
	 * 获取遍历模式 
	 * 
	 * @access public
	 * @return integer
	 */
	public function get_fetch_mode()
	{
		return $this->__fetch_mode;
	}

	// }}}
	// {{{ public function fetch_all()

	/**
	 * 利用默认的fetch方式返回所有的结果集 
	 * 
	 * @param string|sw_db_select $sql 
	 * @param array $bind 
	 * @param mixed $fetch_mode 
	 * @access public
	 * @return array
	 */
	public function fetch_all($sql, $bind = array(), $fetch_mode = null)
	{
		if ($fetch_mode === null) {
			$fetch = $this->__fetch_mode;	
		}

		$stmt = $this->query($sql, $bind);
		$result = $stmt->fetch_all($fetch_mode);
		return $result;
	}
		
	// }}}
	// {{{ public function fetch_row()
	
	/**
	 * 获取结果集中的第一条记录 
	 * 
	 * @param string|sw_db_select $sql 
	 * @param array $bind 
	 * @param mixed $fetch_mode 
	 * @access public
	 * @return mixed array, object, scalar 取决于获取方式
	 */
	public function fetch_row($sql, $bind = array(), $fetch_mode = null)
	{
		if ($fetch_mode === null) {
			$fetch_mode = $this->__fetch_mode;	
		}
		$stmt = $this->query($sql, $bind);
		$result = $stmt->fetch($fetch_mode);
		return $result;
	}

	// }}}
	// {{{ public function fetch_assoc()

	/**
	 * 获取所有SQL结果行作为一个关联数组 
	 * 
	 * @param string|sw_db_select $sql 
	 * @param array $bind 
	 * @access public
	 * @return array
	 */
	public function fetch_assoc($sql, $bind = array())
	{
		$stmt = $this->query($sql, $bind);
		$data = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tmp = array_values(array_slice($row, 0, 1));
			$data[$tmp[0]] = $row;	
		}
		return $data;
	}

	// }}}
	// {{{ public function fetch_col()

	/**
	 * 获取第一列的SQL结果行作为一个数组 
	 * 
	 * @param string|sw_db_select $sql 
	 * @param array $bind 
	 * @access public
	 * @return array
	 */
	public function fetch_col($sql, $bind = array())
	{
		$stmt = $this->query($sql, $bind);
		$result = $stmt->fetch_all(PDO::FETCH_COLUMN, 0);
		return $result;
	}

	// }}}
	// {{{ public function fetch_pairs()

	/**
	 * 获取所有SQL结果行数组的键值对 
	 * 
	 * @param string|sw_db_select $sql 
	 * @param array $bind 
	 * @access public
	 * @return array
	 */
	public function fetch_pairs($sql, $bind = array())
	{
		$stmt = $this->query($sql, $bind);
		$data = array();
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$data[$row[0]] = $row[1];	
		}
		return $data;
	}

	// }}}
	// {{{ public function fetch_one()

	/**
	 * 获取第一列的第一行的SQL结果 
	 * 
	 * @param string|sw_db_select $sql 
	 * @param array $bind 
	 * @access public
	 * @return string
	 */
	public function fetch_one($sql, $bind = array())
	{
		$stmt = $this->query($sql, $bind);
		$result = $stmt->fetch_column(0);
		return $result;
	}

	// }}}
	// {{{ protected function _quote()

	/**
	 * 返回一个原始的字符串 
	 * 
	 * @param string $value 
	 * @access protected
	 * @return string
	 */
	protected function _quote($value)
	{
		if (is_int($value)) {
			return $value;	
		} else if (is_float($value)) {
			return sprintf('%F', $value);	
		}
		return "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
	}

	// }}}
	// {{{ public function quote()

	/**
	 * 返回一个安全的SQL语句 
	 * 
	 * @param mixed $value 
	 * @param mixed $type 
	 * @access public
	 * @return string
	 */
	public function quote($value, $type = null)
	{
		$this->_connect();

		if ($value instanceof sw_db_select) {
			return '(' . $value->assemble() . ')';	
		}

		if ($value instanceof sw_db_expr) {
			return $value->__toString();	
		}

		if (is_array($value)) {
			foreach ($value as &$val) {
				$val = $this->quote($val, $type);	
			}	
			return implode(', ', $value);
		}

		if ($type !== null && array_key_exists($type = strtoupper($type), $this->__numeric_data_types)) {
			$quoted_value = '0';	
			switch ($this->__numeric_data_types[$type]) {
				case sw_db::INI_TYPE://32-bit integer
					$quoted_value = (string) intval($value);
					break;
				case sw_db::BIGINT_TYPE:// 64-bit integer
					if (preg_match('/^(
						[+-]?
						(?:
							0[Xx][\da-fA-F]+
							|\d+
							(?:[eE][+-]?\d+)?
						)
					)/x', (string) $value, $metches)) {
						$quoted_value = $metches[1];	
					}
					break;
				case sw_db::FLOAT_TYPE: //float or decimal
					$quoted_value = sprintf('%F', $value);
			}
			return $quoted_value;
		}

		return $this->_quote($value);
	}

	// }}}
	// {{{ public function quote_into()

	/**
	 * 对占位符替换成安全的值Example:
	 * <code>
	 * $text = "WHERE date < ?";
	 * $date = "2012-09-01";
	 * $safe = $sql->quote_into($text, $date);
	 * // $safe = "WHERE date < '2012-09-01'"
     * </code> 
	 * 
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
					$text = substr_replace($text, $this->quote($value, $type), strpos($text, '?'), 1));
				}
				--$count;
			}
			return $text;	
		}
	}

	// }}}
	// {{{ public function quote_identifier()
	
	/**
	 * 引用一个标识符 
	 * Example:
	 * <code>
	 * $adapter->quote_identifier('myschema.mytable')
	 * </code>
	 * Returns: "myschema"."mytable"
	 * <code>
	 * $adapter->quote_identifier(array('myschema','my.table'))
	 * </code>
	 * Returns: "myschema"."my.table"
	 *
	 * @param string|array|sw_db_expr $ident 
	 * @param bool $auto 
	 * @access public
	 * @return string
	 */
	public function quote_identifier($ident, $auto = false)
	{
		return $this->_quote_identifier_as($ident, null, $auto);	
	}

	// }}}
	// {{{ public function quote_column_as()

	/**
	 * 为一个字段加标识符，并且做别名 
	 * 
	 * @param string|array|sw_db_expr $ident 
	 * @param string $alias 
	 * @param boolean $auto 
	 * @access public
	 * @return string
	 */
	public function quote_column_as($ident, $alias, $auto = false)
	{
		return $this->_quote_identifier_as($ident, $alias, $auto);	
	}

	// }}}
	// {{{ public function quote_table_as()

	/**
	 * 为表名添加标识符 
	 * 
	 * @param string|array|sw_db_expr $ident 
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
	// {{{ protected function _quote_identifier_as()

	/**
	 * 引用一个标识符和一个可选的别名 
	 * 
	 * @param string|array|sw_db_expr $ident 
	 * @param string $alias 
	 * @param boolean $auto 
	 * @param string $as 
	 * @access public
	 * @return string
	 */
	protected function _quote_identifier_as($ident, $alias = null, $auto = false, $as = ' AS ')
	{
		if ($ident instanceof sw_db_expr) {
			$quoted = $ident->__toString();	
		} else if ($ident instanceof sw_db_select) {
			$quoted = '(' . $ident->assemble() . ')';	
		} else {
			if (is_string($ident)) {
				$ident = explode('.', $ident);
			}
			if (is_array($ident)) {
				$segments = array();
				foreach ($segments as $segment) {
					if ($segment instanceof sw_db_expr) {
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
		if ($alias !== null) {
			$quoted .= $as . $this->_quote_identifier($alias, $auto);	
		}
		return $quoted;
	}

	// }}}
	// {{{ protected function _quote_identifier()

	/**
	 * 引用一个标识符 
	 * 
	 * @param string $value 
	 * @param boolean $auto 
	 * @access protected
	 * @return string
	 */
	protected function _quote_identifier($value, $auto = false)
	{
		if ($auto === false || $this->__auto_quote_indentifiers === true) {
			$q = $this->get_quote_identifier_symbol();
			return ($q . str_replace("$q", "$q$q", $value) . $q);	
		}	
		return $value;
	}

	// }}}
	// {{{ public function get_quote_identifier_symbol()

	/**
	 * 返回符号适配器使用分隔符 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_quote_identifier_symbol()
	{
		return '"';	
	}

	// }}}
	// {{{ public function last_sequence_id()

	/**
	 * 返回数据库最近插入的序列
	 * 只在RDBMS品牌的数据库中支持，例如Oracle,PostgreSQL,DB2中支持，其他的数据库返回NULL 
	 * 
	 * @param string $sequence_name 
	 * @access public
	 * @return string
	 */
	public function last_sequence_id($sequence_name)
	{
		return null;	
	}

	// }}}
	// {{{ public function next_sequence_id()

	/**
	 * 生成一个新值从指定的序列在数据库中,并返回它 
	 * 
	 * @param string $sequence_name
	 * @access public
	 * @return string
	 */
	public function next_sequence_id($sequence_name)
	{
		return null;
	}

	// }}}
	// {{{ public function fold_case()

	/**
	 * 关键字大小写表示，默认是不强制转换的 
	 * 
	 * @param string $key 
	 * @access public
	 * @return string
	 */
	public function fold_case($key)
	{
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
	// {{{ public function __sleep()

	/**
	 * 当序列化对象时调用 
	 * 
	 * @access public
	 * @return array
	 */
	public function __sleep()
	{
		if ($this->__allow_serialization == false) {
			require_once PATH_SWAN_LIB . 'db/sw_db_adapter_exception.class.php';
			throw new sw_db_adapter_exception(get_class($this) . " is not allowed to be serialized");
				
		}	
		$this->__connection = false;
		return array_keys(array_diff_key(get_object_vars($this), array('__connection' => false)));
	}

	// }}}
	// {{{ public function __wakeup()

	/**
	 * 反序列化时调用 
	 * 
	 * @access public
	 * @return void
	 */
	public function __wakeup()
	{
		if ($this->__allow_serialization == true) {
			$this->get_connection();	
		}	
	}

	// }}}
	// }}} end functions
	// {{{ abstract methods
	// {{{ abstract public function list_tables()

	/**
	 * 返回数据库中的数据表列表 
	 * 
	 * @abstract
	 * @access public
	 * @return array
	 */
	abstract public function list_tables();
	// }}}
	// {{{ abstract public function describe_table()
	
	/**
	 * 返回一个表的所有列描述 
	 * 
	 * @param string $table_name 
	 * @param string $schema_name 
	 * @abstract
	 * @access public
	 * @return array
	 */
	abstract public function describe_table($table_name, $schema_name = null);

	// }}}
	// {{{ abstract protected function _connect()

	/**
	 * 连接数据库 
	 * 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function _connect();

	// }}}
	// {{{ abstract public function is_connected()

	/**
	 * 判断数据库是否连接上 
	 * 
	 * @abstract
	 * @access public
	 * @return boolean
	 */
	abstract public function is_connected();

	// }}}
	// {{{ abstract public function close_connection()

	/**
	 * 关闭数据库连接 
	 * 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function close_connection();

	// }}}
	// {{{ abstract public function prepare()

	/**
	 * 预处理sql生成一个stmt对象 
	 * 
	 * @param string|sw_db_select $sql 
	 * @abstract
	 * @access public
	 * @return sw_db_statement|PDOStatement
	 */
	abstract public function prepare($sql);

	// }}}
	// {{{ abstract public function last_insert_id()

	/**
	 * 返回最后插入的ID
	 * 
	 * @param string $table_name 
	 * @param string $primary_key 
	 * @abstract
	 * @access public
	 * @return string
	 */
	abstract public function last_insert_id($table_name = null, $primary_key = null);

	// }}}
	// {{{ abstract protected function _begin_transaction()

	/**
	 * 开始事务 
	 * 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function _begin_transaction();

	// }}}
	// {{{ abstract protected function _commit()
	
	/**
	 * 提交事务 
	 * 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function _commit();

	// }}}
	// {{{ abstract protected function _rollback()

	/**
	 * 事务回滚 
	 * 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function _rollback();

	// }}}
	// {{{ abstract public function set_fetch_mode()

	/**
	 * 设置结果集遍历模式 
	 * 
	 * @param integer $mode 
	 * @abstract
	 * @access public
	 * @return void
	 * @throws sw_db_adapter_exception
	 */
	abstract public function set_fetch_mode($mode);

	// }}}
	// {{{ abstract public function limit()

	/**
	 * 实现limit 
	 * 
	 * @param mixed $sql 
	 * @param integer $count 
	 * @param integer $offset 
	 * @abstract
	 * @access public
	 * @return string
	 */
	abstract public function limit($sql, $count, $offset = 0);
	// }}}
	// {{{ abstract public function supports_parameters()

	/**
	 * 判断继承接口模块是否支持占位符绑定或命名绑定 
	 * 
	 * @param mixed $type    'positional' or 'named'
	 * @abstract
	 * @access public
	 * @return boolean
	 */
	abstract public function supports_parameters($type);

	// }}}
	// {{{ abstract public function get_server_version()

	/**
	 * 获取服务器的版本号 
	 * 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function get_server_version();
	// }}}
	// }}} end abstract
}
