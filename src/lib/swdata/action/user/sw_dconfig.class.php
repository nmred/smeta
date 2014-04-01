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
class sw_dconfig extends sw_abstract
{
	// {{{ functions
	// {{{ public function action_do()

	/**
	 * 分发客户端监控的配置 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_smond()
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

				unset($mp_info['monitor_display_name']);
				unset($mp_info['steps']);
				$basic = $mp_info;
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
				
				// 删除掉 smond 不需要的属性
				foreach ($metrics as $key => $value) {
					unset($metrics[$key]['tmax']);
					unset($metrics[$key]['dst_type']);
					unset($metrics[$key]['vmin']);
					unset($metrics[$key]['vmax']);
					unset($metrics[$key]['unit']);
					unset($metrics[$key]['title']);
				}
				$monitor_data[$monitor_key]['metrics'] = $metrics;
			}
		}
		return $this->render_json($monitor_data, 10000, 'get smeta config success');
	}

	// }}}
	// {{{ public function action_cache_dm()

	/**
	 * 缓存到 redis 的数据 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_cache_dm()
	{
		try {
			$device = sw_member::operator_factory('device');
			$condition = sw_member::condition_factory('get_device'); 
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
				$basic = $mp_info;
				$basic['device_display_name'] = $d_info['device_display_name'];
				$basic['host_name']   = $d_info['host_name'];
				$basic['device_name'] = $d_info['device_name'];
				$basic['heartbeat_time'] = $d_info['heartbeat_time'];
				$monitor_key  = $device_id . '_' . $mp_info['dm_id'];
				$monitor_data[$monitor_key]  = $basic;

			}
		}
		return $this->render_json($monitor_data, 10000, 'get smeta config success');
	}

	// }}}
	// {{{ public function action_cache_monitor()

	/**
	 * 缓存到 redis 的监控器 archive 数据 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_cache_monitor()
	{
		try {
			$condition = sw_member::condition_factory('get_monitor_basic');
			$monitor = sw_member::operator_factory('monitor');
			$monitor_basic = $monitor->get_operator('basic')->get_basic($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$monitor_data = array();
		foreach ($monitor_basic as $monitor_info) {
			// 获取数据项
			try {
				$condition = sw_member::condition_factory('get_monitor_metric', array('monitor_id' => $monitor_info['monitor_id'])); 
				$condition->set_in('monitor_id');
				$monitor = sw_member::operator_factory('monitor');
				$metrics = $monitor->get_operator('metric')->get_metric($condition);
			} catch (\swan\exception\sw_exception $e) {
				return $this->render_json(null, 10001, $e->getMessage());	
			}
			// 获取 archive 信息
			try {
				$condition = sw_member::condition_factory('get_monitor_archive', array('monitor_id' => $monitor_info['monitor_id'])); 
				$condition->set_in('monitor_id');
				$monitor  = sw_member::operator_factory('monitor');
				$archives = $monitor->get_operator('archive')->get_archive($condition);
			} catch (\swan\exception\sw_exception $e) {
				return $this->render_json(null, 10001, $e->getMessage());	
			}
			$monitor_data[$monitor_info['monitor_id']]['archives'] = $archives;
			$monitor_data[$monitor_info['monitor_id']]['metrics']  = $metrics;
			$monitor_data[$monitor_info['monitor_id']]['basic']    = $monitor_info;
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
	// }}}
}
