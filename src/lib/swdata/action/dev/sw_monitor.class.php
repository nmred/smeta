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
* 监控器接口 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_monitor extends sw_abstract
{
	// {{{ functions
	// {{{ public function action_add()

	/**
	 * 添加监控器 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_add()
	{
		$monitor_name = $this->__request->get_post('name', '');
		$monitor_display_name = $this->__request->get_post('display_name', '');
		if (!$monitor_name) {
			return $this->render_json(null, 10001, '`name` not allow is empty.');
		}

		// 添加 monitor basic
		try {
			$property_basic = sw_member::property_factory('monitor_basic', 
				array('monitor_name' => $monitor_name, 'monitor_display_name' => $monitor_display_name)); 
			$monitor    = sw_member::operator_factory('monitor', $property_basic);
			$monitor_id = $monitor->get_operator('basic')->add_basic();
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(array('monitor_id' => $monitor_id), 10000, 'add monitor success.');
	}

	// }}}
	// {{{ public function action_del()
	
	/**
	 * 删除设备 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_del()
	{
		$monitor_name = $this->__request->get_post('name', '');
		$monitor_id   = $this->__request->get_post('mid', '');
		if (!$monitor_name && !$monitor_id) {
			return $this->render_json(null, 10001, '`name` or `mid` not allow is empty.');
		}

		// 删除监控器
		try {
			if ($monitor_id) {
				$condition_basic = sw_member::condition_factory('del_monitor_basic', array('monitor_id' => $monitor_id)); 
				$condition_basic->set_in('monitor_id');
			} else {
				$condition_basic = sw_member::condition_factory('del_monitor_basic', array('monitor_name' => $monitor_name)); 
				$condition_basic->set_in('monitor_name');
			}
			$monitor = sw_member::operator_factory('monitor');
			$monitor->get_operator('basic')->del_basic($condition_basic);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'delete monitor success.');
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
		// 获取监控器
		$page = $this->__request->get_post('page', 1);
		$page_count = $this->__request->get_post('page_count', 10);
		$count = 0;
		try {
			$condition_basic = sw_member::condition_factory('get_monitor_basic'); 
			$condition_basic->set_is_count(true);
			$monitor = sw_member::operator_factory('monitor');
			$count   = $monitor->get_operator('basic')->get_basic($condition_basic);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		if (!$count) {
			return $this->render_json(array('count' => $count), 10001, 'no data.');	
		}

		try {
			$condition_basic->set_is_count(false);
			$condition_basic->set_columns('*');
			$condition_basic->set_limit_page(array('page' => $page, 'rows_count' => $page_count));
			$monitor = sw_member::operator_factory('monitor');
			$data   = $monitor->get_operator('basic')->get_basic($condition_basic);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$result = array(
			'result' => $data,
			'count'  => $count,
		);

		return $this->render_json($result, 10000, 'get monitor success.');
	}

	// }}}
	// {{{ public function action_mod()

	/**
	 * 修改监控器 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_mod()
	{
		
	}

	// }}}
	// }}}
}
