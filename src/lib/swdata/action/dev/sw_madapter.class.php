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
* 监控适配器接口 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_madapter extends sw_abstract
{
	// {{{ functions
	// {{{ public function action_add()

	/**
	 * 添加监控适配器 
	 * 
	 * @access public
	 * @return intger
	 */
	public function action_add()
	{
		$madapter_name = $this->__request->get_post('name', '');
		$madapter_display_name = $this->__request->get_post('display_name', '');
		$steps = $this->__request->get_post('steps', '');
		$store_type   = $this->__request->get_post('store_type', '2');
		$madapter_type = $this->__request->get_post('madapter_type', '2');
		if (!$madapter_name) {
			return $this->render_json(null, 10001, '`name` not allow is empty.');
		}

		$data = array(
			'madapter_name' => $madapter_name,
			'madapter_display_name' => $madapter_display_name,
			'steps' => $steps,
			'store_type'   => $store_type,
			'madapter_type' => $madapter_type,			
		);

		// 添加 madapter basic
		try {
			$property_basic = sw_member::property_factory('madapter_basic', $data); 
			$madapter    = sw_member::operator_factory('madapter', $property_basic);
			$madapter_id = $madapter->get_operator('basic')->add_basic();
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(array('madapter_id' => $madapter_id), 10000, 'add madapter success.');
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
		$madapter_name = $this->__request->get_post('name', '');
		$madapter_id   = $this->__request->get_post('madapter_id', '');
		if (!$madapter_name && !$madapter_id) {
			return $this->render_json(null, 10001, '`name` or `madapter_id` not allow is empty.');
		}

		// 删除监控适配器
		try {
			if ($madapter_id) {
				$condition_basic = sw_member::condition_factory('del_madapter_basic', array('madapter_id' => $madapter_id)); 
				$condition_basic->set_in('madapter_id');
			} else {
				$condition_basic = sw_member::condition_factory('del_madapter_basic', array('madapter_name' => $madapter_name)); 
				$condition_basic->set_in('madapter_name');
			}
			$madapter = sw_member::operator_factory('madapter');
			$madapter->get_operator('basic')->del_basic($condition_basic);
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
		// 获取监控适配器
		$length = $this->__request->get_post('length', 10);
		$start = $this->__request->get_post('start', 0);
		$count = 0;
		try {
			$condition_basic = sw_member::condition_factory('get_madapter_basic'); 
			$condition_basic->set_is_count(true);
			$madapter = sw_member::operator_factory('madapter');
			$count   = $madapter->get_operator('basic')->get_basic($condition_basic);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		if (!$count) {
			return $this->render_json(array('count' => $count), 10001, 'no data.');	
		}

		try {
			$condition_basic->set_is_count(false);
			$condition_basic->set_columns('*');
			$condition_basic->set_limit(array('count' => $length, 'offset' => $start));
			$madapter = sw_member::operator_factory('madapter');
			$data   = $madapter->get_operator('basic')->get_basic($condition_basic);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10001, $e->getMessage());	
		}

		$result = array(
			'result' => $data,
			'count'  => $count,
		);

		return $this->render_json($result, 10000, 'get madapter success.');
	}

	// }}}
	// {{{ public function action_mod()

	/**
	 * 修改监控适配器 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_mod()
	{	
		$madapter_name = $this->__request->get_post('name', '');
		$madapter_id   = $this->__request->get_post('madapter_id', '');
		$madapter_display_name = $this->__request->get_post('display_name', '');
		$steps = $this->__request->get_post('steps', '');
		$store_type   = $this->__request->get_post('store_type', '');
		$madapter_type = $this->__request->get_post('madapter_type', '');
		if (!$madapter_id) {
			return $this->render_json(null, 10001, '`madapter_id` not allow is empty.');
		}

		if ($madapter_display_name) {
			$data['madapter_display_name'] = $madapter_display_name;	
		}

		if ($steps) {
			$data['steps'] = $steps;	
		}

		if ($store_type) {
			$data['store_type'] = $store_type;	
		}

		if ($madapter_type) {
			$data['madapter_type'] = $madapter_type;	
		}

		if ($madapter_name) {
			$data['madapter_name'] = $madapter_name;	
		}

		// 修改 madapter basic
		try {
			$property_basic = sw_member::property_factory('madapter_basic', $data); 
			$condition = sw_member::condition_factory('mod_madapter_basic', array('madapter_id' => $madapter_id));
			$condition->set_in('madapter_id');

			$condition->set_property($property_basic);
			$madapter = sw_member::operator_factory('madapter');
			$madapter->get_operator('basic')->mod_basic($condition);
		} catch (\swan\exception\sw_exception $e) {
			return $this->render_json(null, 10002, $e->getMessage());	
		}

		return $this->render_json(null, 10000, 'mod madapter success.');
	}

	// }}}
	// }}}
}
