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
* 监控适配器数据项接口 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_madapter_metric extends sw_abstract
{
	// {{{ functions
	// {{{ public function action_add()

	/**
	 * 添加监控适配器数据项 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_add()
	{
		$metric_name  = $this->__request->get_post('name', '');
		$madapter_id   = $this->__request->get_post('madapter_id', '');
		$collect_every   = $this->__request->get_post('collect_every', '');
		$time_threshold  = $this->__request->get_post('time_threshold', '200');
		$title = $this->__request->get_post('title', '');
		$unit  = $this->__request->get_post('unit', '');
		$tmax  = $this->__request->get_post('tmax', '');
		$vmax  = $this->__request->get_post('vmax', 'U');
		$vmin  = $this->__request->get_post('vmin', 'U');
		$dst_type  = $this->__request->get_post('dst_type', '1');
		if (!$metric_name || !$madapter_id || !$collect_every) {
			return $this->render_json(null, 10001, '`name`/`madapter_id`/`collect_every` not allow is empty.');
		}

		// 添加 madapter metric
		$data = array(
			'metric_name'    => $metric_name,
			'collect_every'  => $collect_every,
			'time_threshold' => $time_threshold,
			'dst_type'       => $dst_type,
			'title' => $title,
			'unit' => $unit,
			'tmax'  => $tmax,
			'vmax'  => $vmax,
			'vmin'  => $vmin,
		);
		try {
			$property_metric  = sw_member::property_factory('madapter_metric', $data); 
			$property_basic   = sw_member::property_factory('madapter_basic', array('madapter_id' => $madapter_id)); 
			$madapter = sw_member::operator_factory('madapter', $property_basic);
			$metric_id = $madapter->get_operator('metric')->add_metric($property_metric);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(array('metric_id' => $metric_id, 'madapter_id' => $madapter_id), 10000, 'add madapter metric success.');
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
		$metric_id  = $this->__request->get_post('metric_id', '');
		$madapter_id = $this->__request->get_post('madapter_id', '');
		if (!$metric_id || !$madapter_id) {
			return $this->render_json(null, 10001, '`metric_id`/`madapter_id` not allow is empty.');
		}

		// 删除监控适配器数据项
		try {
			$condition = sw_member::condition_factory('del_madapter_metric', array('metric_id' => $metric_id, 'madapter_id' => $madapter_id)); 
			$condition->set_in('metric_id');
			$condition->set_in('madapter_id');
			$madapter = sw_member::operator_factory('madapter');
			$madapter->get_operator('metric')->del_metric($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'delete madapter metric success.');
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
		// 获取监控适配器属性
		$mid  = $this->__request->get_post('madapter_id', '');
		$page = $this->__request->get_post('page', 1);
		$page_count = $this->__request->get_post('page_count', 10);

		if (!$mid) {
			return $this->render_json(null, 10001, 'must defined madapter_id.');	
		}

		$count = 0;
		try {
			$condition = sw_member::condition_factory('get_madapter_metric', array('madapter_id' => $mid)); 
			$condition->set_in('madapter_id');
			$condition->set_is_count(true);
			$madapter = sw_member::operator_factory('madapter');
			$count   = $madapter->get_operator('metric')->get_metric($condition);
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
			$data   = $madapter->get_operator('metric')->get_metric($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$result = array(
			'result' => $data,
			'count'  => $count,
		);

		return $this->render_json($result, 10000, 'get madapter metric success.');
	}

	// }}}
	// {{{ public function action_mod()

	/**
	 * 修改监控适配器数据项 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_mod()
	{	
		$mid  = $this->__request->get_post('madapter_id', '');
		$mmid  = $this->__request->get_post('metric_id', '');
		$name = $this->__request->get_post('name', '');
		$unit  = $this->__request->get_post('unit', '');
		$title = $this->__request->get_post('title', '');
		$tmax  = $this->__request->get_post('tmax', '');
		$vmax  = $this->__request->get_post('vmax', 'U');
		$vmin  = $this->__request->get_post('vmin', 'U');
		$dst_type  = $this->__request->get_post('dst_type', '');
		$collect_every = $this->__request->get_post('collect_every', '');
		$time_threshold = $this->__request->get_post('time_threshold', '');
		if (!$mid || !$mmid) {
			return $this->render_json(null, 10001, '`madapter_id` and `metric_id` not allow is empty.');
		}

		// 修改 madapter metric
		$data = array();
		if ($name) {
			$data['metric_name'] = $name;	
		}

		if ($title) {
			$data['title'] = $title;	
		}

		if ($unit) {
			$data['unit'] = $unit;	
		}

		if ($collect_every) {
			$data['collect_every'] = $collect_every;	
		}

		if ($time_threshold) {
			$data['time_threshold'] = $time_threshold;	
		}

		if ($dst_type) {
			$data['dst_type'] = $dst_type;	
		}

		if ($vmax) {
			$data['vmax'] = $vmax;	
		}

		if ($vmin) {
			$data['vmin'] = $vmin;	
		}

		if ($tmax) {
			$data['tmax'] = $tmax;	
		}
		try {
			$property_metric = sw_member::property_factory('madapter_metric', $data); 
			$condition = sw_member::condition_factory('mod_madapter_metric', array('madapter_id' => $mid, 'metric_id' => $mmid));
			$condition->set_in('madapter_id');
			$condition->set_in('metric_id');
			$condition->set_property($property_metric);
			$madapter = sw_member::operator_factory('madapter');
			$madapter->get_operator('metric')->mod_metric($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'mod madapter metric success.');
	}

	// }}}
	// }}}
}
