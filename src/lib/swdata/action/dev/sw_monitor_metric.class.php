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

namespace lib\swdata\action\dev;
use \lib\swdata\action\sw_abstract;
use lib\swdata\action\dev\exception\sw_exception;
use \lib\member\sw_member;

/**
+------------------------------------------------------------------------------
* 监控器数据项接口 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_monitor_metric extends sw_abstract
{
	// {{{ functions
	// {{{ public function action_add()

	/**
	 * 添加监控器数据项 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_add()
	{
		$metric_name  = $this->__request->get_post('name', '');
		$monitor_id   = $this->__request->get_post('mid', '');
		$collect_every   = $this->__request->get_post('collect_every', '');
		$time_threshold  = $this->__request->get_post('time_threshold', '200');
		$title = $this->__request->get_post('title', '');
		if (!$metric_name || !$monitor_id || !$collect_every) {
			return $this->render_json(null, 10001, '`name`/`mid`/`collect_every` not allow is empty.');
		}

		// 添加 monitor metric
		$data = array(
			'metric_name'    => $metric_name,
			'collect_every'  => $collect_every,
			'time_threshold' => $time_threshold,
			'title' => $title,
		);
		try {
			$property_metric  = sw_member::property_factory('monitor_metric', $data); 
			$property_basic   = sw_member::property_factory('monitor_basic', array('monitor_id' => $monitor_id)); 
			$monitor = sw_member::operator_factory('monitor', $property_basic);
			$metric_id = $monitor->get_operator('metric')->add_metric($property_metric);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(array('metric_id' => $metric_id, 'monitor_id' => $monitor_id), 10000, 'add monitor metric success.');
	}

	// }}}
	// {{{ public function action_del()
	
	/**
	 * 删除数据项 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_del()
	{
		$metric_id  = $this->__request->get_post('mmid', '');
		$monitor_id = $this->__request->get_post('mid', '');
		if (!$metric_id || !$monitor_id) {
			return $this->render_json(null, 10001, '`mmid`/`mid` not allow is empty.');
		}

		// 删除监控器数据项
		try {
			$condition = sw_member::condition_factory('del_monitor_metric', array('metric_id' => $metric_id, 'monitor_id' => $monitor_id)); 
			$condition->set_in('metric_id');
			$condition->set_in('monitor_id');
			$monitor = sw_member::operator_factory('monitor');
			$monitor->get_operator('metric')->del_metric($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'delete monitor metric success.');
	}

	// }}}
	// {{{ public function action_json()
	
	/**
	 * 获取设备列表 
	 * 
	 * @access public
	 * @return string
	 */
	public function action_json()
	{
		// 获取监控器属性
		$mid  = $this->__request->get_post('mid', '');
		$page = $this->__request->get_post('page', 1);
		$page_count = $this->__request->get_post('page_count', 10);

		if (!$mid) {
			return $this->render_json(null, 10001, 'must defined mid.');	
		}

		$count = 0;
		try {
			$condition = sw_member::condition_factory('get_monitor_metric', array('monitor_id' => $mid)); 
			$condition->set_in('monitor_id');
			$condition->set_is_count(true);
			$monitor = sw_member::operator_factory('monitor');
			$count   = $monitor->get_operator('metric')->get_metric($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		if (!$count) {
			return $this->render_json(array('count' => $count), 10000, 'no data.');	
		}

		try {
			$condition->set_is_count(false);
			$condition->set_columns('*');
			$condition->set_limit_page(array('page' => $page, 'rows_count' => $page_count));
			$data   = $monitor->get_operator('metric')->get_metric($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$result = array(
			'result' => $data,
			'count'  => $count,
		);

		return $this->render_json($result, 10000, 'get monitor metric success.');
	}

	// }}}
	// {{{ public function action_mod()

	/**
	 * 修改监控器数据项 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_mod()
	{	
		$mid  = $this->__request->get_post('mid', '');
		$mmid  = $this->__request->get_post('mmid', '');
		$name = $this->__request->get_post('name', '');
		$title = $this->__request->get_post('title', '');
		$collect_every = $this->__request->get_post('collect_every', '');
		$time_threshold = $this->__request->get_post('time_threshold', '');
		if (!$mid || !$mmid) {
			return $this->render_json(null, 10001, '`mid` and `mmid` not allow is empty.');
		}

		// 修改 monitor metric
		$data = array();
		if ($name) {
			$data['metric_name'] = $name;	
		}

		if ($title) {
			$data['title'] = $title;	
		}

		if ($collect_every) {
			$data['collect_every'] = $collect_every;	
		}

		if ($time_threshold) {
			$data['time_threshold'] = $time_threshold;	
		}
		try {
			$property_metric = sw_member::property_factory('monitor_metric', $data); 
			$condition = sw_member::condition_factory('mod_monitor_metric', array('monitor_id' => $mid, 'metric_id' => $mmid));
			$condition->set_in('monitor_id');
			$condition->set_in('metric_id');
			$condition->set_property($property_metric);
			$monitor = sw_member::operator_factory('monitor');
			$monitor->get_operator('metric')->mod_metric($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'mod monitor metric success.');
	}

	// }}}
	// }}}
}
