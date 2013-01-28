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
* 修改 rrd 数据库
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_rrd_protocol_tune_rrd extends sw_rrd_protocol_abstract
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
	 * 更新字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__update_attr = array();

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
	// {{{ public function tune()

	/**
	 * 修改 rrd 数据库 
	 * 
	 * @access public
	 * @return void
	 */
	public function tune()
	{
		if (!isset($this->__update_attr) || !is_array($this->__update_attr)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('not data source need alter. ');	
		}

		$rrd_tune = 'tune ' .  $this->_get_rrd_path() . ' ';

		if (isset($this->__update_attr['heartbeat'])) {
			$rrd_tune .= sprintf(' --heartbeat %s:%s', $this->__update_attr['data_source'], $this->__update_attr['heartbeat']);	
		}

		if (isset($this->__update_attr['rename'])) {
			$rrd_tune .= sprintf(' --data-source-rename %s:%s', $this->__update_attr['data_source'], $this->__update_attr['rename']);	
		}

		if (isset($this->__update_attr['minimum'])) {
			$rrd_tune .= sprintf(' --minimum %s:%s', $this->__update_attr['data_source'], $this->__update_attr['minimum']);	
		}

		if (isset($this->__update_attr['maximum'])) {
			$rrd_tune .= sprintf(' --maximum %s:%s', $this->__update_attr['data_source'], $this->__update_attr['maximum']);	
		}

		if (isset($this->__update_attr['data_source_type'])) {
			$rrd_tune .= sprintf(' --data-source-type %s:%s', $this->__update_attr['data_source'], $this->__update_attr['data_source_type']);	
		}

		$return = $this->set_command($rrd_tune)
			->exec();
		if (0 !== $return['res'] || 'OK' !== substr(trim($return['message']), 0, 2)) {
			require_once PATH_SWAN_LIB . 'rrd/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception("alter rra database failed . ");	
		}

		return $return;
	}

	// }}}
	// {{{ public function set_data_source()

	/**
	 * 设置将要更新的数据源参数 
	 * 
	 * @param string $old_ds 
	 * @param string $options 
	 * 
	 *  array (
	 *	'rename' => 'new_ds_name',
	 *	'heartbeat' => 
	 *	'minimum' =>
	 *	'maximum' =>
	 *	'data_source_type' =>
	 *	'data_source_rename' =>
	 *   );
	 * @access public
	 * @return sw_rrd_protocol_tune_rrd
	 */
	public function set_data_source($old_ds, $options)
	{
		if (!$this->_is_exists_ds($old_ds)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('this data source not exists.');
		}
		
		$this->__update_attr = $options;
		$this->__update_attr['data_source'] = $old_ds;

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
