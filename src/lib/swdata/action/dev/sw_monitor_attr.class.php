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
* 监控器属性接口 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_monitor_attr extends sw_abstract
{
	// {{{ functions
	// {{{ public function action_add()

	/**
	 * 添加监控器属性 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_add()
	{
		$attr_name  = $this->__request->get_post('name', '');
		$monitor_id = $this->__request->get_post('mid', '');
		$form_type  = $this->__request->get_post('form_type', '');
		$form_data  = $this->__request->get_post('form_data', '');
		$display_name = $this->__request->get_post('display_name', '');
		if (!$attr_name || !$monitor_id || !$form_type) {
			return $this->render_json(null, 10001, '`name`/`mid`/`form_type` not allow is empty.');
		}

		// 添加 monitor attribute
		$data = array(
			'form_type' => $form_type,
			'form_data' => $form_data,
			'attr_name' => $attr_name,
			'attr_display_name' => $display_name,
		);
		try {
			$property_attr  = sw_member::property_factory('monitor_attribute', $data); 
			$property_basic = sw_member::property_factory('monitor_basic', array('monitor_id' => $monitor_id)); 
			$monitor = sw_member::operator_factory('monitor', $property_basic);
			$attr_id = $monitor->get_operator('attribute')->add_attribute($property_attr);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(array('attr_id' => $attr_id, 'monitor_id' => $monitor_id), 10000, 'add monitor attribute success.');
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
		$attr_id    = $this->__request->get_post('aid', '');
		$monitor_id = $this->__request->get_post('mid', '');
		if (!$attr_id || !$monitor_id) {
			return $this->render_json(null, 10001, '`aid`/`mid` not allow is empty.');
		}

		// 删除监控器属性
		try {
			$condition = sw_member::condition_factory('del_monitor_attribute', array('attr_id' => $attr_id, 'monitor_id' => $monitor_id)); 
			$condition->set_in('attr_id');
			$condition->set_in('monitor_id');
			$monitor = sw_member::operator_factory('monitor');
			$monitor->get_operator('attribute')->del_attribute($condition);
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
		// 获取监控器属性
		$mid  = $this->__request->get_post('mid', '');
		$page = $this->__request->get_post('page', 1);
		$page_count = $this->__request->get_post('page_count', 10);

		if (!$mid) {
			return $this->render_json(null, 10001, 'must defined mid.');	
		}

		$count = 0;
		try {
			$condition = sw_member::condition_factory('get_monitor_attribute', array('monitor_id' => $mid)); 
			$condition->set_in('monitor_id');
			$condition->set_is_count(true);
			$monitor = sw_member::operator_factory('monitor');
			$count   = $monitor->get_operator('attribute')->get_attribute($condition);
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
			$data   = $monitor->get_operator('attribute')->get_attribute($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$result = array(
			'result' => $data,
			'count'  => $count,
		);

		return $this->render_json($result, 10000, 'get monitor attribute success.');
	}

	// }}}
	// {{{ public function action_mod()

	/**
	 * 修改监控器属性 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_mod()
	{	
		$mid  = $this->__request->get_post('mid', '');
		$aid  = $this->__request->get_post('aid', '');
		$name = $this->__request->get_post('name', '');
		$display_name = $this->__request->get_post('display_name', '');
		$form_type = $this->__request->get_post('form_type', '');
		$form_data = $this->__request->get_post('form_data', '');
		if (!$mid || !$aid) {
			return $this->render_json(null, 10001, '`mid` and `aid` not allow is empty.');
		}

		// 修改 monitor attribute
		$data = array();
		if ($name) {
			$data['attr_name'] = $name;	
		}

		if ($display_name) {
			$data['attr_display_name'] = $display_name;	
		}

		if ($form_type) {
			$data['form_type'] = $form_type;	
		}

		if ($form_data) {
			$data['form_data'] = $form_data;	
		}
		try {
			$property_attribute = sw_member::property_factory('monitor_attribute', $data); 
			$condition = sw_member::condition_factory('mod_monitor_attribute', array('monitor_id' => $mid, 'attr_id' => $aid));
			$condition->set_in('monitor_id');
			$condition->set_in('attr_id');
			$condition->set_property($property_attribute);
			$monitor = sw_member::operator_factory('monitor');
			$monitor->get_operator('attribute')->mod_attribute($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'mod monitor success.');
	}

	// }}}
	// }}}
}
