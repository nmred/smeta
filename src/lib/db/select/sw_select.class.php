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

namespace lib\db\select;
use lib\db\adapter\sw_abstract;
use lib\db\sw_db_expr;
use lib\db\select\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_select
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
+------------------------------------------------------------------------------
*/
class sw_select
{
	// {{{ consts

	const DISTINCT       = 'distinct';
	const COLUMNS        = 'columns';
	const FROM           = 'from';
	const UNION          = 'union';
	const WHERE          = 'where';
	const GROUP          = 'group';
	const HAVING         = 'having';
	const ORDER          = 'order';
	const LIMIT_COUNT    = 'limit_count';
	const LIMIT_OFFSET   = 'limit_offset';
	const FOR_UPDATE     = 'for_update';

	const INNER_JOIN     = 'inner join';
	const LEFT_JOIN      = 'left join';
	const RIGHT_JOIN     = 'right join';
	const FULL_JOIN      = 'full join';
	const CROSS_JOIN     = 'cross join';
	const NATURAL_JOIN   = 'natural join';

	const SQL_WILDCARD   = '*';
	const SQL_SELECT     = 'SELECT';
	const SQL_UNION      = 'UNION';
	const SQL_UNION_ALL  = 'UNION ALL';
	const SQL_FROM       = 'FROM';
	const SQL_WHERE      = 'WHERE';
	const SQL_DISTINCT   = 'DISTINCT';
	const SQL_GROUP_BY   = 'GROUP BY';
	const SQL_ORDER_BY   = 'ORDER BY';
	const SQL_HAVING     = 'HAVING';
	const SQL_FOR_UPDATE = 'FOR UPDATE';
	const SQL_AND        = 'AND';
	const SQL_AS         = 'AS';
	const SQL_OR         = 'OR';
	const SQL_ON         = 'ON';
	const SQL_ASC        = 'ASC';
	const SQL_DESC       = 'DESC';

	// }}}
	// {{{ members

	/**
	 * lib\db\sw_abstract 对象
	 *
	 * @var object
	 * @access protected
	 */
	protected $__adapter;

	/**
	 * 属性初始化数组
	 *
	 * 注意：
	 * 在每次进行 SELECT 查询之前必须进行 self::$__parts 的初始化
	 * 否则可能会有意想不到的错误，这对于 FRO_UPDATE 尤其重要
	 *
	 * @var array
	 * @access protected
	 */
	protected static $__parts_init = array(
		self::DISTINCT     => false,
		self::COLUMNS      => array(),
		self::UNION        => array(),
		self::FROM         => array(),
		self::WHERE        => array(),
		self::GROUP        => array(),
		self::HAVING       => array(),
		self::ORDER        => array(),
		self::LIMIT_COUNT  => null,
		self::LIMIT_OFFSET => null,
		self::FOR_UPDATE   => false,
	);

	/**
	 * 允许使用的 JOIN 类型
	 *
	 * @var array
	 * @access protected
	 */
	protected static $__join_types = array(
		self::INNER_JOIN,
		self::LEFT_JOIN,
		self::RIGHT_JOIN,
		self::FULL_JOIN,
		self::CROSS_JOIN,
		self::NATURAL_JOIN,
	);

	/**
	 * 允许使用 UNION 的类型
	 *
	 * @var array
	 * @access protected
	 */
	protected static $__union_types = array(
		self::SQL_UNION,
		self::SQL_UNION_ALL,
	);

	/**
	 * SELECT 查询的各个装饰部分
	 *
	 * @var array
	 * @access protected
	 */
	protected $parts = array();

	/**
	 *  查询绑定参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bind = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct
	 *
	 * @param \lib\db\adapter\sw_abstract $adapter
	 * @access public
	 * @return void
	 */
	public function __construct(\lib\db\adapter\sw_abstract $adapter)
	{
		$this->__adapter = $adapter;
		$this->__parts = self::$__parts_init;
	}

	// }}}
	// {{{ public function get_bind()

	/**
	 * 获取绑定的参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_bind()
	{
		return $this->__bind;
	}

	// }}}
	// {{{ public function bind()

	/**
	 * 绑定参数 
	 * 
	 * @param array $bind 
	 * @access public
	 * @return sw_select
	 */
	public function bind($bind)
	{
		$this->__bind = $bind;

		return $this;
	}

	// }}}
	// {{{ public function distinct()

	/**
	 * 装饰 SELECT DISTINCT 查询
	 *
	 * @param boolean $flag
	 * @access public
	 * @return sw_select
	 */
	public function distinct($flag = true)
	{
		$this->__parts[self::DISTINCT] = (bool) $flag;
		return $this;
	}

	// }}}
	// {{{ public function columns()

	/**
	 * 处理查询的字段
	 *
	 * @param array|string|sw_db_expr $cols
	 * @param string $correlation_name
	 * @access public
	 * @return sw_select object
	 */
	public function columns($cols = '*', $correlation_name = null)
	{
		if ($correlation_name === null && count($this->__parts[self::FROM])) {
			$correlation_name_keys = array_keys($this->__parts[self::FROM]);
			$correlation_name      = current($correlation_name_keys);
		}

		if (!array_key_exists($correlation_name, $this->__parts[self::FROM])) {
			throw new sw_exception("No table has been specified for the FROM clause");
		}

		$this->_table_cols($correlation_name, $cols);

		return $this;
	}

	// }}}
	// {{{ public function from()

	/**
	 * 装饰 FROM 子句
	 *
	 * @param array|string|sw_db_expr $name
	 * @param array|string|sw_db_expr $cols
	 * @param string $schema
	 * @access public
	 * @return sw_select
	 */
	public function from($name, $cols = '*', $schema = null)
	{
		return $this->_join(self::FROM, $name, null, $cols, $schema);
	}

	// }}}
	// {{{ public function union()

	/**
	 * 装饰 UNION 子句
	 *
	 * @param array $select 数组中的元素是 SQL 语句或 sw_select 对象
	 * @param string $type
	 * @access public
	 * @return sw_select
	 */
	public function union($select = array(), $type = self::SQL_UNION)
	{
		if (!is_array($select)) {
			throw new sw_exception('union() only accepts an array of sw_select instances of sql query strings.');
		}

		if (!in_array($type, self::$__union_types)) {
			throw new sw_exception("Invalid union type '{$type}'");
		}

		foreach ($select as $target) {
			$this->__parts[self::UNION][] = array($target, $type);
		}

		return $this;
	}

	// }}}
	// {{{ public function join()

	/**
	 * 装饰 JOIN 子句
	 *
	 * @param array|string|sw_db_expr $name
	 * @param string $cond
	 * @param string|array $cols
	 * @param string $schema
	 * @access public
	 * @return sw_select
	 */
	public function join($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->join_inner($name, $cond, $cols, $schema);
	}

	// }}}
	// {{{ public function join_inner()

	/**
	 * 装饰 JOIN INNER子句
	 *
	 * @param array|string|sw_db_expr $name
	 * @param string $cond
	 * @param string|array $cols
	 * @param string $schema
	 * @access public
	 * @return sw_select
	 */
	public function join_inner($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::INNER_JOIN, $name, $cond, $cols, $schema);
	}

	// }}}
	// {{{ public function join_left()

	/**
	 * 装饰 JOIN LEFT子句
	 *
	 * @param array|string|sw_db_expr $name
	 * @param string $cond
	 * @param string|array $cols
	 * @param string $schema
	 * @access public
	 * @return sw_select
	 */
	public function join_left($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::LEFT_JOIN, $name, $cond, $cols, $schema);
	}

	// }}}
	// {{{ public function join_right()

	/**
	 * 装饰 JOIN RIGHT子句
	 *
	 * @param array|string|sw_db_expr $name
	 * @param string $cond
	 * @param string|array $cols
	 * @param string $schema
	 * @access public
	 * @return sw_select
	 */
	public function join_right($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::RIGHT_JOIN, $name, $cond, $cols, $schema);
	}

	// }}}
	// {{{ public function join_full()

	/**
	 * 装饰 JOIN FULL子句
	 *
	 * @param array|string|sw_db_expr $name
	 * @param string $cond
	 * @param string|array $cols
	 * @param string $schema
	 * @access public
	 * @return sw_select
	 */
	public function join_full($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::FULL_JOIN, $name, $cond, $cols, $schema);
	}

	// }}}
	// {{{ public function join_cross()

	/**
	 * 装饰 JOIN CROSS子句
	 *
	 * @param array|string|sw_db_expr $name
	 * @param string $cond
	 * @param string|array $cols
	 * @param string $schema
	 * @access public
	 * @return sw_select
	 */
	public function join_cross($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::CROSS_JOIN, $name, $cond, $cols, $schema);
	}

	// }}}
	// {{{ public function join_natural()

	/**
	 * 装饰 JOIN NATURAL子句
	 *
	 * @param array|string|sw_db_expr $name
	 * @param string $cond
	 * @param string|array $cols
	 * @param string $schema
	 * @access public
	 * @return sw_select
	 */
	public function join_natural($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::NATURAL_JOIN, $name, $cond, $cols, $schema);
	}

	// }}}
	// {{{ public function where()

	/**
	 * 装饰 WHERE 子句
	 *
	 * <code>
	 * // simplest but non-secure
	 * $select->where("id = $id");
	 *
	 * // secure (ID is quoted but matched anyway)
	 * $select->where('id = ?', $id);
	 *
	 * // alternatively, with named binding
	 * $select->where('id = :id');
	 * </code>
	 *
	 * Note that it is more correct to use named bindings in your
	 * queries for values other than strings. When you use named
	 * bindings, don't forget to pass the values when actually
	 * making a query:
	 *
	 * <code>
	 * $db->fetch_all($select, array('id' => 5));
	 * </code>
	 * @param string $cond  WHERE 条件
	 * @param mixed $value
	 * @param int $type 调用quote的类型参数 sw_db::INT_TYPE ....
	 * @access public
	 * @return sw_select
	 */
	public function where($cond, $value = null, $type = null)
	{
		$this->__parts[self::WHERE][] = $this->_where($cond, $value, $type, true);

		return $this;
	}

	// }}}
	// {{{ public function or_where()

	/**
	 * 装饰 OR WHERE 子句
	 *
	 * @param string $cond  WHERE 条件
	 * @param mixed $value
	 * @param int $type 调用quote的类型参数 sw_db::INT_TYPE ....
	 * @access public
	 * @return sw_select
	 */
	public function or_where($cond, $value = null, $type = null)
	{
		$this->__parts[self::WHERE][] = $this->_where($cond, $value, $type, false);

		return $this;
	}

	// }}}
	// {{{ public function group()

	/**
	 * 装饰 GROUP 子句
	 *
	 * @param array|string $spec
	 * @access public
	 * @return sw_select
	 */
	public function group($spec)
	{
		if (!is_array($spec)) {
			$spec = array($spec);
		}

		foreach ($spec as $val) {
			if (preg_match('/\(.*\)/', (string) $val)) {
				$val = new sw_db_expr($val);
			}

			$this->__parts[self::GROUP][] = $val;
		}

		return $this;
	}

	// }}}
	// {{{ public function having()

	/**
	 * 装饰 HAVING 条件子句 
	 * 
	 * @param string $cond 
	 * @param mixed $value 
	 * @param int $type 
	 * @access public
	 * @return sw_select
	 */
	public function having($cond, $value = null, $type = null)
	{
		if (null !== $value) {
			$cond = $this->__adapter->quote_into($cond, $value, $type);
		}

		if ($this->__parts[self::HAVING]) {
			$this->__parts[self::HAVING][] = self::SQL_AND . " ($cond)";	
		} else {
			$this->__parts[self::HAVING][] = "($cond)";	
		}

		return $this;
	}

	// }}}
	// {{{ public function or_having()

	/**
	 * 装饰 OR HAVING 条件子句 
	 * 
	 * @param string $cond 
	 * @param mixed $value 
	 * @param int $type 
	 * @access public
	 * @return sw_select
	 */
	public function or_having($cond, $value = null, $type = null)
	{
		if (null !== $value) {
			$cond = $this->__adapter->quote_into($cond, $value, $type);
		}

		if ($this->__parts[self::HAVING]) {
			$this->__parts[self::HAVING][] = self::SQL_OR . " ($cond)";	
		} else {
			$this->__parts[self::HAVING][] = "($cond)";	
		}

		return $this;
	}

	// }}}
	// {{{ public function order()

	/**
	 * 装饰 ORDER 子句 
	 * 
	 * @param string|array $spec 
	 * @access public
	 * @return void
	 */
	public function order($spec)
	{
		if (!is_array($spec)) {
			$spec = array($spec);
		}

		// 强制 'ASC' 或 'DESC' , 默认是 ASC
		foreach ($spec as $val) {
			if ($val instanceof sw_db_expr) {
				$expr = $val->__toString();
				if (empty($expr)) {
					continue;	
				}
				$this->__parts[self::ORDER][] = $val;
			} else {
				if (empty($val)) {
					continue;	
				}

				$direction = self::SQL_ASC;
				if (preg_match('/(.*\W)(' . self::SQL_ASC . '|' . self::SQL_DESC . ')\b/si', $val, $matches)) {
					$val = trim($matches[1]);
					$direction = $matches[2];	
				}
				if (preg_match('/\(.*\)/', $val)) {
					$val = new sw_db_expr($val);	
				}
				$this->__parts[self::ORDER][] = array($val, $direction);
			}
		}

		return $this;
	}

	// }}}
	// {{{ public function limit()

	/**
	 * 装饰 LIMIT 子句 
	 * 
	 * @param int $count 
	 * @param int $offset 
	 * @access public
	 * @return sw_select
	 */
	public function limit($count = null, $offset = null)
	{
		$this->__parts[self::LIMIT_COUNT]  = (int) $count;
		$this->__parts[self::LIMIT_OFFSET] = (int) $offset;
		return $this;
	}

	// }}}
	// {{{ public function limit_page()

	/**
	 * 装饰 分页方式的 LIMIT 子句 
	 * 
	 * @access public
	 * @return sw_select
	 */
	public function limit_page($page, $row_count)
	{
		$page      = ($page > 0) ? $page : 1;
		$row_count = ($row_count > 0) ? $row_count : 1;

		$this->__parts[self::LIMIT_COUNT]  = (int) $page;
		$this->__parts[self::LIMIT_OFFSET] = (int) $row_count;

		return $this;
	}

	// }}}
	// {{{ public function for_update()

	/**
	 * 装饰 FOR UPDATE 子句 
	 * 
	 * @param boolean $flag 
	 * @access public
	 * @return sw_select
	 */
	public function for_update($flag = true)
	{
		$this->__parts[self::FOR_UPDATE] = (bool) $flag;
		return $this;	
	}

	// }}}
	// {{{ public function get_part()

	/**
	 * 返回当前查询的子句 
	 * 
	 * @param string $part 
	 * @access public
	 * @return mixed
	 */
	public function get_part($part)
	{
		$part = strtolower($part);
		if (!array_key_exists($part, $this->__parts)) {
			throw new sw_exception("Invalid Select part '$part'");	
		}

		return $this->__parts[$part];
	}

	// }}}
	// {{{ public function query()

	/**
	 * 执行查询 
	 * 
	 * @param integer $fetch_mode 
	 * @param array $bind 
	 * @access public
	 * @return PDO_Statement|\lib\statement\sw_standard
	 */
	public function query($fetch_mode = null, $bind = array())
	{
		if (!empty($bind)) {
			$this->bind($bind);	
		}

		$stmt = $this->__adapter->query($this);
		if ($fetch_mode == null) {
			$fetch_mode = $this->__adapter->get_fetch_mode();	
		}

		$stmt->set_fetch_mode($fetch_mode);
		return $stmt;
	}

	// }}}
	// {{{ public function get_adapter()

	/**
	 * 获取 \lib\db\adapter\sw_abstract
	 *
	 * @access public
	 * @return void
	 */
	public function get_adapter()
	{
		return $this->__adapter;
	}

	// }}}
	// {{{ public function assemble()

	/**
	 * 将该对象转化为 SQL 语句 
	 * 
	 * @access public
	 * @return string|null
	 */
	public function assemble()
	{
		$sql = self::SQL_SELECT;
		foreach (array_keys(self::$__parts_init) as $part) {
			$method = '_render_' . strtolower($part);
			if (method_exists($this, $method)) {
				$sql = $this->$method($sql);	
			}
		}

		return $sql;
	}

	// }}}
	// {{{ public function reset()

	/**
	 * 重置 part 的设置 
	 * 
	 * @param string part 
	 * @access public
	 * @return sw_select
	 */
	public function reset($part = null)
	{
		if ($part == null) {
			$this->__parts = self::$__parts_init;	
		} else if (array_key_exists($part, self::$__parts_init)) {
			$this->__parts[$part] = self::$__parts_init[$part];	
		}

		return $this;
	}

	// }}}
	// {{{ public function __call()

	/**
	 * __call 
	 * 
	 * @access public
	 * @return void
	 */
	public function __call($method, array $args)
	{
		$matches = array();

		if (preg_match('/^join_([a-z]*?)_using$/', $method, $matches)) {
			$type = strtolower($matches[1]);
			if ($type) {
				$type .= ' join';
				if (!in_array($type, self::$__join_types)) {
					throw new sw_exception("Unrecognized method '$method()'");	
				}
				if (in_array($type, array(self::CROSS_JOIN, self::NATURAL_JOIN))) {
					throw new sw_exception("Cannot perform a join_using with method '$method()'");	
				}
			} else {
				$type = self::INNER_JOIN;	
			}
			array_unshift($args, $type);

			return call_user_func_array(array($this, '_join_using'), $args);
		}

		throw new sw_exception("Unrecognized method '$method()'");
	}

	// }}}
	// {{{ public function __toString()

	/**
	 * __toString
	 *
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		try {
			$sql = $this->assemble();
		} catch (sw_exception $e) {
			trigger_error($e->getMessage(), E_USER_WARNING);
			$sql = '';
		}

		return (string) $sql;
	}

	// }}}
	// {{{ protected function _join()

	/**
	 * 装饰 JOIN
	 *
	 * @param null|string $type 指定类型
	 * @param array|string|sw_db_expr $name 指定表名
	 * @param string $cond 指定 JOIN 的条件
	 * @param array|string $cols 指定查询字段
	 * @param string $schema 指定数据库名称
	 * @access protected
	 * @return sw_select
	 */
	protected function _join($type, $name, $cond, $cols, $schema = null)
	{
		if (!in_array($type, self::$__join_types) && $type !== self::FROM) {
			throw new sw_exception("Invalid join type '$type'");
		}

		if (count($this->__parts[self::UNION])) {
			throw new sw_exception("Invalid use of table with " . self::SQL_UNION);
		}

		if (empty($name)) {
			$correlation_name = $table_name = '';
		} else if (is_array($name)) {
			// 必须是 array($correlationName => $tableName) 或 array($ident, ...)
			foreach ($name as $_correlation_name => $_table_name) {
				if (is_string($_correlation_name)) {
					// 在 name的结构中假设 key是表别名， value是表名称
					$table_name = $_table_name;
					$correlation_name = $_correlation_name;
				} else {
					$table_name = $_table_name;
					$correlation_name = $this->_unique_correlation($table_name);
				}
				break;
			}
		} else if ($name instanceof sw_db_expr || $name instanceof sw_select) {
			$table_name = $name;
			$correlation_name = $this->_unique_correlation('t');
		} else if (preg_match('/^(.+)\s+AS\s+(.+)$/i', $name, $m)) {
			$table_name = $m[1];
			$correlation_name = $m[2];
		} else {
			$table_name = $name;
			$correlation_name = $this->_unique_correlation($table_name);
		}

		// 处理带有数据库名称的
		if (!is_object($table_name) && false !== strpos($table_name, '.')) {
			list($schema, $table_name) = explode('.', $table_name);
		}

		$last_from_correlation_name = null;
		if (!empty($correlation_name)) {
			if (array_key_exists($correlation_name, $this->__parts[self::FROM])) {
				throw new sw_exception("You cannot define a correlation name '$correlation_name' more than once");
			}

			if (self::FROM === $type) {
				// 将 from 类型的追加到 self::FROM 的 from类型最后
				$tmp_from_parts = $this->__parts[self::FROM];
				$this->__parts[self::FROM] = array();

				// 移动所有的 FROM 栈
				while($tmp_from_parts) {
					$current_correlation_name = key($tmp_from_parts);
					if ($tmp_from_parts[$current_correlation_name]['join_type'] != self::FROM) {
						break;
					}
					$last_from_correlation_name = $current_correlation_name;
					$this->__parts[self::FROM][$current_correlation_name] = array_shift($tmp_from_parts);
				}
			} else {
				$tmp_from_parts = array();
			}

			$this->__parts[self::FROM][$correlation_name] = array(
				'join_type'      => $type,
				'schema'         => $schema,
				'table_name'     => $table_name,
				'join_condition' => $cond,
			);

			while ($tmp_from_parts) {
				$current_correlation_name = key($tmp_from_parts);
				$this->__parts[self::FROM][$current_correlation_name] = array_shift($tmp_from_parts);
			}
		}

		// 添加查询字段
		if (self::FROM === $type && $last_from_correlation_name == null) {
			$last_from_correlation_name = true; // 一定要保证 from 类型的字段在最前面
		}

		$this->_table_cols($correlation_name, $cols, $last_from_correlation_name);
		return $this;
	}

	// }}}
	// {{{ protected function _table_cols()

	/**
	 * 添加内部的 table-to-column MAP 对应关系
	 *
	 * @param string $correlation_name
	 * @param string|array $cols
	 * @param bool|string $after_correlation_name 如果是 true 将处理的新的字段添加到 sekf::$__parts[self::COLUMNS] 的最前面
	 * 如果是字符串则插入到该子段的后面
	 * @access protected
	 * @return void
	 */
	protected function _table_cols($correlation_name, $cols, $after_correlation_name = null)
	{
		if (!is_array($cols)) {
			$cols = array($cols);
		}

		if ($correlation_name == null) {
			$correlation_name = '';
		}

		$column_values = array();

		foreach (array_filter($cols) as $alias => $col) {
			$current_correlation_name = $correlation_name;
			$alias = null;
			if (is_string($col)) {
				// 检查 column 是否是 "<column> AS <alias>" 形式，并且提取 alias 名称
				if (preg_match('/^(.+)\s+' . self::SQL_AS . '\s+(.+)$/i', $col, $m)) {
					$col = $m[1];
					$alias = $m[2];
				}
				// 检查 column 是否是 SQL 函数，如果是转化为 sw_db_expr
				if (preg_match ('/\(.*\)/', $col)) {
					$col = new sw_db_expr($col);
				} elseif (preg_match('/(.+)\.(.+)/', $col, $m)) {
					$current_correlation_name = $m[1];
					$col = $m[2];
				}
			}
			$column_values[] = array($current_correlation_name, $col, isset($alias) ? $alias : null);
		}

		if ($column_values) {
			if ($after_correlation_name === true || is_string($after_correlation_name)) {
				$tmp_columns = $this->__parts[self::COLUMNS];
				$this->__parts[self::COLUMNS] = array();
			} else {
				$tmp_columns = array();
			}

			if (is_string($after_correlation_name)) {
				while ($tmp_columns) {
					$this->__parts[self::COLUMNS][] = $current_column = array_shift($tmp_columns);
					if ($current_column[0] == $after_correlation_name) {
						break;
					}
				}
			}

			foreach ($column_values as $column_value) {
				array_push($this->__parts[self::COLUMNS], $column_value);
			}

			while ($tmp_columns) {
				array_push($this->__parts[self::COLUMNS], array_shift($tmp_columns));
			}
		}
	}

	// }}}
	// {{{ protected function _unique_correlation()

	/**
	 * 获取唯一的数据表别名
	 *
	 * @param string|array $name
	 * @access protected
	 * @return string
	 */
	protected function _unique_correlation($name)
	{
		if (is_array($name)) {
			$k = key($name);
			$c = is_string($k) ? $k : end($name);
			$name = $k;
		} else {
			$dot = strrpos($name, '.');
			$c   = ($dot === false) ? $name : substr($name, $dot + 1);
		}

		for ($i = 2; array_key_exists($c, $this->__parts[self::FROM]); $i++) {
			$c = $name . '_' . (string) $i;
		}

		return $c;
	}

	// }}}
	// {{{ protected function _join_using()

	/**
	 * 装饰 JOIN .... Using ... 子句 
	 * 
	 * <code>
	 * $select = $db->select()->from('table1')
	 *                        ->joinUsing('table2', 'column1');
	 *
	 * // SELECT * FROM table1 JOIN table2 ON table1.column1 = table2.column2
	 * </code>
	 *
	 * @access protected
	 * @return void
	 */
	protected function _join_using($type, $name, $cond, $cols = '*', $schema = null)
	{	
		if (empty($this->__parts[self::FROM])) {
			throw new sw_exception("You can only perform a joinUsing after specifying a FROM table");
		}

		$join = $this->__adapter->quote_indentifier(key($this->__parts[self::FROM]), true);
		$from = $this->__adapter->quote_indentifier($this->_unique_correlation($name), true);
		
		$join_cond = array();
		foreach ((array) $cond as $field_name) {
			$cond1 = $from . '.' . $field_name;
			$cond2 = $join . '.' . $field_name;
			$join_cond[] = $cond1 . ' = ' . $cond2;	
		}
		$cond = implode(' ' . self::SQL_AND . ' ', $join_cond);

		return $this->_join($type, $name, $cond, $cols, $schema);
	}

	// }}}
	// {{{ protected function _where()

	/**
	 * 装饰 WHERE 子句
	 *
	 * @param string $condition
	 * @param mixed $where
	 * @param string $type
	 * @param boolean $bool
	 * @access protected
	 * @return string
	 */
	protected function _where($condition, $value = null, $type = null, $bool = null)
	{
		if (count($this->__parts[self::UNION])) {
			throw new sw_exception("Invalid use of where clause with " . self::SQL_UNION);
		}

		if (null !== $value) {
			$condition = $this->__adapter->quote_into($condition, $value, $type);
		}

		$cond = "";
		if ($this->__parts[self::WHERE]) {
			if (true === $bool) {
				$cond = self::SQL_AND . ' ';
			} else {
				$cond = self::SQL_OR . ' ';
			}
		}

		return $cond . "($condition)";
	}

	// }}}
	// {{{ protected function _get_dummy_table()

	/**
	 * 获取一个空表 
	 * 
	 * @access protected
	 * @return array
	 */
	protected function _get_dummy_table()
	{
		return array();	
	}

	// }}}
	// {{{ protected function _get_quoted_schema()

	/**
	 * 格式化一个数据库名称 
	 * 
	 * @param string|null $schema 
	 * @access protected
	 * @return string|null
	 */
	protected function _get_quoted_schema($schema = null)
	{
		if ($schema === null) {
			return null;	
		}

		return $this->__adapter->quote_indentifier($schema, true) . '.';
	}

	// }}}
	// {{{ protected function _get_quoted_table()

	/**
	 * 格式化表名 
	 * 
	 * @param string $table_name 
	 * @param string $correlation_name 
	 * @access protected
	 * @return void
	 */
	protected function _get_quoted_table($table_name, $correlation_name = null)
	{
		return $this->__adapter->quote_table_as($table_name, $correlation_name, true);	
	}

	// }}}
	// {{{ protected function _render_distinct()

	/**
	 * 拼装 DISTINCT 子句 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string
	 */
	protected function _render_distinct($sql)
	{
		if ($this->__parts[self::DISTINCT]) {
			$sql .= ' ' . self::SQL_DISTINCT;	
		}
		
		return $sql;	
	}

	// }}}
	// {{{ protected function _render_columns()

	/**
	 * 拼装 COLUMNS 子句 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string
	 */
	protected function _render_columns($sql)
	{
		if (!count($this->__parts[self::COLUMNS])) {
			return null;	
		}

		$columns = array();
		foreach ($this->__parts[self::COLUMNS] as $column_entry) {
			list($correlation_name, $column, $alias) = $column_entry;
			if ($column instanceof sw_db_expr) {
				$columns[] = $this->__adapter->quote_column_as($column, $alias, true);	
			} else {
				if ($column == self::SQL_WILDCARD) {
					$column = new sw_db_expr(self::SQL_WILDCARD);
					$alias  = null;	
				}

				if (empty($correlation_name)) {
					$columns[] = $this->__adapter->quote_column_as($column, $alias, true);	
				} else {
					$columns[] = $this->__adapter->quote_column_as(array($correlation_name, $column), $alias, true);	
				}
			}
		}

		return $sql .= ' ' . implode(', ', $columns);
	}

	// }}}
	// {{{ protected function _render_from()

	/**
	 * 拼装 FROM 和 JOIN 子句 
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _render_from($sql)
	{
		if (empty($this->__parts[self::FROM])) {
			$this->__parts[self::FROM] = $this->_get_dummy_table();	
		}
		
		$from = array();

		foreach ($this->__parts[self::FROM] as $correlation_name => $table) {
			$tmp = '';
			$join_type = ($table['join_type'] === self::FROM) ? self::INNER_JOIN : $table['join_type'];
			if (!empty($from)) {
				$tmp .= ' ' . strtoupper($join_type) . ' ';	
			}	

			$tmp .= $this->_get_quoted_schema($table['schema']);
			$tmp .= $this->_get_quoted_table($table['table_name'], $correlation_name);

			if (!empty($from) && !empty($table['join_condition'])) {
				$tmp .= ' ' . self::SQL_ON . ' ' . $table['join_condition'];	
			}

			$from[] = $tmp;
		}

		if (!empty($from)) {
			$sql .= ' ' . self::SQL_FROM . ' ' . implode("\n", $from);	
		}

		return $sql;
	}

	// }}}
	// {{{ protected function _render_union()

	/**
	 * 拼装 UNION 子句 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string
	 */
	protected function _render_union($sql)
	{
		if ($this->__parts[self::UNION]) {
			$parts = count($this->__parts[self::UNION]);
			foreach ($this->__parts[self::UNION] as $cnt => $union) {
				list($target, $type) = $union;
				if ($target instanceof sw_select) {
					$target = $target->assemble();	
				}

				$sql .= $target;
				if ($cnt < $parts - 1) {
					$sql .= ' ' . $type . ' ';	
				}
			}
		}

		return $sql;
	}

	// }}}
	// {{{ protected function _render_where()

	/**
	 * 拼装 WHERE 子句 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string
	 */
	protected function _render_where($sql)
	{
		if ($this->__parts[self::FROM] && $this->__parts[self::WHERE]) {
			$sql .= ' ' . self::SQL_WHERE . ' ' . implode(' ', $this->__parts[self::WHERE]);	
		}

		return $sql;
	}

	// }}}
	// {{{ protected function _render_group()

	/**
	 * 拼装 GROUP 子句 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string
	 */
	protected function _render_group($sql)
	{
		if ($this->__parts[self::FROM] && $this->__parts[self::GROUP]) {
			$group = array();
			foreach ($this->__parts[self::GROUP] as $term) {
				$group[] = $this->__adapter->quote_indentifier($term, true);	
			}
			$sql .= ' ' . self::SQL_GROUP_BY . ' ' . implode(",\n\t", $group);
		}

		return $sql;
	}

	// }}}
	// {{{ protected function _render_having()

	/**
	 * 拼装 HAVING 子句 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string
	 */
	protected function _render_having($sql)
	{
		if ($this->__parts[self::FROM] && $this->__parts[self::HAVING]) {
			$sql .= ' ' . self::SQL_HAVING . ' ' . implode(' ', $this->__parts[self::HAVING]);	
		}

		return $sql;
	}

	// }}}
	// {{{ protected function _render_order()

	/**
	 * 拼装 ORDER 子句 
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _render_order($sql)
	{
		if ($this->__parts[self::ORDER]) {
			$order = array();
			foreach ($this->__parts[self::ORDER] as $term) {
				if (is_array($term)) {
					if (is_numeric($term[0]) && strval(intval($term[0])) == $term[0]) {
						$order[] = (int) trim($term[0]) . ' ' . $term[1];	
					} else {
						$order[] = $this->__adapter->quote_indentifier($term[0], true) . ' ' . $term[1];	
					}
				} else if (is_numeric($term) && strval(intval($term)) == $term) {
					$order[] = (int)trim($term);
				} else {
					$order[] = $this->__adapter->quote_indentifier($term, true);	
				}
			}

			$sql .= ' ' . self::SQL_ORDER_BY . ' ' . implode(', ', $order);
		}	

		return $sql;
	}

	// }}}
	// {{{ protected function _render_limit_offset()

	/**
	 * 拼装 LIMIT 子句 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string
	 */
	protected function _render_limit_offset($sql)
	{
		$count = 0;
		$offset = 0;
		
		if (!empty($this->__parts[self::LIMIT_OFFSET])) {
			$offset = (int) $this->__parts[self::LIMIT_OFFSET];
			$count  = PHP_INT_MAX;	
		}	

		if (!empty($this->__parts[self::LIMIT_COUNT])) {
			$count = (int) $this->__parts[self::LIMIT_COUNT];	
		}

		if ($count > 0) {
			$sql = trim($this->__adapter->limit($sql, $count, $offset));	
		}

		return $sql;
	}

	// }}}
	// {{{ protected function _render_for_update()

	/**
	 * 拼装 FOR UPDATE 子句 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string
	 */
	protected function _render_for_update($sql)
	{
		if ($this->__parts[self::FOR_UPDATE]) {
			$sql .= ' ' . self::FOR_UPDATE;	
		}

		return $sql;
	}

	// }}}
	// }}}
}
