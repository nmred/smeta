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
 
namespace lib\inner_client;
use \lib\inner_client\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 封装调用数据中心数据 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_inner_client
{
	// {{{ consts
	// }}}
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function call()
	
	/**
	 * 调用接口 
	 * 
	 * @param string $module 
	 * @param string $action 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function call($module, $action, $params = array())
	{
		$config = \swan\config\sw_config::get_config('data_host');
		if (!isset($config['host']) || !isset($config['port'])) {
			throw new sw_exception('data center host or port empty.');		
		}

		$url = 'http://%s:%s/%s/?/%s';
		$url = sprintf($url, $config['host'], $config['port'], $module, $action);
		$curl = new \swan\curl\sw_curl($url);
		$curl->set_params($params);
		$return_content = $curl->call();
		if (!$return_content) {
			throw new sw_exception('get data from data center.');	
		}

		$data = json_decode($return_content, true);
		if (isset($data['code'])) {
			return $data;
		}

		throw new sw_exception('get data from data center.');	
	}

	// }}}
	// }}}
}
