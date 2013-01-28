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
	 * rrd 数据库的路劲 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__rrd_path = '';

	// }}}
	// {{{ functions
	// {{{ public function create_rrd()

	/**
	 * 更新rrd数据库 
	 * 
	 * @access public
	 * @return void
	 */
	public function update($project_id, $recover = false)
	{
		$condition = sw_orm::condition_factory('rrd', 'device_project:get_device_project');
		if (!isset($project_id) || !is_numeric($project_id)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('Unknow project_id.');	
		}	

		$condition->set_in('project_id');
		$condition->set_project_id($project_id);
		$operator = sw_orm::operator_factory('rrd', 'device_project');

		$project_result = $operator->get_device_project($condition);	
		
		foreach ($project_result as $value) {
			$condition = sw_orm::condition_factory('rrd', 'device:get_device');
			$condition->set_in('device_id');
			$condition->set_device_id($value['device_id']);
			$condition->set_columns(array('device_name'));
			$device_operator = sw_orm::operator_factory('rrd', 'device');
			$device_desc = $device_operator->get_device($condition);

			// 创建存储目录
			require_once PATH_SWAN_LIB . 'sw_hash_dir.class.php';
			$rrd_name = sprintf('%s_%s.rrd', $device_desc[0]['device_name'], $value['project_name']);
			$hash_dir = sw_hash_dir::get_hash_dir($rrd_name);
			sw_hash_dir::make_hash_dir(PATH_SWAN_RRA, $hash_dir);

			$full_path = PATH_SWAN_RRA . $hash_dir . $rrd_name;

			if (!$recover && file_exists($full_path)) {
				continue;	
			}
			
			$string = '';			
			$params = array(
				'create ' . $full_path,
				' --start ' . $value['start_time'],
				' --step ' . $value['step'],
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
	}

	// }}}
	// {{{ public function set_rrd_path()

	/**
	 * 设置 rrd 的数据库的路劲 
	 * 
	 * @access public
	 * @return void
	 */
	public function set_rrd_path($project_id)
	{
		$condition = sw_orm::condition_factory('rrd', 'device_project:get_device_project');
		if (!isset($project_id) || !is_numeric($project_id)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('Unknow project_id.');	
		}	

		$condition->set_in('project_id');
		$condition->set_project_id($project_id);
		$operator = sw_orm::operator_factory('rrd', 'device_project');

		$project_result = $operator->get_device_project($condition);	
		
		foreach ($project_result as $value) {
			$condition = sw_orm::condition_factory('rrd', 'device:get_device');
			$condition->set_in('device_id');
			$condition->set_device_id($value['device_id']);
			$condition->set_columns(array('device_name'));
			$device_operator = sw_orm::operator_factory('rrd', 'device');
			$device_desc = $device_operator->get_device($condition);

			// 创建存储目录
			require_once PATH_SWAN_LIB . 'sw_hash_dir.class.php';
			$rrd_name = sprintf('%s_%s.rrd', $device_desc[0]['device_name'], $value['project_name']);
			$hash_dir = sw_hash_dir::get_hash_dir($rrd_name);
			sw_hash_dir::make_hash_dir(PATH_SWAN_RRA, $hash_dir);

			$full_path = PATH_SWAN_RRA . $hash_dir . $rrd_name;

			if (!$recover && file_exists($full_path)) {
				continue;	
			}
			
			$string = '';			
			$params = array(
				'create ' . $full_path,
				' --start ' . $value['start_time'],
				' --step ' . $value['step'],
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
