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
	 * 所有的监控适配器信息 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__madapters = array();

	/**
	 * 监控适配器的数据项 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__metrics = array();

	/**
	 * 设备监控适配器 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__devices = array();

	/**
	 * 保存监控适配器数据库返回的 IDS 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__madapter_ids = array();

	/**
	 *  设备监控适配器组合 ID 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__monitor_ids = array();

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
		$this->__madapters = $parse->get_madapters();
		$this->__metrics  = $parse->get_metrics();
		$this->__devices  = $parse->get_devices();

		$this->_init_madapter();
		$this->_init_metrics();
		$this->_init_devices();
	}

	// }}}
	// {{{ protected function _init_madapter()
	
	/**
	 * _init_madapter 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _init_madapter()
	{
		foreach ($this->__madapters as $name => $madapter) {
			$attrs = isset($madapter['attrs']) ? $madapter['attrs'] : array();
			unset($madapter['attrs']);
			$archives = isset($madapter['archives']) ? $madapter['archives'] : array();
			unset($madapter['archives']);
			$data = \lib\inner_client\sw_inner_client::call('dev', 'madapter.add', $madapter);
			if ($data['code'] !== 10000) {
				throw new sw_exception("add madapter: $name .," . $data['msg']);	
			}
			$madapter_id = $data['data']['madapter_id'];

			$attr_ids = array();
			foreach ($attrs as $value) {
				$value['madapter_id'] = $madapter_id;
				$data = \lib\inner_client\sw_inner_client::call('dev', 'madapter_attr.add', $value);
				if ($data['code'] !== 10000) {
					throw new sw_exception("add madapter: $name ,attr_name:" . $value['name'] . $data['msg']);	
				}

				$attr_ids[$value['name']] = $data['data']['attr_id'];
			}
			foreach ($archives as $value) {
				$value['madapter_id'] = $madapter_id;
				$data = \lib\inner_client\sw_inner_client::call('dev', 'madapter_archive.add', $value);
				if ($data['code'] !== 10000) {
					throw new sw_exception("add madapter: $name ". $data['msg']);	
				}
			}
			$this->__madapter_ids[$name]['id'] = $madapter_id;
			$this->__madapter_ids[$name]['attr_ids'] = $attr_ids;
		}	
	}

	// }}}
	// {{{ protected function _init_metrics()
	
	/**
	 * 入库监控适配器的数据项 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _init_metrics()
	{
		foreach ($this->__metrics as $madapter_name => $metrics) {
			$metric_ids = array();
			foreach ($metrics as $metric_name => $value) {
				$value['madapter_id'] = $this->__madapter_ids[$madapter_name]['id'];
				$data = \lib\inner_client\sw_inner_client::call('dev', 'madapter_metric.add', $value);
				if ($data['code'] !== 10000) {
					throw new sw_exception("add madapter: $name ., metric:$metric_name" . $data['msg']);	
				}
				$metric_ids[$metric_name] = $data['data']['metric_id'];
			}
			$this->__madapter_ids[$madapter_name]['metric_ids'] = $metric_ids;
		}	
	}

	// }}}
	// {{{ protected function _init_devices()
	
	/**
	 * 入库设备、设备监控适配器 
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

			foreach ($monitors as $monitor_name => $value) {
				$madapter_name = $value['madapter_name'];
				$monitor_name  = $value['name'];
				$attr_data = array();
				foreach ($value['attrs'] as $attr_name => $attr_value) {
					if (!isset($this->__madapter_ids[$madapter_name]['attr_ids'][$attr_name])) {
						continue;
					}
					$attr_data[] = array(
						'attr_id' => $this->__madapter_ids[$madapter_name]['attr_ids'][$attr_name],
						'value'   => $attr_value,
					);	
				}
				$post_data = array(
					'device_id' => $device_id,
					'madapter_id' => $this->__madapter_ids[$madapter_name]['id'],
					'monitor_name' => $monitor_name,
					'attr_data' => json_encode($attr_data),
				);
				$data = \lib\inner_client\sw_inner_client::call('user', 'dmonitor.add', $post_data);
				if ($data['code'] !== 10000) {
					throw new sw_exception("add device: $device_name .,monitor_name: $monitor_name" . $data['msg']);	
				}

				$this->__monitor_ids[$device_name][$monitor_name] = $data['data'];
			}
		}	
	}

	// }}}
	// }}}
}
