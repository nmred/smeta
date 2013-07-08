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
	// {{{ public function 
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
	// {{{ public function assemble()

	public function assemble()
	{
		//todo
		return 'test todo';	
	}

	// }}}
	// }}}	
}
