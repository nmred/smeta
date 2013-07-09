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

	const INNER_JOIN     = 'inner_join';
	const LEFT_JOIN      = 'left_join';
	const RIGHT_JOIN     = 'right_join';
	const FULL_JOIN      = 'full_join';
	const CROSS_JOIN     = 'cross_join';
	const NATURAL_JOIN   = 'natural_join';

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
	protected $__union_types = array(
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
			$correlation_name_keys = array_keys($this->__parts[self::FROM]);				$correlation_name = current($correlation_name_keys);
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
	// {{{ public function assemble()

	public function assemble()
	{
		//todo
		return 'test todo';	
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
	// }}}	
}
