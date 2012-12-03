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
 
require_once PATH_SWAN_LIB . 'ui/action/sw_controller_action_web.class.php';

/**
+------------------------------------------------------------------------------
* 管理端设备列表
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_admin_device_list_action extends sw_controller_action_web
{
	// {{{ const

	/**
	 * SNMP版本  
	 */
	const SNMP_VERSION_1 = 0;
	const SNMP_VERSION_2 = 1;
	const SNMP_VERSION_3 = 2;

	/**
	 * 获取方式  
	 */
	const METHOD_EXEC = 0;
	const METHOD_EXT  = 1;

	/**
	 * 通讯协议  
	 */
	const PROTOCOL_NET = 0;
	const PROTOCOL_UDP = 1;

	// }}}
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function action_default()

	/**
	 * action_default 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_default()
	{
		$page       = $this->__request->get_query('page', 1);
		$rows_count = $this->__request->get_query('rows_count', 10);
		$method     = $this->__request->get_query('method', 'tpl');

		$device_condition = sw_orm::condition_factory('rrd', 'device:get_device');
		$device_condition->set_columns(array('device_id', 'device_name', 'host', 'snmp_version', 'snmp_protocol'));
		try {
			$device_operator = sw_orm::operator_factory('rrd', 'device');	
			$device_condition->set_is_count(true);
			$total = $device_operator->get_device($device_condition);	

			$device_condition->set_is_count(false);
			$device_condition->set_limit_page(array('page' => $page, 'rows_count' => $rows_count));
			$device_infos = $device_operator->get_device($device_condition);
		} catch (sw_exception $e) {
			return false;	
		}

		//格式化信息
		$snmp_versions = array('VERSION_1', 'VERSION_2', 'VERSION_3');
		$snmp_methods  = array('EXEC', 'EXT');
		$snmp_protocols = array('NET', 'UDP');
		$list = array();
		foreach ($device_infos as $key => $value) {
			$list[$key]['version']  = $snmp_versions[$value['snmp_version']];
			$list[$key]['protocol'] = $snmp_protocols[$value['snmp_protocol']];
			$list[$key] = array_merge($value, $list[$key]);	
		}

		if ('tpl' == $method) {
			$tpl_params['list'] = $list;
			$tpl_params['page'] = $page;
			$tpl_params['total'] = $total;
			$this->render('device_list.html', $tpl_params);	
		} else {
			return $this->json_stdout(array('res' => 0, 'data' => $list));	
		}

	}

	// }}}
	// {{{ public function action_do()

	/**
	 * action_do
	 * 
	 * @access public
	 * @return void
	 */
	public function action_do()
	{
		$action = $this->__request->get_query('action', '');
		switch ($action) {
			case 'get_detail':
				$this->_get_detail();
				break;
			case 'batch_delete':
				$this->_batch_delete();
				break;
			default:
				break;	
		}
	}

	// }}}
	// {{{ protected function _get_detail()

	/**
	 * 获取设备的详情 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _get_detail()
	{
		$device_id = $this->__request->get_post('device_id', '');

		$device_condition = sw_orm::condition_factory('rrd', 'device:get_device');
		$device_condition->set_eq('device_id');
		$device_condition->set_device_id($device_id);
		try {
			$device_operator = sw_orm::operator_factory('rrd', 'device');	
			$device_infos = $device_operator->get_device($device_condition);
		} catch (sw_exception $e) {
			return false;	
		}

		//格式化信息
		$snmp_versions   = array('VERSION_1', 'VERSION_2', 'VERSION_3');
		$snmp_methods    = array('EXEC', 'EXT');
		$snmp_protocols  = array('NET', 'UDP');
		$security_levels = array('noAuthNoPriv', 'authNoPriv', 'authPriv');
		$auth_protocols  = array('MD5', 'SHA');
		$priv_protocols   = array('DES', 'AES');
		$list = array();
		$tmp_arr = $device_infos[0];

		//基本信息
		$list['base']['version']	  = $snmp_versions[$tmp_arr['snmp_version']];
		$list['base']['protocol']	  = $snmp_protocols[$tmp_arr['snmp_protocol']];
		$list['base']['method']		  = $snmp_methods[$tmp_arr['snmp_method']];
		$list['base']['device_name']  = $tmp_arr['device_name'];
		$list['base']['host']		  = $tmp_arr['host'];
		$list['base']['port']		  = $tmp_arr['port'];
		$list['base']['snmp_timeout'] = $tmp_arr['snmp_timeout'];
		$list['base']['snmp_retries'] = $tmp_arr['snmp_retries'];
		$list['base']['snmp_version'] = $tmp_arr['snmp_version'];

		//认证信息
		if (self::SNMP_VERSION_3 == $tmp_arr['snmp_version']) {
			$list['auth']['security_name']   = $tmp_arr['security_name'];	
			$list['auth']['security_level']  = $security_levels[$tmp_arr['security_level']];	
			$list['auth']['auth_protocol']   = $auth_protocols[$tmp_arr['auth_protocol']];	
			$list['auth']['auth_passphrase'] = $tmp_arr['auth_passphrase'];	
			$list['auth']['priv_protocol']   = $priv_protocols[$tmp_arr['priv_protocol']];	
			$list['auth']['priv_passphrase'] = $tmp_arr['priv_passphrase'];	
		} else {
			$list['base']['snmp_community'] = $tmp_arr['snmp_community'];
		}

		return $this->json_stdout(array('res' => 0, 'data' => $list));	
	}

	// }}}
	// {{{ protected function _batch_delete()

	/**
	 * 删除设备 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _batch_delete()
	{
		$device_ids = $this->__request->get_post('device_id', '');
		$device_id_arr = explode(",", $device_ids);

		$device_condition = sw_orm::condition_factory('rrd', 'device:del_device');
		$device_condition->set_in('device_id');
		$device_condition->set_device_id($device_id_arr);
		try {
			$device_operator = sw_orm::operator_factory('rrd', 'device');	
			$device_operator->del_device($device_condition);
		} catch (sw_exception $e) {
			return $this->json_stdout(array('res' => 0, 'message' => gettext('删除设备失败。') . $e->getMessage()));	
		}

		return $this->json_stdout(array('res' => 0, 'message' => gettext('删除设备成功')));	
	}

	// }}}
	// }}}
}
