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
* 设备监控器接口 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_dmonitor extends sw_abstract
{
	// {{{ functions
	// {{{ public function action_add()

	/**
	 * 添加设备监控器 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_add()
	{
		$did = $this->__request->get_post('device_id', '');
		$mid = $this->__request->get_post('madapter_id', '');
		$dm_name = $this->__request->get_post('monitor_name', '');
		$attr_data = $this->__request->get_post('attr_data', '{}');
		if (!$did || !$mid || !$dm_name) {
			return $this->render_json(null, 10001, '`monitor_name`/`device_id`/`madapter_id` not allow is empty.');
		}

		$attr_data = json_decode($attr_data, true);
		$monitor_params = array();
		foreach ($attr_data as $value) {
			if (!isset($value['attr_id']) || !isset($value['value'])) {
				return $this->render_json(null, 10001, 'attr_data is must defined `attr_id`/`value` not allow is empty.');
			}
			$monitor_params[] = sw_member::property_factory('madapter_params', array('attr_id' => $value['attr_id'], 'value' => $value['value']));
		}
		try {
			$device_property_key    = sw_member::property_factory('device_key', array('device_id' => $did));
			$madapter_property_basic = sw_member::property_factory('madapter_basic', array('madapter_id' => $mid));
			$device_property_monitor = sw_member::property_factory('device_monitor', array('monitor_name' => $dm_name));
			$device_property_monitor->set_madapter_basic($madapter_property_basic);
			$device_property_monitor->set_monitor_params($monitor_params);
			$device = sw_member::operator_factory('device', $device_property_key);
			$monitor_id  = $device->get_operator('monitor')->add_monitor($device_property_monitor);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(array('monitor_id' => $monitor_id, 'device_id' => $did), 10000, 'add device monitor attributes success.');
	}

	// }}}
	// {{{ public function action_del()
	
	/**
	 * 删除设备监控器 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_del()
	{
		$monitor_id = $this->__request->get_post('monitor_id', '');
		$did   = $this->__request->get_post('device_id', '');
		if (!$monitor_id || !$did) {
			return $this->render_json(null, 10001, '`monitor_id`/`device_id` not allow is empty.');
		}

		// 删除设备
		try {
			$condition = sw_member::condition_factory('del_device_monitor');
			$condition->set_in('monitor_id');
			$condition->set_monitor_id($monitor_id);
			$property_key = sw_member::property_factory('device_key', array('device_id' => $did));
			$device = sw_member::operator_factory('device', $property_key);
			$device_monitor = $device->get_operator('monitor')->del_monitor($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'delete device monitor success.');
	}

	// }}}
	// {{{ public function action_mod()

	/**
	 * 修改设备监控器 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_mod()
	{	
		$device_id = $this->__request->get_post('device_id', '');
		$monitor_id = $this->__request->get_post('monitor_id', '');
		$attr_data = $this->__request->get_post('attr_data', '{}');
		if (!$device_id || !$monitor_id) {
			return $this->render_json(null, 10001, '`device_id`/`monitor_id` not allow is empty.');
		}

		$attr_data = json_decode($attr_data, true);
		$monitor_params = array();
		foreach ($attr_data as $value) {
			if (!isset($value['attr_id']) || !isset($value['value'])) {
				return $this->render_json(null, 10001, 'attr_data is must defined `attr_id`/`value` not allow is empty.');
			}
			$monitor_params[] = sw_member::property_factory('madapter_params', array('attr_id' => $value['attr_id'], 'value' => $value['value']));
		}

		// 修改 device monitor
		try {
			$device_property_key = sw_member::property_factory('device_key', array('device_id' => $device_id));
			$device_property_monitor = sw_member::property_factory('device_monitor');
			$device_property_monitor->set_monitor_params($monitor_params);
			$device_property_monitor->set_monitor_id($monitor_id);
			$device_property_monitor->set_in('monitor_id');
			$condition = sw_member::condition_factory('mod_device_monitor');
			$condition->set_property($device_property_monitor);
			$device = sw_member::operator_factory('device', $device_property_key);
			$device_monitor = $device->get_operator('monitor')->mod_monitor($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'mod device monitor success.');
	}

	// }}}
	// {{{ public function action_json()
	
	/**
	 * 获取设备监控器列表 
	 * 
	 * @access public
	 * @return string
	 */
	public function action_json()
	{			
		// 获取设备监控器
		$device_id  = $this->__request->get_post('device_id', '');
		$page = $this->__request->get_post('page', 1);
		$page_count = $this->__request->get_post('page_count', 10);
		$count = 0;
		try {
			$condition = sw_member::condition_factory('get_device_monitor'); 
			$condition->set_is_count(true);
			if ($device_id) {
				$condition->set_in('device_id');
				$condition->set_device_id($device_id);	
			}
			$device = sw_member::operator_factory('device');
			$count  = $device->get_operator('monitor')->get_monitor($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		if (!$count) {
			return $this->render_json(array('count' => $count), 10001, 'no data.');	
		}

		try {
			$condition = sw_member::condition_factory('get_device_monitor'); 
			if ($device_id) {
				$condition->set_in('device_id');
				$condition->set_device_id($device_id);	
			}
			$condition->set_is_count(false);
			$condition->set_limit_page(array('page' => $page, 'rows_count' => $page_count));
			$data = $device->get_operator('monitor')->get_monitor($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$result = array(
			'result' => $data,
			'count'  => $count,
		);

		return $this->render_json($result, 10000, 'get device monitor success.');
	}

	// }}}
	// {{{ public function action_info()
	
	/**
	 * 获取设备监控器 params 值 
	 * 
	 * @access public
	 * @return string
	 */
	public function action_info()
	{			
		// 获取设备监控器 params 值
		$device_id  = $this->__request->get_post('device_id', '');
		$monitor_id = $this->__request->get_post('monitor_id', '');
		$page = $this->__request->get_post('page', 1);
		$page_count = $this->__request->get_post('page_count', 10);
		$count = 0;
		if (!$device_id || !$monitor_id) {
			return $this->render_json(null, 10001, '`device_id`/`monitor_id` not allow is empty.');
		}

		try {
			$condition = sw_member::condition_factory('get_device_monitor_params');
			$condition->set_in('device_id');
			$condition->set_device_id($device_id);
			$condition->set_in('monitor_id');
			$condition->set_monitor_id($monitor_id);
			$condition->set_is_count(true);
			$device = sw_member::operator_factory('device');
			$count = $device->get_operator('monitor')->get_monitor_params($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		if (!$count) {
			return $this->render_json(array('count' => $count), 10001, 'no data.');	
		}

		try {
			$condition = sw_member::condition_factory('get_device_monitor_params');
			$condition->set_in('device_id');
			$condition->set_device_id($device_id);
			$condition->set_in('monitor_id');
			$condition->set_monitor_id($monitor_id);
			$device = sw_member::operator_factory('device');
			$data = $device->get_operator('monitor')->get_monitor_params($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$result = array(
			'result' => $data,
			'count'  => $count,
		);

		return $this->render_json($result, 10000, 'get device monitor success.');
	}

	// }}}
	// }}}
}
