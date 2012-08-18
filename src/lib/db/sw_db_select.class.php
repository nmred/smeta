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
 
require_once PATH_SWAN_LIB . 'db/sw_db_adapter_abstract.class.php';
require_once PATH_SWAN_LIB . 'db/sw_db_expr.class.php';
/**
+------------------------------------------------------------------------------
* sw_db_select 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_db_select
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
	const LIMIT_COUNT    = 'limitcount';
	const LIMIT_OFFSET   = 'limitoffset';
	const FOR_UPDATE     = 'forupdate';

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

	// }}} end consts
	// {{{ members

	/**
	 * 绑定的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bind = array();

	/**
	 * sw_db_adapter_abstract object 
	 * 
	 * @var sw_db_adapter_abstract
	 * @access protected
	 */
	protected $__adapter;

	/**
	 *  sql语句组装初始化顺序 
	 * 
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
		self::LIMIT_COUNT  => null,
		self::LIMIT_OFFSET => null,
		self::FOR_UPDATE   => false,
	);
		
	/**
	 *关连查询类型
	 * 
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
	 *联合类型
	 * 
	 * @access protected
	 */
	protected static $__union_types = array(
		self::SQL_UNION,
		self::SQL_UNION_ALL,
	);
	
	/**
	 * __parts 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__parts = array();

	/**
	 * __table_cols 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__table_cols = array();

	// }}} end members
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param sw_db_adapter_abstract $adapter 
	 * @access public
	 * @return void
	 */
	public function __construct(sw_db_adapter_abstract $adapter)
	{
		$this->__adapter = $adapter;
		$this->__parts = self::$__parts_init;
	}

	// }}}
	// {{{ public function get_bind()
	
	/**
	 * 获取绑定的变量 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_bind()
	{
		return $this->__bind;	
	}

	// }}}
	// {{{ publicc function bind()

	/**
	 * 绑定参数 
	 * 
	 * @param mixed $bind 
	 * @access public
	 * @return sw_db_select
	 */
	public function bind($bind)
	{
		$this->__bind = $bind;

		return $this;
	}

	// }}}
	// {{{ public function distinct()

	/**
	 * 组装DISTINCT查询语句 
	 * 
	 * @param boolean $flag 
	 * @access public
	 * @return sw_db_select
	 */
	public function distinct($flag = true) 
	{
		$this->__parts[self::DISTINCT] = (bool) $flag;
		return $this;	
	}

	// }}}
	// {{{ public function from()

	/**
	 * from 
	 * 
	 * @param array|string|sw_db_expr $name 
	 * @param array|string|sw_db_expr $cols 
	 * @param string $schema 
	 * @access public
	 * @return sw_db_select
	 */
	public function from($name, $cols = '*', $schema = null)
	{
		return $this->__join(self::FROM, $name, null, $cols, $schema);	
	}

	// }}}
	// {{{ public function columns()

	/**
	 * columns 
	 * 
	 * @param array|string|sw_db_expr $cols 
	 * @param string $correlation_name 
	 * @access public
	 * @return sw_db_select
	 */
	public function columns($cols = '*', $correlation_name = null)
	{
		if ($correlation_name === null && count($this->__parts[self::FROM])) {
			$correlation_name_keys = array_keys($this->__parts[self::FROM]);
			$correlation_name = current($correlation_name_keys);	
		}
		if (!array_key_exists($correlation_name, $this->__parts[self::FROM])) {
			require_once PATH_SWAN_LIB . 'db/sw_db_select_exception.class.php';
			throw new sw_db_select_exception("No table has been specified for the FROM clause");	
		}

		$this->_table_cols($correlation_name, $cols);

		return $this;
	}

	// }}}
	// {{{ public function union()

	/**
	 * union 
	 * 
	 * <code>
	 * $sql1 = $db->select();
	 * $sql2 = "SELECT ...";
	 * $select = $db->select()
	 *      ->union(array($sql1, $sql2))
	 *      ->order("id");
	 * </code>
	 *
	 * @param array $select 
	 * @param mixed $type 
	 * @access public
	 * @return sw_db_select
	 */
	public function union($select = array(), $type = self::SQL_UNION)
	{
		if (!is_array($select)) {
			require_once PATH_SWAN_LIB . 'sw_db_select_exception.class.php';
			throw new sw_db_select_exception(
				 "union() only accepts an array of sw_db_select instances of sql query strings."
			);
		}	
		if (!in_array($type, self::$__union_types)) {
			require_once PATH_SWAN_LIB . 'sw_db_select_exception.class.php';
			throw new sw_db_select_exception("Invalid union type '{$type}'");	
		}

		foreach ($select as $target) {
			$this->__parts[self::UNION][] = array($target, $type);	
		}

		return $this;
	}

	// }}}
	// {{{ public function join()

	/**
	 * join 
	 * 
	 * @param array|string|sw_db_expr $name 
	 * @param string  $cond 
	 * @param array|string $cols 
	 * @param string $schema 
	 * @access public
	 * @return sw_db_select
	 */
	public function join($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->join_inner($name, $cond, $cols, $schema);	
	}

	// }}}
	// {{{ pubcli function join_inner()
	
	/**
	 * join_inner 
	 * 
	 * @param array|string|sw_db_expr $name 
	 * @param string $cond 
	 * @param array|string $cols 
	 * @param string $schema 
	 * @access public
	 * @return sw_db_select
	 */
	public function join_inner($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::INNER_JOIN, $name, $cond, $cols, $schema);	
	}

	// }}}
	// {{{ public function join_left()

	/**
	 * join_left 
	 * 
	 * @param array|string|sw_db_expr $name 
	 * @param string $cond 
	 * @param array|string $cols 
	 * @param string $schema 
	 * @access public
	 * @return sw_db_select
	 */
	public function join_left($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::LEFT_JOIN, $name, $cond, $cols, $schema);	
	}

	// }}}
	// {{{ public function join_right()

	/**
	 * join_right
	 * 
	 * @param array|string|sw_db_expr $name 
	 * @param string $cond 
	 * @param array|string $cols 
	 * @param string $schema 
	 * @access public
	 * @return sw_db_select
	 */
	public function join_right($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::RIGHT_JOIN, $name, $cond, $cols, $schema);	
	}

	// }}}
	// {{{ public function join_full()

	/**
	 * join_full
	 * 
	 * @param array|string|sw_db_expr $name 
	 * @param string $cond 
	 * @param array|string $cols 
	 * @param string $schema 
	 * @access public
	 * @return sw_db_select
	 */
	public function join_full($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::FULL_JOIN, $name, $cond, $cols, $schema);	
	}

	// }}}
	// {{{ public function join_cross()

	/**
	 * join_cross
	 * 
	 * @param array|string|sw_db_expr $name 
	 * @param string $cond 
	 * @param array|string $cols 
	 * @param string $schema 
	 * @access public
	 * @return sw_db_select
	 */
	public function join_cross($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::CROSS_JOIN, $name, $cond, $cols, $schema);	
	}

	// }}}
	// {{{ public function join_natural()

	/**
	 * join_natural
	 * 
	 * @param array|string|sw_db_expr $name 
	 * @param string $cond 
	 * @param array|string $cols 
	 * @param string $schema 
	 * @access public
	 * @return sw_db_select
	 */
	public function join_natural($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
	{
		return $this->_join(self::NATURAL_JOIN, $name, $cond, $cols, $schema);	
	}

	// }}}
	// {{{ public function where()
	
	/**
	 * 添加一个where条件（AND） 
	 * 
	 * <code>
	 * //简单的方式，但不安全
	 * $select->where("id = $id");
	 *
	 * //通过名字绑定
	 * $select->where('id = :id');
	 * 
	 * //通过占位符
	 * $select->where('id = ?', $id)
	 *
	 * </code>
	 * <code>
	 * $db->fetch_all($select, array('id' => 5));
	 * </code>
	 *
	 * @param string $cond 
	 * @param mixed $value 
	 * @param int $type 
	 * @access public
	 * @return sw_db_select
	 */
	public function where($cond, $value = null, $type = null)
	{
		$this->__parts[self::WHERE][] = $this->_where($cond, $value, $type, true);	
	}

	// }}}
	// {{{ public function or_where()

	/**
	 * 添加一个where条件(OR) 
	 * 
	 * @param string $cond 
	 * @param mixed $value 
	 * @param int $type 
	 * @access public
	 * @return sw_db_select
	 * @see
	 */
	public function or_where($cond, $value = null, $type = null)
	{
		$this->__parts[self::WHERE][] = $this->_where($cond, $value, $type, false);
		
		return $this;	
	}

	// }}}
	// {{{ public function group()

	/**
	 * 为查询语句添加分组 
	 * 
	 * @param array|string $spec 
	 * @access public
	 * @return sw_db_select
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
	 * 添加having条件语句，并且是AND的条件 
	 * 
	 * @param string $cond 
	 * @param mixed $value 
	 * @param int $type 
	 * @access public
	 * @return sw_db_select
	 */
	public function having($cond, $value = null, $type = null)
	{
		if ($value !== null) {
			$cond = $this->__adapter->quote_into($cond, $value, $type);	
		}

		if ($this->__parts[self::HAVING]) {
			$this->__parts[self::HAVING][] = self::SQL_AND . " ($cond)";	
		} else {
			$this->__parts[self::HAVING][] = " ($cond)";	
		}

		return $this;
	}

	// }}}
	// {{{ public function or_having()

	/**
	 * 添加having条件语句，并且是OR的条件 
	 * 
	 * @param string $cond 
	 * @param mixed $value 
	 * @param int $type 
	 * @access public
	 * @return sw_db_select
	 */
	public function or_having($cond, $value = null, $type = null)
	{
		if ($value !== null) {
			$cond = $this->__adapter->quote_into($cond, $value, $type);	
		}

		if ($this->__parts[self::HAVING]) {
			$this->__parts[self::HAVING][] = self::SQL_OR . " ($cond)";	
		} else {
			$this->__parts[self::HAVING][] = " ($cond)";	
		}

		return $this;
	}

	// }}}
	// {{{ public function order()

	/**
	 * 排序 
	 * 
	 * @param string|array|sw_db_expr $spec 
	 * @access public
	 * @return sw_db_select
	 */
	public function order($spec)
	{
		if (!is_array($spec)) {
			$spec = array($spec);	
		}

		//可以强制加入ASC和DESC的修饰符，默认是ASC
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
				if (preg_match('/(.*\W)(' . self::SQL_ASC . '|' . self::SQL_DESC .  ')\b/si', $val, $matches)) {
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
	 * limit 
	 * 
	 * @param int $count 
	 * @param int $offset 
	 * @access public
	 * @return sw_db_select
	 */
	public function limit($count = null, $offset = null)
	{
		$this->__parts[self::LIMIT_COUNT] = (int) $count;
		$this->__parts[self::LIMIT_OFFSET] = (int) $offset;
		return $this;	
	}

	// }}}
	// {{{ public function limit_page()

	/**
	 * limit_page 
	 * 
	 * @param int $page 
	 * @param int $row_count 
	 * @access public
	 * @return sw_db_select
	 */
	public function limit_page($page, $row_count)
	{
		$page      = ($page > 0)      ? $page      : 1;
		$row_count = ($row_count > 0) ? $row_count : 1;
		$this->__parts[self::LIMIT_COUNT]  = (int) $row_count;
		$this->__parts[self::LIMIT_OFFSET] = (int) $row_count * ($page - 1);
		return $this; 	
	}

	// }}}
	// {{{ public function for_update()

	/**
	 * for_update 
	 * 
	 * @param boolean $flag 
	 * @access public
	 * @return sw_db_select
	 */
	public function for_update($flag = true)
	{
		$this->__parts[self::FOR_UPDATE] = (bool) $flag;
		return $this;
	}

	// }}}
	// {{{ public function get_part()

	/**
	 * get_part 
	 * 
	 * @param string $part 
	 * @access public
	 * @return mixed
	 */
	public function get_part($part)
	{
		$part = strtolower($part);
		if (!array_key_exists($part, $this->__parts)) {
			require_once PATH_SWAN_LIB . 'db/sw_db_select_exception.class.php';
			throw new sw_db_select_exception("Invalid Select part '$part'");	
		}	
		return $this->__parts[$part];
	}

	// }}}
	// {{{ public function query()

	/**
	 * query 
	 * 
	 * @param integer $fetch_mode 
	 * @param array $bind
	 * @access public
	 * @return sw_db_statement_standard| PDO_Statement
	 */
	public function query($fetch_mode = null, $bind = array())
	{	
		if (!empty($bind)) {
			$this->bind($bind);	
		}

		$stmt = $this->__adapter->query($this);
		if ($fetch_mode === null) {
			$fetch_mode = $this->__adapter->get_fetch_mode();	
		}
		$stmt->set_fetch_mode($fetch_mode);
		return $stmt;
	}
	// }}}
	// {{{ public funciton assemble()
	
	/**
	 * 解析SQL语句 
	 * 
	 * @access public
	 * @return string
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
	 * 重置sw_db_select对象 
	 * 
	 * @param string $part 
	 * @access public
	 * @return sw_db_select
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
	// {{{ public function get_adapter()

	/**
	 * 获取sw_db_adapter_abstract 
	 * 
	 * @access public
	 * @return sw_db_adapter_abstract
	 */
	public function get_adapter()
	{
		return $this->__adapter;	
	}

	// }}}
	// {{{ protected function _join()

	/**
	 * _join 
	 * 
	 * @param null|string $type 
	 * @param array|string|sw_db_expr $name 
	 * @param string $cond 
	 * @param array|string $cols 
	 * @param string $schema 
	 * @access protected
	 * @return sw_db_select
	 * @throws sw_db_select_exception
	 */
	protected function _join($type, $name, $cond, $cols, $schema = null)
	{
		if (!in_array($type, self::$__join_types) && $type != self::FROM) {
			require_once PATH_SWAN_LIB . 'db/sw_db_select_exception.class.php';
			throw new sw_db_select_exception("Invalid join type '$type'");
		}	
		if (empty($name)) {
			$correlation_name = $table_name = '';	
		} else if (is_array($name)) {
			// Must be array($correlation_name => $table_name) or array($ident, ...)
			foreach ($name as $_correlation_name => $_table_name) {
				if (is_string($_correlation_name)) {
					$table_name = $_table_name;
					$correlation_name = $_correlation_name;	
				} else {
					$table_name = $_table_name;
					$correlation_name = $this->_unique_correlation($table_name);	
				}
				break;
			}	
		} else if ($name instanceof sw_db_expr || $name instanceof sw_db_select) {
			$table_name = $name;
			$correlation_name = $this->_unique_correlation('t');	
		} else if (preg_match('/^(.+)\s+AS\s+(.+)$/i', $name, $m)) {
			$table_name = $m[1];
			$correlation_name = $m[2];	
		} else {
			$table_name = $name;
			$correlation_name = $this->_unique_correlation($table_name);	
		}

		// Schema from table name overrides schema argument
		if (!is_object($table_name) && false !== strpos($table_name, '.')) {
			list($schema, $table_name) = explode('.', $table_name);	
		}

		$last_from_correlation_name = null;
		if (!empty($correlation_name)) {
			if (array_key_exists($correlation_name, $this->__parts[self::FROM])) {
				require_once PATH_SWAN_LIB . 'db/sw_db_select_exception.class.php';
				throw new sw_db_select_exception("You cannot define a correlation name '$correlation_name' more than once");
			}	
			if ($type == self::FROM) {
				$tmp_from_parts = $this->__parts[self::FROM];
				$this->__parts[self::FROM] = array();
				while ($tmp_from_parts) {
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
		if ($type == self::FROM && $last_from_correlation_name == null) {
			$last_from_correlation_name = true;	
		}
		$this->_table_cols($correlation_name, $cols, $last_from_correlation_name);
		return $this;
	}

	// }}}
	// {{{ public function join_using()

	/**
	 * join_using 
	 * 
	 * <code>
	 * $select = $db->select()->from('table1')
	 *						  ->join_using('table2', 'column1');
	 * </code>
	 * @param mixed $type 
	 * @param mixed $name 
	 * @param mixed $cond 
	 * @param string $cols 
	 * @param mixed $schema 
	 * @access protected
	 * @return sw_db_select
	 */
	public  function join_using($type, $name, $cond, $cols = '*', $schema = null)
	{
		if (empty($this->__parts[self::FROM])) {
			require_once PATH_SWAN_LIB . 'db/sw_db_select_exception.class.php';
			throw new sw_db_select_exception("You can only perform a joinUsing after specifying a FROM table");	
		}

		$join = $this->__adapter->quote_identifier(key($this->__parts[self::FROM]), true);
		$from = $this->__adapter->quote_identifier($this->_unique_correlation($name), true);

		$cond1 = $from . '.' . $cond;
		$cond2 = $join . '.' . $cond;
		$cond = $cond1 . ' =' . $cond2;

		return $this->_join($type, $name, $cond, $cols, $schema);
	}
		
	// }}}
	// {{{ private function _unique_correlation()

	/**
	 * _unique_correlation 
	 * 
	 * @param string|array $name 
	 * @access private
	 * @return string
	 */
	private function _unique_correlation($name)
	{
		if (is_array($name)) {
			$k = key($name);
			$c = is_string($k) ? $k : end($name);	
		} else {
			$dot = strrpos($name, '.');
			$c = ($dot === false) ? $name : substr($name, $dot + 1);
		}
		for ($i = 2; array_key_exists($c, $this->__parts[self::FROM]); ++$i) {
			$c = $name . '_' . (string) $i;	
		}
		return $c;
	}

	// }}}
	// {{{ protected function _table_cols()

	/**
	 * _table_cols 
	 * 
	 * @param string $correlation_name 
	 * @param array|string $cols 
	 * @param string|boolean $after_correlation_name 
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
			if (is_string($col)) {
				if (preg_match('/^(.+)\s+' .  self::SQL_AS . '\s+(.+)$/i', $col, $m)) {
					$col = $m[1];
					$alias = $m[2];	
				}
				if (preg_match('/\(.*\)/', $col)) {
					$col = new sw_db_expr($col);	
				} elseif (preg_match('/(.+)\.(.+)/', $col, $m)) {
					$current_correlation_name = $m[1];
					$col = $m[2];	
				}
			}
			$column_values[] = array($current_correlation_name, $col, is_string($alias) ? $alias : null);
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

			while($tmp_columns) {
				array_push($this->__parts[self::COLUMNS], array_shift($tmp_columns));	
			}
		}
	}

	// }}}
	// {{{ protected function _where()

	/**
	 * _where 
	 * 
	 * @param string $condition 
	 * @param mixed $value 
	 * @param string $type 
	 * @param boolean $bool 
	 * @access protected
	 * @return string clause
	 */
	protected function _where($condition, $value = null, $type = null, $bool = true)
	{
		if (count($this->__parts[self::UNION])) {
			require_once PATH_SWAN_LIB . 'db/sw_db_select_exception.class.php';
			throw new sw_db_select_exception("Invalid use of where clause with " . self::SQL_UNION);	
		}

		if ($value !== null) {
			$condition = $this->__adapter->quote_into($condition, $value, $type);	
		}

		$cond = "";
		if ($this->__parts[self::WHERE]) {
			if ($bool === true) {
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
	 * _get_dummy_table 
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
	 * _get_quoted_schema 
	 * 
	 * @param mixed $schema 
	 * @access protected
	 * @return string|null
	 */
	protected function _get_quoted_schema($schema = null)
	{
		if ($schema === null) {
			return null;	
		}	
		return $this->__adapter->quote_identifier($schema, true) . '.';
	}

	// }}}
	// {{{ protected function _get_quoted_table()

	/**
	 * _get_quoted_table 
	 * 
	 * @param string $table_name 
	 * @param string $correlation_name 
	 * @access protected
	 * @return string
	 */
	protected function _get_quoted_table($table_name, $correlation_name = null)
	{
		return $this->__adapter->quote_table_as($table_name, $correlation_name, true);	
	}

	// }}}
	// {{{ protected function _render_distinct()
	
	/**
	 * _render_distinct 
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
	 * _render_columns 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string|null
	 */
	protected function _render_columns($sql)
	{
		if (!count($this->__parts[self::COLUMNS])) {
			return null;	
		}	

		$columns = array();
		foreach ($this->__parts[self::COLUMNS]  as $column_entry) {
			list($correlation_name, $column, $alias) = $column_entry;
			if ($column instanceof sw_db_expr) {
				$columns[] = $this->__adapter->quote_column_as($column, $alias, true);	
			} else {
				if ($column == self::SQL_WILDCARD) {
					$column = new sw_db_expr(self::SQL_WILDCARD);
					$alias = null;	
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
	 * _render_from 
	 * 
	 * @param string $sql 
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
			$join_type = ($table['join_type'] == self::FROM) ? self::INNER_JOIN : $table['join_type'];
			
			if (! empty($from)) {
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
	 * _render_union 
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
				if ($target instanceof sw_db_select) {
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
	 * _render_where 
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
	 * _render_group 
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
				$group[] = $this->__adapter->quote_identifier($term, true);	
			}
			$sql .= ' '. self::SQL_GROUP_BY . ' ' . implode(",\n\t", $group);
		}		

		return $sql;
	}

	// }}}
	// {{{ protected function _render_having()

	/**
	 * _render_having 
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
	 * _render_order 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string
	 */
	protected function _render_order($sql)
	{
		if ($this->__parts[self::ORDER]) {
			$order = array();
			foreach ($this->__parts[self::ORDER]  as $term) {
				if (is_array($term)) {
					if (is_numeric($term[0]) && strval(intval($term[0])) == $term[0]) {
						$order[] = (int)trim($term[0]) . ' ' . $term[1];	
					} else {
						$order[] = $this->__adapter->quote_identifier($term[0], true) . ' ' . $term[1];	
					}
				} else if (is_numeric($term) && strval(intval($term)) == $term) {
					$order[] = (int)trim($term);	
				} else {
					$order[] = $this->__adapter->quote_identifier($term, true);
				}
			}	
			$sql .= ' ' . self::SQL_ORDER_BY . ' ' . implode(', ', $order);
		}	

		return $sql;
	}

	// }}}
	// {{{ protected function _render_limit_offset()
	
	/**
	 * _render_limit_offset 
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
			$count = PHP_INI_MAX;	
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
	 * _render_for_update 
	 * 
	 * @param string $sql 
	 * @access protected
	 * @return string
	 */
	protected function _render_for_update($sql)
	{
		if ($this->__parts[self::FOR_UPDATE]) {
			$sql .= ' ' . self::SQL_FOR_UPDATE;	
		}	

		return $sql;
	}

	// }}}
	// {{{ public function __call()

	/**
	 * __call 
	 * 
	 * @param string $method 
	 * @param array $args 
	 * @access public
	 * @return void
	 */
	public function __call($method, array $args)
	{
		$matches = array();
		
		if (preg_match('/^join_([a-zA-Z]*?)_using$/', $method, $matches)) {
			$type = strtoupper($matches[1]);
			if ($type) {
				$type .= ' join';
				if (!in_array($type, self::$__join_types)) {
					require_once PATH_SWAN_LIB . 'db/sw_db_select_exception.class.php';
					throw new sw_db_select_exception("Unrecognized method '$method()'");	
				}
				if (in_array($type, array(self::CROSS_JOIN, self::NATURAL_JOIN))) {
					require_once PATH_SWAN_LIB . 'db/sw_db_select_exception.class.php';
					throw new sw_db_select_exception("Cannot perform a joinUsing with method '$method()'");	
				}
			} else {
				$type = self::INNER_JOIN;	
			}
			array_shift($args, $type);
			return call_user_func_array(array($this, 'join_using'), $args);
		}	

		require_once PATH_SWAN_LIB . 'db/sw_db_select_exception.class.php';
		throw new sw_db_select_exception("Unrecognized method '$method()'");
	}

	// }}}
	// {{{ public function __toString()

	/**
	 * __toString 
	 * 
	 * @access public
	 * @return void
	 */
	public function __toString()
	{
		try {
			$sql = $this->assemble();	
		} catch (Exception $e) {
			trigger_error($e->getMessage(), E_USER_WARNING);
			$sql = '';
		}
		return (string)$sql;
	}

	// }}}
	// }}} end functions
}
