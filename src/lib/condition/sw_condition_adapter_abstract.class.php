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
* sw_condition_adapter_abstract 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_condition_adapter_abstract
{
	// {{{ const

	const QUERY_OPTS_EQ = 'eq';
	const QUERY_OPTS_IN = 'in';
	const QUERY_OPTS_LIKE = 'like';
	const QUERY_OPTS_RANGE = 'range';

	// }}}	
	// {{{ members

	/**
	 * 允许的参数数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__allow_params = array();

	/**
	 * 默认允许的参数数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__default_allow_params = array();

	/**
	 * 参数数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__params = array();

	/**
	 * 查询设置 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__query_options = array();

	/**
	 * 表别名 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__table_alias = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param array $params 
	 * @param array $query_opts 
	 * @access public
	 * @return void
	 */
	public function __construct(array $params = array(), array $query_opts = array())
	{
		$this->__allow_params = array_merge($this->__allow_params, $this->__default_allow_params);
		
		$this->set_query_opts($query_opts);
		
		if (empty($params))	{
			return;	
		}

		foreach ($this->__allow_params as $this_params => $value) {
			if (array_key_exists($this_params, $params)) {
				$method = 'set_' . $this_params;
				$this->$method($params[$this_params]);	
			}	
		}
	}

	// }}}
	// {{{ public function params()

	/**
	 * 获取所有条件参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function params()
	{
		return $this->__params;		
	}
	
	// }}}
	// {{{ public function __call()
	
	/**
	 * 重载set_xxx,get_xxx,unset_xx系列方法 
	 * 
	 * @param string $method 
	 * @param array $args 
	 * @access public
	 * @return void
	 */
	public function __call($method, array $args)
	{
		list($type, $param) = explode('_', $method, 2) + array('', '');
		
		if (!isset($this->__allow_params[$param])) {
			require_once PATH_SWAN_LIB . 'condition/sw_condition_adapter_exception.class.php';
			throw new sw_condition_adapter_exception("Unrecognized method '$method()'");	
		} 	

		switch ($type) {
			case 'set':
				if (1 !== count($args)) {
					require_once path_swan_lib . 'condition/sw_condition_adapter_exception.class.php';
					throw new sw_condition_adapter_exception("'$method()' args error");	
				}

				$this->__params[$param] = $args[0];
				break;
			case 'get':
				if (isset($this->__params[$param])) {
					return $this->__params[$param];	
				}
				break;
			case 'unset':
				unset($this->__params[$param]);
				break;
			default:
				require_once path_swan_lib . 'condition/sw_condition_adapter_exception.class.php';
				throw new sw_condition_adapter_exception("Unrecognized method '$method()'");	
		}
	}

	// }}}
	// {{{ public function check_params()

	/**
	 * 检查参数设置 
	 * 
	 * @access public
	 * @return void
	 */
	public function check_params()
	{
		if (isset($this->__query_options[self::QUERY_OPTS_RANGE])) {
			$range = $this->__query_options[self::QUERY_OPTS_RANGE];
			foreach ($range as $key => $value) {
				if (!isset($this->__params[$key])) {
					continue;	
				}	
				$data = $this->__params[$key];
				if (!is_array($data) || (!isset($data['min']) && !isset($data['max']))) {
					require_once PATH_SWAN_LIB . 'condition/sw_condition_adapter_exception.class.php';
					throw new sw_condition_adapter_exception("$key `min` or `max` required");	
				}	
			}
		}	
	}

	// }}}
	// {{{ public function check_require()

	/**
	 * 验证必须设置的值 
	 * 
	 * @param array $keys 
	 * @access public
	 * @return void
	 */
	public function check_require($keys)
	{
		foreach ((array) $keys as $key) {
			if (!isset($this->__params[$key])) {
				$msg = sprintf(gettext('`%s` is not set.'), gettext($key));
				require_once PATH_SWAN_LIB . 'condition/sw_condition_adapter_exception.class.php';
				throw new sw_condition_adapter_exception($msg);	
			}	
		}	
	}

	// }}}
	// {{{ public function set_in()

	/**
	 * 设置in条件字段 
	 * 
	 * @param array $in 
	 * @param string $table_name 
	 * @access public
	 * @return void
	 */
	public function set_in($in, $table_name = null)
	{
		$this->set_query_opt(self::QUERY_OPTS_IN, $in, $table_name);
	}

	// }}}
	// {{{ public function set_eq()

	/**
	 * 设置eq条件字段 
	 * 
	 * @param array $eq
	 * @param string $table_name 
	 * @access public
	 * @return void
	 */
	public function set_eq($eq, $table_name = null)
	{
		$this->set_query_opt(self::QUERY_OPTS_EQ, $eq, $table_name);
	}

	// }}}
	// {{{ public function set_like()

	/**
	 * 设置like条件字段 
	 * 
	 * @param array $like 
	 * @param string $table_name 
	 * @access public
	 * @return void
	 */
	public function set_like($like, $table_name = null)
	{
		$this->set_query_opt(self::QUERY_OPTS_LIKE, $like, $table_name);
	}

	// }}}
	// {{{ public function set_range()

	/**
	 * 设置range条件字段 
	 * 
	 * @param array $range
	 * @param string $table_name 
	 * @access public
	 * @return void
	 */
	public function set_range($range, $table_name = null)
	{
		$this->set_query_opt(self::QUERY_OPTS_RANGE, $range, $table_name);
	}

	// }}}
	// {{{ public function set_query_opt()

	/**
	 * 设置查询参数 
	 * 
	 * @param string $type 
	 * @param array $fields 
	 * @param string $table_name 
	 * @access public
	 * @return void
	 */
	public function set_query_opt($type, $fields, $table_name = null)
	{
		foreach ((array) $fields as $field) {
			if (isset($table_name)) {
				$this->__query_options[$type][$field] = $table_name;	
			} else {
				$this->__query_options[$type][$field] = $this->_table_name($field);	
			}

			$this->__allow_params[$field] = true;
		}	
	}

	// }}}
	// {{{ public function get_query_opts()

	/**
	 * 获取查询参数设置 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_query_opts()
	{
		return $this->__query_options;
	}

	// }}}
	// {{{ public function set_query_opts()

	/**
	 * 批量设置查询参数 
	 * 
	 * @param array $opts 
	 * @access public
	 * @return void
	 */
	public function set_query_opts($opts)
	{
		foreach ($opts as $type => $fields) {
			foreach ($fields as $field => $table_name) {
				$this->set_query_opt($type, $field);	
			}
		}
	}

	// }}}
	// {{{ public function table_alias()

	/**
	 * 获取表别名 
	 * 
	 * @param mixed $table_name 
	 * @access public
	 * @return void
	 */
	public function table_alias($table_name = null)
	{
		if (!isset($table_name)) {
			return $this->__table_alias;	
		}

		if (!isset($this->__table_alias[$table_name])) {
			return null;
		}

		return $this->__table_alias[$table_name];
	}

	// }}}
	// {{{ public function where()

	/**
	 * 拼装的where条件 
	 * 
	 * @access public
	 * @return void
	 */
	public function where($select = null, $disable_alias = true)
	{
		$db = sw_db::singleton();
		if (!isset($select)) {
			$select = $db->select();
			$is_return = true;	
		}

		$alias = $this->table_alias();

		foreach ($this->__query_options as $type => $fields) {
			foreach ($fields as $field => $tbn) {
				if (!isset($this->__params[$field])) {
					continue;	
				}	

				$value = $this->__params[$field];
				if (!$disable_alias && isset($alias[$tbn])) {
					$field = $alias[$tbn]. '.' . $field;	
				}

				switch ($type) {
					case self::QUERY_OPTS_IN:
						if (is_array($value) && empty($value)) {
							$select->where(0);	
						} else {
							$select->where($field . ' IN (?)', $value);
						}
						break;
					case self::QUERY_OPTS_LIKE:
						$value = $db->quote('%'  . $value . '%');
						$select->where($field . ' LIKE ' . $value);
						break;
					case self::QUERY_OPTS_RANGE:
						if (isset($value['min'])) {
							$select->where($field . ' >= ?', $value['min']);	
						}

						if (isset($value['max'])) {
							$select->where($field . ' <= ?', $value['max']);	
						}
						break;
					case self::QUERY_OPTS_EQ:	//此处故意省略break
					default:
						$select->where($field . ' = ?', $value);
				}
			}	
		}

		if (isset($this->__params['where']) && '' != $this->__params['where']) {
			$select->where($this->__params['where']);	
		}

		if (isset($is_return)) {
			return implode(' ', $select->get_part('where'));	
		}
	}

	// }}}
	// {{{ protected function _table_name()

	/**
	 * 根据字段获取表名 
	 * 
	 * @param mixed $field 
	 * @access protected
	 * @return void
	 */
	protected function _table_name($field)
	{
		return '';	
	}

	// }}}
	// }}}
}
