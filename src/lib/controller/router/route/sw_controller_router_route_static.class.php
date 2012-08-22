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
 
require_once PATH_SWAN_LIB . 'controller/router/route/sw_controller_router_route_abstract.class.php';
/**
+------------------------------------------------------------------------------
* sw_controller_router_route_static 
+------------------------------------------------------------------------------
* 
* @uses sw_controller_router_route_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_router_route_static extends sw_controller_router_route_abstract
{
	// {{{ members

	/**
	 * http request对象 
	 * 
	 * @var sw_controller_request_http
	 * @access protected
	 */
	protected $__request;

	/**
	 * 允许请求的控制器参数
	 *   /user/?q=system_config
	 *   /user/?q=system_config.do
	 * array(
	 *	'user' => array(
	 *		'system_config'    = true
	 *		'system_config.do' = true
	 *  ),
	 *	'admin' => array(
	 *		'system_config'    = true
	 *		'system_config.do' = true
	 *  ),
	 * ); 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__allow = array();

	/**
	 * 模块的KEY 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__module_key;

	/**
	 * 控制器的key 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__controller_key;


	/**
	 * 方法的key 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__action_key;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param array $allow 
	 * @param sw_controller_request_http $request 
	 * @access public
	 * @return void
	 */
	public function __construct(array $allow, sw_controller_request_http $request) {
		$this->set_request($request)
			 ->set_allow($allow);			
	}
	
	// }}}
	// {{{ public function set_request()

	/**
	 * 设置request对象 
	 * 
	 * @param sw_controller_request_http $request 
	 * @access public
	 * @return sw_controller_router_route_static
	 */
	public function set_request(sw_controller_request_http $request)
	{
		$this->__request = $request;
		return $this;	
	}

	// }}}
	// {{{ public function set_allow()

	/**
	 * 设置allow 
	 * 
	 * @param  array $allow
	 * @access public
	 * @return sw_controller_router_route_static
	 */
	public function set_allow(array $allow)
	{
		$this->__allow = $allow;
		return $this;	
	}

	// }}}
	// {{{ public function match()
	
	/**
	 * 匹配路由 
	 * 
	 * @param sw_controller_request_http $request 
	 * @access public
	 * @return array
	 */
	public function match(sw_controller_request_http $request)
	{
		$this->set_request($request);
		$this->__module_key     = $this->__request->get_module_key();
		$this->__controller_key = $this->__request->get_controller_key();
		$this->__action_key     = $this->__request->get_action_key();

		$request_uri = $this->__request->get_request_uri();

		$values  = array();
		$params = array(); 

		if (false === ($pos = strpos($request_uri, '?')) || (0 === $pos)) {
			return false;
		}

		list($module, $gets) = explode('?', $request_uri);
		if (false === ($pos = strpos($gets, '=')) || (0 === $pos)) {
			return false;
		}

		$module = trim($module, '/');
		$param = (array) explode('&', $gets);
		$has_controller = false;
		foreach ($param as $value) {
			if (false !== strpos('q=', $value))	{
				list($tmp, $controller) = explode('=', $value);
				$has_controller = true;
			}

			list($name, $param_value) = explode('=', $value);
			$params[$name] = $param_value;
		}

		if (!$has_controller) {
			return false;	
		}
		
		if (isset($this->__allow[$module][$controller])) {
			if ('.do' === substr($controller, -3)) {
				$action = 'action_do';
				$controller = substr($controller, 0, -3);
			}

			$values[$this->__module_key]     = $module;
			$values[$this->__controller_key] = $controller;
			$values[$this->__action_key]     = $action;
		}

		return ($values + $params);
	}

	// }}}	
	// {{{ public function assemble()

	/**
	 * 生成url 
	 * 
	 * @param array $data 
	 * @param boolean $reset 
	 * @param boolean $encode 
	 * @access public
	 * @return string
	 */
	public function assemble($data = array(), $reset = false, $encode = false)
	{
		return '';	
	}

	// }}}
	// }}} functions end	
}
