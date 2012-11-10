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

require_once PATH_SWAN_LIB . 'controller/router/sw_controller_router_abstract.class.php';

/**
+------------------------------------------------------------------------------
* sw_controller_router_router 
+------------------------------------------------------------------------------
* 
* @uses sw
* @uses _controller_router_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_router_router extends sw_controller_router_abstract 
{
	// {{{ members

	/**
	 * 是否启用默认路由器 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__use_default_routes = true;

	/**
	 * 存放路由器 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__routes = array();

	/**
	 * 路由器信息map表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__route_map = array();

	/**
	 * 设置当前使用的路由 
	 * 
	 * @var sw_controller_router_route_interface
	 * @access protected
	 */
	protected $__current_route = null; 

	/**
	 * 全局参数，针对所有的路由器 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__global_params = array();
	
	/**
	 * 当前路由是否用全局参数 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__use_current_param_as_global = false;

	// }}}
	// {{{ functions
	// {{{ public function add_default_routes()

	/**
	 * 添加默认的路由 
	 * 
	 * @access public
	 * @return sw_controller_router_router
	 */
	public function add_default_routes()
	{
		if (!$this->has_route('default')) {
			$request = $this->get_front_controller()->get_request();
			
			require_once PATH_SWAN_LIB . 'controller/router/route/sw_controller_router_route_static.class.php';

			$compat = new sw_controller_router_route_static($this->__route_map, $request);

			$this->__routes = array('default' => $compat) + $this->__routes;
		}

		return $this;
	}

	// }}}
	// {{{ public function set_route_map()

	/**
	 * 设置静态路由表 
	 * 
	 * @param array $array 
	 * @access public
	 * @return sw_controller_router_router
	 */
	public function set_route_map($array)
	{
		if (!is_array($array)) {
			return $this;	
		}	

		$this->__route_map = $array;

		return $this;
	}

	// }}}
	// {{{ public function add_route()
	
	/**
	 * 添加一个路由对象 
	 * 
	 * @param string $name 
	 * @param sw_controller_router_route_interface $route 
	 * @access public
	 * @return sw_controller_router_router
	 */
	public function add_route($name, sw_controller_router_route_interface $route)
	{
		if (method_exists($route, 'set_request')) {
			$route->set_request($this->get_front_controller()->get_request());
		} 

		$this->__routes[$name] = $route;

		return $this;
	}

	// }}}
	// {{{ public function add_routes()

	/**
	 * 批量添加路由器 
	 * 
	 * @param array $routes 
	 * @access public
	 * @return void
	 */
	public function add_routes($routes)
	{
		foreach ($routes as $name => $route) {
			$this->add_route($name, $route);	
		}

		return $this;
	}

	// }}}
	// {{{ public function remove_route()
	
	/**
	 * 移出某个路由 
	 * 
	 * @param string $name 
	 * @access public
	 * @throw sw_controller_router_exception
	 * @return sw_controller_router_router
	 */
	public function remove_route($name)
	{
		if (!isset($this->__routes[$name])) {
			require_once PATH_SWAN_LIB . 'controller/router/sw_controller_router_exception.class.php';
			throw new sw_controller_router_exception("Route $name is not defined");	
		}

		unset($this->__routes[$name]);

		return $this;
	}
	
	// }}}
	// {{{ public function remove_default_routes()

	/**
	 * 移出默认路由 
	 * 
	 * @access public
	 * @return sw_controller_router_router
	 */
	public function remove_default_routes()
	{
		$this->__use_default_routes = false;
		
		return $this;	
	}

	// }}}
	// {{{ public function has_route()

	/**
	 * 判断是否存在次路由 
	 * 
	 * @param string $name 
	 * @access public
	 * @return boolean
	 */
	public function has_route($name)
	{
		return isset($this->__routes[$name]);	
	}

	// }}}
	// {{{ public function get_route()

	/**
	 * 获取某个路由 
	 * 
	 * @param string $name 
	 * @access public
	 * @return sw_controller_router_route_interface
	 */
	public function get_route($name)
	{
		if (!isset($this->__routes[$name])) {
			require_once PATH_SWAN_LIB . 'controller/router/sw_controller_router_exception.class.php';
			throw new sw_controller_router_exception("Route $name is not defined");	
		}

		return $this->__routes[$name];
	}

	// }}}
	// {{{ public function get_current_route()
	
	/**
	 * 获取当前的路由 
	 * 
	 * @access public
	 * @return sw_controller_router_route_interface
	 */
	public function get_current_route()
	{
		if (!isset($this->__current_route)) {
			require_once PATH_SWAN_LIB . 'controller/router/sw_controller_router_exception.class.php';
			throw new sw_controller_router_exception("Current route is not defined");	
		}

		return $this->get_route($this->__current_route);
	}

	// }}}
	// {{{ public function get_current_route_name()
	
	/**
	 * 获取当前路由的名称 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_current_route_name()
	{
		if (!isset($this->__current_route)) {
			require_once PATH_SWAN_LIB . 'controller/router/sw_controller_router_exception.class.php';
			throw new sw_controller_router_exception("Current route is not defined");	
		}

		return $this->__current_route;
		
	}

	// }}}
	// {{{ public function get_routes()

	/**
	 * 获取所有路由器 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_routes()
	{
		return $this->__routes;	
	}

	// }}}
	// {{{ public function route()

	/**
	 * 匹配路由 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return sw_controller_request_http
	 */
	public function route(sw_controller_request_abstract $request)
	{
		if (!$request instanceof sw_controller_request_http) {
			require_once PATH_SWAN_LIB . 'controller/router/sw_controller_router_exception.class.php';
			throw new sw_controller_router_exception("sw_controller_router_router requires a sw_controller_request_http request object");	
		}

		if ($this->__use_default_routes) {
			$this->add_default_routes();	
		}

		$route_matched = false;

		foreach (array_reverse($this->__routes, true) as $name => $route) {
			if ($params = $route->match($request)) {
				$this->_set_request_params($request, $params);	
				$this->__current_route = $name;
				$route_matched = true;
				break;
			}
		}

		if (!$route_matched) {
			require_once PATH_SWAN_LIB . 'controller/router/sw_controller_router_exception.class.php';
			throw new sw_controller_router_exception("No route matched the request");	
		}

		if ($this->__use_current_param_as_global) {
			$params = $request->get_params();
			foreach ($params as $params => $value) {
				$this->set_global_param($param, $value);	
			}	
		}
		return $request;
	}

	// }}}
	// {{{ protected function _set_request_params()

	/**
	 * 将解析的参数注入到请求对象中 
	 * 
	 * @param sw_controller_request_http $request 
	 * @param array $params 
	 * @access protected
	 * @return void
	 */
	protected function _set_request_params($request, $params)
	{
		foreach ($params as $param => $value) {
			$request->set_param($param, $value);

			if ($param === $request->get_module_key()) {
				$request->set_module_name($value);
			}

			if ($param === $request->get_controller_key()) {
				$request->set_controller_name($value);
			}

			if ($param === $request->get_action_key()) {
				$request->set_action_name($value);
			}
		}
	}

	// }}}
	// {{{ public function assemble()

	/**
	 * 获得url地址 
	 * 
	 * @param array $user_params 
	 * @param string $name 
	 * @param boolean $reset 
	 * @param boolean $encode 
	 * @access public
	 * @return string
	 */
	public function assemble($user_params, $name = null, $reset = false, $encode = true)
	{
		if (!is_array($user_params)) {
			require_once PATH_SWAN_LIB . 'controller/router/sw_controller_router_exception.class.php';
			throw new sw_controller_router_exception("userParams must be an array");	
		}

		if ($name == null) {
			try {
				$name = $this->get_current_route_name();	
			} catch (sw_controller_router_exception $e) {
				$name = 'default';	
			}
		}

		$params = $user_params + $this->__global_params;

		$route = $this->get_route($name);
		$url   = $route->assemble($params, $reset, $encode);

		if (!preg_match('|^[a-z]+://|', $url)) {
			$url = $this->get_front_controller()->get_base_url();	
		}

		return $url;
	}

	// }}}
	// {{{ public function set_global_param()

	/**
	 * 设置全局参数 
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @access public
	 * @return sw_controller_router_router
	 */
	public function set_global_param($name, $value)
	{
		$this->__global_params[$name] = $value;

		return $this;
	}

	// }}}
	// {{{ public function use_request_parameter_as_global()

	/**
	 * 设置是否使用全局参数 
	 * 
	 * @param mixed $use 
	 * @access public
	 * @return sw_controller_router_router
	 */
	public function use_request_parameter_as_global($use = null)
	{
		if ($use == null) {
			return $this->__use_current_param_as_global;	
		}	

		$this->__use_current_param_as_global = (bool) $use;

		return $this;
	}

	// }}}
	// }}} function end
}
