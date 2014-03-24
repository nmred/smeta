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
* 监控器 archive 接口 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_monitor_archive extends sw_abstract
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
		$monitor_id = $this->__request->get_post('mid', '');
		$cf_type    = $this->__request->get_post('cf_type', '');
		$xff   = $this->__request->get_post('xff', '0.5');
		$title = $this->__request->get_post('title', '');
		$steps = $this->__request->get_post('steps', '');
		$rows  = $this->__request->get_post('rows', '');
		if (!$cf_type || !$monitor_id || !$steps || !$rows) {
			return $this->render_json(null, 10001, '`cf_type`/`steps`/`mid`/`rows` not allow is empty.');
		}

		// 添加 monitor archive
		$data = array(
			'monitor_id' => $monitor_id,
			'title'   => $title,
			'cf_type' => $cf_type,
			'xff'   => $xff,
			'steps' => $steps,
			'rows'  => $rows,
		);
		try {
			$property_archive  = sw_member::property_factory('monitor_archive', $data); 
			$property_basic   = sw_member::property_factory('monitor_basic', array('monitor_id' => $monitor_id)); 
			$monitor = sw_member::operator_factory('monitor', $property_basic);
			$archive_id = $monitor->get_operator('archive')->add_archive($property_archive);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(array('archive_id' => $archive_id, 'monitor_id' => $monitor_id), 10000, 'add monitor archive success.');
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
		$archive_id  = $this->__request->get_post('arid', '');
		$monitor_id = $this->__request->get_post('mid', '');
		if (!$archive_id || !$monitor_id) {
			return $this->render_json(null, 10001, '`arid`/`mid` not allow is empty.');
		}

		// 删除监控器 archive
		try {
			$condition = sw_member::condition_factory('del_monitor_archive', array('archive_id' => $archive_id, 'monitor_id' => $monitor_id)); 
			$condition->set_in('archive_id');
			$condition->set_in('monitor_id');
			$monitor = sw_member::operator_factory('monitor');
			$monitor->get_operator('archive')->del_archive($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'delete monitor archive success.');
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
		$arid = $this->__request->get_post('arid', '');
		$page = $this->__request->get_post('page', 1);
		$page_count = $this->__request->get_post('page_count', 10);

		if (!$mid) {
			return $this->render_json(null, 10001, 'must defined mid.');	
		}

		$count = 0;
		try {
			$condition = sw_member::condition_factory('get_monitor_archive', array('monitor_id' => $mid)); 
			$condition->set_in('monitor_id');
			if ($arid) {
				$condition->set_in('archive_id');
				$condition->set_archive_id($arid);
			}
			$condition->set_is_count(true);
			$monitor = sw_member::operator_factory('monitor');
			$count   = $monitor->get_operator('archive')->get_archive($condition);
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
			$data   = $monitor->get_operator('archive')->get_archive($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$result = array(
			'result' => $data,
			'count'  => $count,
		);

		return $this->render_json($result, 10000, 'get monitor archive success.');
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
		$mid   = $this->__request->get_post('mid', '');
		$arid  = $this->__request->get_post('arid', '');
		$cf_type = $this->__request->get_post('cf_type', '');
		$xff   = $this->__request->get_post('xff', '');
		$title = $this->__request->get_post('title', '');
		$steps = $this->__request->get_post('steps', '');
		$rows  = $this->__request->get_post('rows', '');
		if (!$mid || !$arid) {
			return $this->render_json(null, 10001, '`mid` and `arid` not allow is empty.');
		}

		// 修改 monitor archive
		$data = array();
		if ($cf_type) {
			$data['cf_type'] = $cf_type;	
		}

		if ($xff) {
			$data['xff'] = $xff;	
		}

		if ($title) {
			$data['title'] = $title;	
		}

		if ($steps) {
			$data['steps'] = $steps;	
		}

		if ($rows) {
			$data['rows'] = $rows;	
		}

		try {
			$property_archive = sw_member::property_factory('monitor_archive', $data); 
			$condition = sw_member::condition_factory('mod_monitor_archive', array('monitor_id' => $mid, 'archive_id' => $arid));
			$condition->set_in('monitor_id');
			$condition->set_in('archive_id');
			$condition->set_property($property_archive);
			$monitor = sw_member::operator_factory('monitor');
			$monitor->get_operator('archive')->mod_archive($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'mod monitor archive success.');
	}

	// }}}
	// }}}
}
