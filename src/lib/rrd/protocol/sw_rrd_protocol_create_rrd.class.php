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
* 创建rrd数据库
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_rrd_protocol_create_rrd extends sw_rrd_protocol_abstract
{
	// {{{ members

	/**
	 * 数据源计算规则 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__ds_dst = array (
		'GAUGE',
		'COUNTER',
		'DERIVE',
		'ABSOLUTE',
	);

	/**
	 * 归档运算法则 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__rra_cf = array(
		'AVERAGE',
		'MIN',
		'MAX',
		'LAST',
	);

	// }}}
	// {{{ functions
	// {{{ public function create_rrd()

	/**
	 * 创建rrd数据库 
	 * 
	 * @param sw_rrd_project $project 
	 * @param boolean $recover 
	 * @access public
	 * @return void
	 */
	public function create(sw_rrd_project $project, $recover = false)
	{
		$condition = sw_orm::condition_factory('rrd', 'device_project:get_device_project');

		$project_id   = $project->get_project_id();
		$project_name = $project->get_project_name();
		$device_name  = $project->get_device_name();

		if ('' === trim($project_name)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('Unknow project name.');	
		}	

		if ('' === trim($device_name)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('Unknow device  name.');	
		}	

		if (!isset($project_id) || !is_numeric($project_id)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('Unknow project_id.');	
		}	

		// 创建存储目录
		require_once PATH_SWAN_LIB . 'sw_hash_dir.class.php';
		$rrd_name = sprintf('%s_%s.rrd', $device_name, $project_name);
		$hash_dir = sw_hash_dir::get_hash_dir($rrd_name);
		sw_hash_dir::make_hash_dir(PATH_SWAN_RRA, $hash_dir);

		$full_path = PATH_SWAN_RRA . $hash_dir . $rrd_name;

		if (!$recover && file_exists($full_path)) {
			return true;	
		}
		
		$string = '';			
		$params = array(
			'create ' . $full_path,
			' --start ' . $project->get_start_time(),
			' --step ' . $project->get_step(),
			$this->_create_ds($project_id),
			$this->_create_rra($project_id),
		);
		$string = implode($params, RRD_NL);
		
		$return = $this->set_command($string)
					   ->exec();
		if (0 !== $return['res'] || 'OK' !== substr(trim($return['message']), 0, 2)) {
			require_once PATH_SWAN_LIB . 'rrd/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception("create rra database failed . ");	
		}

		return $return;
	}

	// }}}
	// {{{ protected function _create_ds()
	
	/**
	 * 创建数据源语句 
	 * 
	 * @access protected
	 * @return string 
	 */
	protected function _create_ds($project_id)
	{
		$condition = sw_orm::condition_factory('rrd', 'rrd_ds:get_rrd_ds');
		$condition->set_in('project_id');
		$condition->set_project_id($project_id);
		$operator = sw_orm::operator_factory('rrd', 'rrd_ds');
		
		$ds_result = $operator->get_rrd_ds($condition);	
		$string = '';
		foreach ($ds_result as $value) {
			$params = array(
				' DS',
				$value['ds_name'],
				$this->__ds_dst[$value['source_type']],
				$value['heart_time'],
				$value['min'],
				$value['max'] . RRD_NL,
			);
			$string .= implode($params, ':');
		}

		return rtrim($string, RRD_NL);
	}

	// }}}
	// {{{ protected function _create_rra()

	/**
	 * 创建归档策略语句 
	 * 
	 * @param int $project_id 
	 * @access protected
	 * @return void
	 */
	protected function _create_rra($project_id)
	{
		$condition = sw_orm::condition_factory('rrd', 'rrd_rra:get_rrd_rra');
		$condition->set_in('project_id');
		$condition->set_project_id($project_id);
		$operator = sw_orm::operator_factory('rrd', 'rrd_rra');

		$rra_result = $operator->get_rrd_rra($condition);	

		$string = '';
		foreach ($rra_result as $value) {
			$params = array(
				' RRA',
				$this->__rra_cf[$value['rra_cf']],
				$value['rra_xff'],
				$value['steps'],
				$value['rows'] . RRD_NL,
			);
			$string .= implode($params, ':');
		}

		return rtrim($string, RRD_NL);
	}

	// }}}
	// }}}	
}
