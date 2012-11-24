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
 
require_once PATH_SWAN_LIB . 'condition/sw_condition_adapter_abstract.class.php';

/**
+------------------------------------------------------------------------------
* sw_condition_adapter_get_abstract 
+------------------------------------------------------------------------------
* 
* @uses sw_condition_adapter_abstract
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_condition_adapter_get_abstract extends sw_condition_adapter_abstract
{
	// {{{ members

	/**
	 * 默认允许的参数数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__default_allow_params = array(
		'columns'    => true,
		'distinct'   => true,
		'group'	     => true,
		'order'      => true,
		'limit'      => true,
		'limit_page' => true,
		'where'      => true,

		'is_count'   => true,
		'is_fetch'   => true,
	);
	
	/**
	 * 各个表的字段
	 * 格式：
	 * <code> 
	 *  'field1' => 'table1',
	 *  'field2' => 'table2'
	 * ...
	 * </code>
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__columns = array();

	// }}}
	// {{{ functions
	// {{{ public function columns()

	/**
	 * 获得字段参数 
	 * 
	 * @param string $table_name 
	 * @access public
	 * @return array
	 */
	public function columns($table_name = null)
	{
		$params = $this->__params;
		if (isset($params['is_count']) && $params['is_count']) {
			return null;	
		}

		$columns = $this->__columns;
		
		if (isset($table_name)) { //过滤出指定的表的字段
			$columns = array_intersect($columns, array($table_name));
		} 
		$columns = array_keys($columns);

		if (!isset($params['columns']) || '*' == $params['columns']) { //未指定字段或获取全部
			if (!isset($table_name)) {
				return null;	
			}
			return $columns ? $columns : '*';
		}

		if (isset($table_name)) {
			return array_intersect((array) $params['columns'], $columns);	
		}

		return array_diff((array) $params['columns'], $columns);
	}

	// }}}
	// {{{ public function params()

	/**
	 * 获取参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function params()
	{
		$params = $this->__params;
		if (!isset($params['columns'])) {
			$params['columns'] = '*';	
		}
		
		return $params;
	}

	// }}}
	// {{{ public function  check_params()

	/**
	 * 检查参数 
	 * 
	 * @access public
	 * @return void
	 */
	public function check_params()
	{
		if (isset($this->__params['limit'])) {
			if (!isset($this->__params['limit']['count'])) {
				require_once PATH_SWAN_LIB . 'condition/sw_condition_adapter_exception.class.php';
				throw new sw_condition_adapter_exception('limit count required');		
			}	

			if (!isset($this->__params['limit']['offset'])) {
				$this->__params['limit']['offset'] = null;	
			}
		}	

		if (isset($this->__params['limit_page'])) {
			if (!isset($this->__params['limit_page']['page'])
					|| !isset($this->__params['limit_page']['rows_count'])) {
				require_once PATH_SWAN_LIB . 'condition/sw_condition_adapter_exception.class.php';
				throw new sw_condition_adapter_exception('limit_page `page` and `rows_count` required');		
			}	
		}

		parent::check_params();
	}

	// }}}
	// {{{ protected function _table_name()

	/**
	 * 获取表的别名 
	 * 
	 * @param string $field 
	 * @access protected
	 * @return string
	 */
	protected function _table_name($field)
	{
		return isset($this->__columns[$field]) ? $this->__columns[$field] : '';	
	}

	// }}}
	// }}}	
}
