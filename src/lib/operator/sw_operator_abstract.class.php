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
 
require_once PATH_SWAN_LIB . 'operator/sw_operator_exception.class.php';

/**
+------------------------------------------------------------------------------
* sw_operator_abstract 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_operator_abstract
{
	// {{{ members

	/**
	 * DB 操作类 
	 * 
	 * @var object
	 * @access protected
	 */
	protected $__db;

	/**
	 * 各基础操作类 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__operators;

	// }}}	
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造方法 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->__db = sw_db::singleton();	
	}

	// }}}
	// {{{ public function _get()

	/**
	 * 获取列表 
	 * 
	 * @param sw_db_select $select 
	 * @param array $params 
	 * @access public
	 * @return array
	 */
	public function _get($select, $params)
	{
		if (isset($params['distinct']) && $params['distinct']) {
			$select->distinct();	
		}	

		//返回统计个数
		if (isset($params['is_count']) && $params['is_count']) {
			$select->columns('count(*)');
			return $this->__db->fetch_one($select);
		}

		if (isset($params['columns'])) {
			$select->columns($params['columns']);	
		}

		if (isset($params['group'])) {
			$select->group($params['group']);	
		}

		if (isset($params['order'])) {
			$select->order($params['order']);	
		}

		if (isset($params['limit'])) {
			$select->limit($params['limit']['count'], $params['limit']['offset']);	
		} elseif (isset($params['limit_page'])) {
			$select->limit_page($params['limit_page']['page'], $params['limit_page']['rows_count']);
		}

		if (isset($params['is_fetch']) && $params['is_fetch']) {
			return $this->__db->query($select);	
		} else {
			return $this->__db->fetch_all($select);	
		}
	}

	// }}}
	// {{{ public function _check_require()

	/**
	 * 检测必要的字段 
	 * 
	 * @param array $attributes 
	 * @param array $require_fields 
	 * @access public
	 * @return void
	 */
	public function _check_require($attributes, $require_fields = array())
	{
		foreach ($require_fields as $field) {
			if (!isset($attributes[$field])) {
				require_once PATH_SWAN_LIB . 'operator/sw_operator_exception.class.php';
				throw new sw_operator_exception("must given $field");	
			}
		}	
	}

	// }}}
	// {{{ public function _operator()

	/**
	 * 获取各基础操作类 
	 * 
	 * @param string $cate 
	 * @param string $type 
	 * @access public
	 * @return sw_operator_abstract
	 */
	public function _operator($cate, $type)
	{
		if (!isset($this->__operators[$cate . ':' . $type])) {
			$class = $cate . '/' . 'sw_operator_' . $type;
			require_once PATH_SWAN_LIB . $class . '.class.php';
			$this->__operators[$cate . ':' . $type] = new $class;
		}

		return $this->__operators[$cate . ':' . $type];
	}

	// }}}
	// }}}
}
