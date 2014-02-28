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
 
namespace lib\init_data;
use \lib\init_data\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 初始化数据模块 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_init_data
{
	// {{{ consts
	// }}}
	// {{{ members
	
	/**
	 * 所有的监控器信息 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__monitors = array();

	/**
	 * 监控器的数据项 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__metrics = array();

	/**
	 * 设备监控器 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__devices = array();

	/**
	 * 保存监控器数据库返回的 IDS 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__monitor_ids = array();

	/**
	 *  设备监控器组合 ID 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__dm_ids = array();

	// }}}
	// {{{ functions
	// {{{ public function run()
	
	/**
	 * 运行初始化数据库 
	 * 
	 * @access public
	 * @return void
	 */
	public function run()
	{
		$parse = new \lib\init_data\sw_parse_data();
		$parse->generate_dynamic();
		$this->__monitors = $parse->get_monitors();
		$this->__metrics  = $parse->get_metrics();
		$this->__devices  = $parse->get_devices();

		$this->_init_monitor();
		$this->_init_metrics();
		$this->_init_devices();
	}

	// }}}
	// {{{ protected function _init_monitor()
	
	/**
	 * _init_monitor 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _init_monitor()
	{
		foreach ($this->__monitors as $name => $monitor) {
			$attrs = $monitor['attrs'];
			unset($monitor['attrs']);
			$data = \lib\inner_client\sw_inner_client::call('dev', 'monitor.add', $monitor);
			if ($data['code'] !== 10000) {
				throw new sw_exception("add monitor: $name .," . $data['msg']);	
			}
			$monitor_id = $data['data']['monitor_id'];

			$attr_ids = array();
			foreach ($attrs as $value) {
				$value['mid'] = $monitor_id;
				$data = \lib\inner_client\sw_inner_client::call('dev', 'monitor_attr.add', $value);
				if ($data['code'] !== 10000) {
					throw new sw_exception("add monitor: $name ,attr_name:" . $value['name'] . $data['msg']);	
				}

				$attr_ids[$value['name']] = $data['data']['attr_id'];
			}
			$this->__monitor_ids[$name]['id'] = $monitor_id;
			$this->__monitor_ids[$name]['attr_ids'] = $attr_ids;
		}	
	}

	// }}}
	// {{{ protected function _init_metrics()
	
	/**
	 * 入库监控器的数据项 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _init_metrics()
	{
		foreach ($this->__metrics as $monitor_name => $metrics) {
			$metric_ids = array();
			foreach ($metrics as $metric_name => $value) {
				$value['mid'] = $this->__monitor_ids[$monitor_name]['id'];
				$data = \lib\inner_client\sw_inner_client::call('dev', 'monitor_metric.add', $value);
				if ($data['code'] !== 10000) {
					throw new sw_exception("add monitor: $name ., metric:$metric_name" . $data['msg']);	
				}
				$metric_ids[$metric_name] = $data['data']['metric_id'];
			}
			$this->__monitor_ids[$monitor_name]['metric_ids'] = $metric_ids;
		}	
	}

	// }}}
	// {{{ protected function _init_devices()
	
	/**
	 * 入库设备、设备监控器 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _init_devices()
	{
		foreach ($this->__devices as $device_name => $device) {
			$monitors = $device['monitors'];
			unset($device['monitors']);
			$data = \lib\inner_client\sw_inner_client::call('user', 'device.add', $device);
			if ($data['code'] !== 10000) {
				throw new sw_exception("add device: $device_name .," . $data['msg']);	
			}
			$device_id = $data['data']['device_id'];

			foreach ($monitors as $dm_name => $value) {
				$monitor_name = $value['name'];
				$attr_data = array();
				foreach ($value['attrs'] as $attr_name => $attr_value) {
					if (!isset($this->__monitor_ids[$monitor_name]['attr_ids'][$attr_name])) {
						continue;
					}
					$attr_data[] = array(
						'attr_id' => $this->__monitor_ids[$monitor_name]['attr_ids'][$attr_name],
						'value'   => $attr_value,
					);	
				}
				$post_data = array(
					'did' => $device_id,
					'mid' => $this->__monitor_ids[$monitor_name]['id'],
					'dm_name' => $value['dm_name'],
					'attr_data' => json_encode($attr_data),
				);
				$data = \lib\inner_client\sw_inner_client::call('user', 'device_monitor.add', $post_data);
				if ($data['code'] !== 10000) {
					throw new sw_exception("add device: $device_name .,monitor: $monitor_name" . $data['msg']);	
				}

				$this->__dm_ids[$device_name][$monitor_name] = $data['data'];
			}
		}	
	}

	// }}}
	// }}}
}
