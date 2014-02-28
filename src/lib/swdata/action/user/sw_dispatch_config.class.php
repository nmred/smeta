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

namespace lib\swdata\action\user;
use \lib\swdata\action\sw_abstract;
use lib\swdata\action\user\exception\sw_exception;
use \lib\member\sw_member;

/**
+------------------------------------------------------------------------------
* 分发配置接口 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_dispatch_config extends sw_abstract
{
	// {{{ functions
	// {{{ public function action_do()

	/**
	 * 执行配置分发 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_do()
	{
		$device_name = $this->__request->get_post('device_name', '');
		try {
			$device = sw_member::operator_factory('device');
			$condition = sw_member::condition_factory('get_device'); 
			if ($device_name) {
				$condition->set_in('device_name');
				$condition->set_device_name(trim($device_name));
			}
			$condition->set_is_count(false);
			$data = $device->get_device($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$monitor_data = array();
		foreach ($data as $d_info) {
			$device_id = $d_info['device_id'];
			try {
				$condition = sw_member::condition_factory('get_device_monitor'); 
				$condition->set_in('device_id');
				$condition->set_device_id($device_id);	
				$condition->set_is_count(false);
				$monitor_params = $device->get_operator('monitor')->get_monitor($condition);
			} catch (\swan\exception\sw_exception $e) {
				return $this->render_json(null, 10001, $e->getMessage());	
			}

			if (empty($monitor_params)) {
				continue; 
			}

			foreach ($monitor_params as $mp_info) {
				try {
					$attr_info  = $this->_get_attr_info($mp_info['monitor_id'], $mp_info['device_id'], $mp_info['dm_id']);
				} catch (\swan\exception\sw_exception $e) {
					continue;
				}
				$params = array();
				foreach ($attr_info as $attr) {
					$params[$attr['attr_name']]	= $attr['value'];
				}

				$basic = $mp_info;
				$basic['device_display_name'] = $d_info['device_display_name'];
				$basic['host_name']   = $d_info['host_name'];
				$basic['device_name'] = $d_info['device_name'];
				$monitor_key  = $device_id . '_' . $mp_info['dm_id'];
				$monitor_data[$monitor_key]['params'] = $params;
				$monitor_data[$monitor_key]['basic']  = $basic;

				// 获取数据项
				try {
					$condition = sw_member::condition_factory('get_monitor_metric', array('monitor_id' => $mp_info['monitor_id'])); 
					$condition->set_in('monitor_id');
					$monitor = sw_member::operator_factory('monitor');
					$metrics = $monitor->get_operator('metric')->get_metric($condition);
				} catch (\swan\exception\sw_exception $e) {
					return $this->render_json(null, 10001, $e->getMessage());	
				}
				$monitor_data[$monitor_key]['metrics'] = $metrics;
			}
		}
		return $this->render_json($monitor_data, 10000, 'get smeta config success');
	}

	// }}}
	// {{{ protected function _get_attr_info()
	
	/**
	 * 获取属性的详细信息 
	 * 
	 * @param int $monitor_id 
	 * @param int $attr_id 
	 * @access protected
	 * @return array
	 */
	protected function _get_attr_info($monitor_id, $device_id, $dm_id)
	{
		try {
			$device = sw_member::operator_factory('device');
			$condition = sw_member::condition_factory('get_device_monitor_params'); 
			$condition->set_in('device_id');
			$condition->set_device_id($device_id);	
			$condition->set_in('dm_id');
			$condition->set_dm_id($dm_id);	
			$condition->set_in('monitor_id');
			$condition->set_monitor_id($monitor_id);	
			$condition->set_is_count(false);
			$monitor_params = $device->get_operator('monitor')->get_monitor_params($condition);
		} catch (\swan\exception\sw_exception $e) {
			throw new sw_exception($e);
		}

		return $monitor_params;
	}

	// }}}
	// {{{ protected function _get_monitor_info()
	
	/**
	 * 获取监控器的详细信息 
	 * 
	 * @param int $monitor_id 
	 * @access protected
	 * @return array
	 */
	protected function _get_monitor_info($monitor_id)
	{
		try {
			$monitor = sw_member::operator_factory('monitor');
			$monitor_info = $monitor->get_operator('basic')->get_info($monitor_id);
		} catch (\swan\exception\sw_exception $e) {
			throw new sw_exception($e);
		}

		return $monitor_info;
	}

	// }}}
	// }}}
}
