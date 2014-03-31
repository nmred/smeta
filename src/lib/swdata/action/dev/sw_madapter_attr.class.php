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
* 监控适配器属性接口 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_madapter_attr extends sw_abstract
{
	// {{{ functions
	// {{{ public function action_add()

	/**
	 * 添加监控适配器属性 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_add()
	{
		$attr_name  = $this->__request->get_post('name', '');
		$madapter_id = $this->__request->get_post('madapter_id', '');
		$form_type  = $this->__request->get_post('form_type', '');
		$form_data  = $this->__request->get_post('form_data', '');
		$display_name = $this->__request->get_post('display_name', '');
		$attr_default = $this->__request->get_post('attr_default', '');
		if (!$attr_name || !$madapter_id || !$form_type) {
			return $this->render_json(null, 10001, '`name`/`madapter_id`/`form_type` not allow is empty.');
		}

		// 添加 madapter attribute
		$data = array(
			'form_type' => $form_type,
			'form_data' => $form_data,
			'attr_name' => $attr_name,
			'attr_display_name' => $display_name,
			'attr_default' => $attr_default,
		);
		try {
			$property_attr  = sw_member::property_factory('madapter_attribute', $data); 
			$property_basic = sw_member::property_factory('madapter_basic', array('madapter_id' => $madapter_id)); 
			$madapter = sw_member::operator_factory('madapter', $property_basic);
			$attr_id = $madapter->get_operator('attribute')->add_attribute($property_attr);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(array('attr_id' => $attr_id, 'madapter_id' => $madapter_id), 10000, 'add madapter attribute success.');
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
		$attr_id    = $this->__request->get_post('attr_id', '');
		$madapter_id = $this->__request->get_post('madapter_id', '');
		if (!$attr_id || !$madapter_id) {
			return $this->render_json(null, 10001, '`attr_id`/`madapter_id` not allow is empty.');
		}

		// 删除监控适配器属性
		try {
			$condition = sw_member::condition_factory('del_madapter_attribute', array('attr_id' => $attr_id, 'madapter_id' => $madapter_id)); 
			$condition->set_in('attr_id');
			$condition->set_in('madapter_id');
			$madapter = sw_member::operator_factory('madapter');
			$madapter->get_operator('attribute')->del_attribute($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'delete madapter success.');
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
			$condition = sw_member::condition_factory('get_madapter_attribute', array('madapter_id' => $mid)); 
			$condition->set_in('madapter_id');
			$condition->set_is_count(true);
			$madapter = sw_member::operator_factory('madapter');
			$count   = $madapter->get_operator('attribute')->get_attribute($condition);
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
			$data   = $madapter->get_operator('attribute')->get_attribute($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$result = array(
			'result' => $data,
			'count'  => $count,
		);

		return $this->render_json($result, 10000, 'get madapter attribute success.');
	}

	// }}}
	// {{{ public function action_mod()

	/**
	 * 修改监控适配器属性 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_mod()
	{	
		$mid  = $this->__request->get_post('madapter_id', '');
		$aid  = $this->__request->get_post('attr_id', '');
		$name = $this->__request->get_post('name', '');
		$display_name = $this->__request->get_post('display_name', '');
		$form_type = $this->__request->get_post('form_type', '');
		$form_data = $this->__request->get_post('form_data', '');
		$attr_default = $this->__request->get_post('attr_default', '');
		if (!$mid || !$aid) {
			return $this->render_json(null, 10001, '`madapter_id` and `attr_id` not allow is empty.');
		}

		// 修改 madapter attribute
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

		if ($attr_default) {
			$data['attr_default'] = $attr_default;	
		}

		try {
			$property_attribute = sw_member::property_factory('madapter_attribute', $data); 
			$condition = sw_member::condition_factory('mod_madapter_attribute', array('madapter_id' => $mid, 'attr_id' => $aid));
			$condition->set_in('madapter_id');
			$condition->set_in('attr_id');
			$condition->set_property($property_attribute);
			$madapter = sw_member::operator_factory('madapter');
			$madapter->get_operator('attribute')->mod_attribute($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'mod madapter success.');
	}

	// }}}
	// }}}
}
