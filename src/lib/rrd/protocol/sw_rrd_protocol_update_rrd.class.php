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
 
require_once PATH_SWAN_LIB . 'sw_orm.class.php';
require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_abstract.class.php';

/**
+------------------------------------------------------------------------------
* 更新rrd数据库
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_rrd_protocol_update_rrd extends sw_rrd_protocol_abstract
{
	// {{{ members

	/**
	 * project 对象 
	 * 
	 * @var sw_rrd_project
	 * @access protected
	 */
	protected $__project = null;

	/**
	 * update time 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__update_time = 'N';

	/**
	 * 更新字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__fields = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造函数
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct(sw_rrd_project $project)
	{
		$this->__project = $project;
		parent::__construct();
	}

	// }}}
	// {{{ public function update()

	/**
	 * 更新rrd数据库 
	 * 
	 * @access public
	 * @return void
	 */
	public function update()
	{
		if (!isset($this->__fields) || !is_array($this->__fields)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('not data source need update. ');	
		}

		$rrd_update_template = '';
		$rrd_update_value = $this->__update_time . ':';
		foreach ($this->__fields as $field => $value) {
			$rrd_update_template .= $field . ':';
			$rrd_update_value .= $value . ':';
		}

		$rrd_update_template = rtrim($rrd_update_template, ':');
		$rrd_update_value = rtrim($rrd_update_value, ':');

		$string = sprintf('update %s --template %s %s',
					$this->_get_rrd_path(),
					$rrd_update_template,
					$rrd_update_value
				);

		/*
		*/
		$return = $this->set_command($string)
			->exec();
		if (0 !== $return['res'] || 'OK' !== substr(trim($return['message']), 0, 2)) {
			require_once PATH_SWAN_LIB . 'rrd/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception("update rra database failed . ");	
		}

		return $return;
	}

	// }}}
	// {{{ public function set_update_time()

	/**
	 * 设置更新时间
	 * 
	 * @access public
	 * @return sw_rrd_protocol_update_rrd
	 */
	public function set_update_time($update_time)
	{
		if ($update_time) {
			$this->__update_time = $update_time;	
		} else {
			$this->__update_time = 'N';	
		}

		return $this;
	}

	// }}}
	// {{{ public function set_field()

	/**
	 * 设置更新的数据源
	 * 
	 * @access public
	 * @return sw_rrd_protocol_update_rrd
	 */
	public function set_field($field, $value)
	{
		if (!$this->_is_exists_ds($field)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('this data source not exists.');
		}

		if (!isset($value) || !is_numeric($value)) {
			$this->__fields[$field] = 'U';	
		} else {
			$this->__fields[$field] = $value;	
		}

		return $this;
	}

	// }}}
	// {{{ protected function _get_rrd_path()

	/**
	 * 获取 rrd 数据库路劲 
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _get_rrd_path()
	{
		$project_name = $this->__project->get_project_name();
		$device_name = $this->__project->get_device_name();

		require_once PATH_SWAN_LIB . 'sw_hash_dir.class.php';
		$rrd_name = sprintf('%s_%s.rrd', $device_name, $project_name);
		$hash_dir = sw_hash_dir::get_hash_dir($rrd_name);

		$full_path = PATH_SWAN_RRA . $hash_dir . $rrd_name; 
		
		if (!file_exists($full_path)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('rrd db not exists.');	
		}
		return $full_path;
	}

	// }}}
	// {{{ protected function _is_exists_ds()

	/**
	 * 判断给定的 数据源 名称是否存在 
	 * 
	 * @param string $ds_name 
	 * @access protected
	 * @return boolean
	 */
	protected function _is_exists_ds($ds_name)
	{
		$condition = sw_orm::condition_factory('rrd', 'rrd_ds:get_rrd_ds');
		$condition->set_eq('ds_name');
		$condition->set_ds_name($ds_name);
		$condition->set_eq('project_id');
		$condition->set_project_id($this->__project->get_project_id());
		$condition->set_is_count(true);
		$operator = sw_orm::operator_factory('rrd', 'rrd_ds');
		
		$ds_result = $operator->get_rrd_ds($condition);	

		return $ds_result ? true : false;
	}

	// }}}
	// }}}	
}