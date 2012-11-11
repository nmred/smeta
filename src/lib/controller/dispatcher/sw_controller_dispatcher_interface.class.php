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
 
require_once PATH_SWAN_LIB . 'controller/response/sw_controller_response_abstract.class.php';
require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_abstract.class.php';

/**
+------------------------------------------------------------------------------
* 分发器接口
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
interface sw_controller_dispatcher_interface
{
	// {{{ functions
	// {{{ public function format_controller_name()

	/**
	 * 格式化控制器名称 
	 * 
	 * @param string $unformatted 
	 * @access public
	 * @return string
	 */
	public function format_controller_name($unformatted);

	// }}}
	// {{{ public function format_module_name()

	/**
	 * 格式化模块名称
	 * 
	 * @param string $unformatted 
	 * @access public
	 * @return string
	 */
	public function format_module_name($unformatted);

	// }}}
	// {{{ public function format_action_name()

	/**
	 * 格式化动作名称 
	 * 
	 * @param string $unformatted 
	 * @access public
	 * @return string
	 */
	public function format_action_name($unformatted);

	// }}}
	// {{{ public function is_dispatchable()
	
	/**
	 * 是否可以分发 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return boolean
	 */
	public function is_dispatchable(sw_controller_request_abstract $request);

	// }}}
	// {{{ public function set_param()

	/**
	 * 设置参数 
	 * 
	 * @param string $name 
	 * @param string $value 
	 * @access public
	 * @return sw_controller_dispatcher_interface
	 */
	public function set_param($name, $value);

	// }}}
	// {{{ public function set_params()

	/**
	 * 设置参数 
	 * 
	 * @param array $params 
	 * @access public
	 * @return sw_controller_dispatcher_interface
	 */
	public function set_params(array $params);

	// }}}
	// {{{ public function get_param()

	/**
	 * 获取某个参数 
	 * 
	 * @param string $name 
	 * @access public
	 * @return mixed
	 */
	public function get_param($name);

	// }}}
	// {{{ public function get_params()

	/**
	 * 获取所有的参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_params();

	// }}}
	// {{{ public function clear_params()

	/**
	 * 清除全部或某个参数 
	 * 
	 * @param null|string|array $name 
	 * @access public
	 * @return sw_controller_dispatcher_interface
	 */
	public function clear_params($name = null);

	// }}}
	// {{{ public function set_response()

	/**
	 * 设置响应对象 
	 * 
	 * @param sw_controller_response_abstract $response 
	 * @access public
	 * @return void
	 */
	public function set_response(sw_controller_response_abstract $response = null);

	// }}}
	// {{{ public function get_response()

	/**
	 * 获取响应对象 
	 * 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function get_response();

	// }}}
	// {{{ public function add_controller_directory()

	/**
	 * 添加控制器的目录 
	 * 
	 * @param string $path 
	 * @param string $args 
	 * @access public
	 * @return sw_controller_dispatcher_interface
	 */
	public function add_controller_directory($path, $args = null);

	// }}}
	// {{{ public function set_controller_directory()

	/**
	 * 设置控制器的目录 
	 * 
	 * @param string $path 
	 * @access public
	 * @return sw_controller_dispatcher_interface
	 */
	public function set_controller_directory($path);

	// }}}
	// {{{ public function get_controller_directory()

	/**
	 * 获取控制器的目录 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_controller_directory();

	// }}}
	// {{{ public function dispatch()

	/**
	 * 分发 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @param sw_controller_response_abstract $response 
	 * @access public
	 * @return void
	 */
	public function dispatch(sw_controller_request_abstract $request, sw_controller_response_abstract $response);

	// }}}
	// {{{ public function is_valid_module()

	/**
	 * 判断是否是合法的模块 
	 * 
	 * @param string $module 
	 * @access public
	 * @return boolean
	 */
	public function is_valid_module($module);

	// }}}
	// {{{ public function get_default_module()

	/**
	 * 获取默认的模型 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_module();

	// }}}
	// {{{ public function get_default_controller_name()

	/**
	 * 获取默认的控制器名称 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_controller_name();

	// }}}
	// {{{ public function get_default_action()

	/**
	 * 获取默认的方法 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_action();

	// }}}
	// }}}	
}
