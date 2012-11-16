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
 
require_once PATH_SWAN_LIB . 'controller/action/sw_controller_action_helper_broker.class.php';
require_once PATH_SWAN_LIB . 'controller/plugin/sw_controller_plugin_broker.class.php';

/**
+------------------------------------------------------------------------------
* 前端控制器
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_front
{
	// {{{ members

	/**
	 * 基地址 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__base_url = null;

	/**
	 * 控制器目录 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__controller_dir = null;

	/**
	 * 分发器 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__dispatcher = null;

	/**
	 * 单例 
	 * 
	 * @var string
	 * @access protected
	 */
	protected static $__instance = null;

	/**
	 * 参数列表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__invoke_params = array();

	/**
	 * 插件 
	 * 
	 * @var sw_controller_plugin_abstract
	 * @access protected
	 */
	protected $__plugins = null;

	/**
	 * 请求对象 
	 * 
	 * @var sw_controller_request_abstract
	 * @access protected
	 */
	protected $__request = null;

	/**
	 * 响应对象 
	 * 
	 * @var sw_controller_response_abstract
	 * @access protected
	 */
	protected $__response = null;

	/**
	 * 判断是否返回响应的内容，而不输出，默认是直接输出通过send_response 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__return_response = false;

	/**
	 * 路由器对象 
	 * 
	 * @var sw_controller_router_abstract
	 * @access protected
	 */
	protected $__router = null;

	/**
	 * 是否抛出异常 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__throw_exceptions = false;

	// }}}
	// {{{ functions
	// {{{ protected function __construct()

	/**
	 * 构造器 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function __construct()
	{
		$this->__plugins = new sw_controller_plugin_broker();
	}

	// }}}
	// {{{ private function __clone()
	
	/**
	 * __clone 
	 * 
	 * @access private
	 * @return void
	 */
	private function __clone()
	{
		//为了防止克隆这个对象
	}

	// }}} function end
	// {{{ public static function get_instance()

	/**
	 * 生成对象，实现单件模式，不允许创建多个对象 
	 * 
	 * @static
	 * @access public
	 * @return sw_controller_front
	 */
	public static function get_instance()
	{
		if (null === self::$__instance) {
			self::$__instance = new self();	
		}	

		return self::$__instance;
	}

	// }}}
	// {{{ public function reset_instance()

	/**
	 * 重置对象 
	 * 
	 * @access public
	 * @return void
	 */
	public function reset_instance()
	{
		$reflection = new ReflectionObject($this);
		foreach ($reflection->getProperties() as $property) {
			$name = $property->getName();
			switch ($name) {
				case '__instance' :
					break;
				case '__invoke_params':
					$this->{$name} = array();
					break;
				case '__plugins':
					$this->{$name} = new sw_controller_plugin_broker();
					break;
				case '__throw_exception':
				case '__return_response':
					$this->{$name} = false;
					break;
				default:
					$this->{$name} = null;
					break;
			}	
		}
		
		sw_controller_action_helper_broker::reset_helpers();	
	}

	// }}}
	// {{{ public static function run()
	
	/**
	 * 运行 
	 * 
	 * @param string $controller_directory 
	 * @access public
	 * @return void
	 */
	public function run($controller_directory)
	{
		self::get_instance()
			->set_controller_directory($controller_directory)
			->dispatch();	
	}

	// }}}
	// {{{ public function add_controller_directory()
	
	/**
	 * 添加控制器目录 
	 * 
	 * @param string $directory 
	 * @param string $module 
	 * @access public
	 * @return sw_controller_front
	 */
	public function add_controller_directory($directory, $module = null)
	{
		$this->get_dispatcher()->add_controller_directory($directory, $module);
		return $this;
	}

	// }}}
	// {{{ public function set_controller_directory()

	/**
	 * 设置控制器目录 
	 * 
	 * @param string $directory 
	 * @param string $module 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_controller_directory($directory, $module = null)
	{
		$this->get_dispatcher()->set_controller_directory($directory, $module);
		return $this;
	}

	// }}}
	// {{{ public function get_controller_directory()

	/**
	 * 获取控制器目录 
	 * 
	 * @param string $name 
	 * @access public
	 * @return sw_controller_front
	 */
	public function get_controller_directory($name = null)
	{
		return $this->get_dispatcher()->get_controller_directory($name);	
	}

	// }}}
	// {{{ public function remove_controller_directory()

	/**
	 * 移除控制器目录 
	 * 
	 * @access public
	 * @param string $module
	 * @return boolean
	 */
	public function remove_controller_directory($module)
	{
		return $this->get_dispatcher()->remove_controller_directory($module);
	}

	// }}}
	// {{{ public function set_default_controller_name()

	/**
	 * 设置默认控制器名称 
	 * 
	 * @param string $controller 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_default_controller_name($controller)
	{
		$this->get_dispatcher()->set_default_controller_name($controller);	
		return $this;
	}

	// }}}
	// {{{ public function get_default_controller_name()

	/**
	 * 获取默认的控制器名称 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_controller_name()
	{
		return $this->get_dispatcher()->get_default_controller_name();	
	}

	// }}}
	// {{{ public function set_default_action()

	/**
	 * 设置默认的action 
	 * 
	 * @param string $action 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_default_action($action)
	{
		$this->get_dispatcher()->set_default_action($action);
		return $this;
	}

	// }}}
	// {{{ public function get_default_action()

	/**
	 * 获取默认的action 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_action()
	{
		return $this->get_dispatcher()->get_default_action();	
	}

	// }}}
	// {{{ public function get_default_module()
	
	/**
	 * 获取默认的模块 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_module()
	{
		return $this->get_dispatcher()->get_default_module();	
	}

	// }}}
	// {{{ public function set_request()
	
	/**
	 * 设置请求对象 
	 * 
	 * @param string|sw_controller_request_abstract $request 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_request($request)
	{
		if (is_string($request)) {
			if (!class_exists($request)) {
				require_once PATH_SWAN_LIB . 'controller/request/' . $request . '.class.php';	
			}	
			$request = new $request();
		}

		if (!$request instanceof sw_controller_request_abstract) {
			require_once PATH_SWAN_LIB . 'controller/sw_controller_exception.class.php';
			throw new sw_controller_exception('Invalid request class');	
		}

		$this->__request = $request;

		return $this;
	}

	// }}}
	// {{{ public function get_request()

	/**
	 * 获取请求对象 
	 * 
	 * @access public
	 * @return sw_controller_front
	 */
	public function get_request()
	{
		return $this->__request;	
	}

	// }}}
	// {{{ public function set_router()

	/**
	 * 设置路由器 
	 * 
	 * @param sw_controller_router_abstract $router 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_router($router)
	{
		if (is_string($router)) {
			if (!class_exists($router)) {
				require_once PATH_SWAN_LIB . 'controller/router/' . $router . '.class.php';
				$router = new $router();	
			}
		}

		if (!$router instanceof sw_controller_router_abstract) {
			require_once PATH_SWAN_LIB . 'controller/sw_controller_exception.class.php';
			throw new sw_controller_exception('Invalid router class');	
		}

		$router->set_front_controller($this);

		$this->__router = $router;

		return $this;
	}

	// }}}
	// {{{ public function get_router()

	/**
	 * 获取路由器 
	 * 
	 * @access public
	 * @return sw_controller_router_router
	 */
	public function get_router()
	{
		if (null == $this->__router) {
			require_once PATH_SWAN_LIB . 'controller/router/sw_controller_router_router.class.php';
			$this->set_router(new sw_controller_router_router());
		}	

		return $this->__router;
	}

	// }}}
	// {{{ public function set_base_url()

	/**
	 * 设置基地址 
	 * 
	 * @param string $base 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_base_url($base = null)
	{
		if (!is_string($base) && (null !== $base)) {
			require_once PATH_SWAN_LIB . 'controller/sw_controller_exception.class.php';
			throw new sw_controller_exception('Rewrite base must be a string');	
		}

		$this->__base_url = $base;

		if ((null !== ($request = $this->get_request())) && (method_exists($request, 'set_base_url'))) {
			$request->set_base_url($base);	
		}

		return $this;
	}

	// }}}
	// {{{ public function get_base_url()

	/**
	 * 获取基地址 
	 * 
	 * @access public
	 * @return sw_controller_front
	 */
	public function get_base_url()
	{
		$request = $this->get_request();
		if ((null !== $request) && method_exists($request, 'get_base_url'))	{
			return $request->get_base_url();	
		}

		return $this->__base_url;
	}

	// }}}
	// {{{ public function set_dispatcher()

	/**
	 * 设置分发器 
	 * 
	 * @param sw_controller_dispatcher_interface $dispatcher 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_dispatcher(sw_controller_dispatcher_interface $dispatcher)
	{
		$this->__dispatcher = $dispatcher;
		return $this;	
	}

	// }}}
	// {{{ public function get_dispatcher()

	/**
	 * 获取分发器 
	 * 
	 * @access public
	 * @return sw_controller_dispatcher_standard
	 */
	public function get_dispatcher()
	{
		if (!$this->__dispatcher instanceof sw_controller_dispatcher_interface) {
			require_once PATH_SWAN_LIB . 'controller/dispatcher/sw_controller_dispatcher_standard.class.php';
			$this->__dispatcher = new sw_controller_dispatcher_standard();	
		}

		return $this->__dispatcher;
	}

	// }}}
	// {{{ public function set_response()

	/**
	 * 设置响应对象 
	 * 
	 * @access public
	 * @prams sw_controller_response_abstract|string $response
	 * @return sw_controller_front
	 */
	public function set_response($response)
	{
		if (is_string($response)) {
			if (!class_exists($response)) {
				require_once PATH_SWAN_LIB . 'controller/response/' . $response . '.class.php';	
			}
			$response = new $response();
		}

		if (!$response instanceof sw_controller_response_abstract) {
			require_once PATH_SWAN_LIB . 'controller/sw_controller_exception.class.php';
			throw new sw_controller_exception('Invalid response class');	
		}

		$this->__response = $response;
		return $this;
	}

	// }}}
	// {{{ public function get_response()

	/**
	 * 获取一个响应对象 
	 * 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function get_response()
	{
		return $this->__response;	
	}

	// }}}
	// {{{ public function set_param()

	/**
	 * 设置单个参数 
	 * 
	 * @param string $name 
	 * @param string $value 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_param($name, $value)
	{
		$name = (string) $name;
		$this->__invoke_params[$name] = $value;
		return $this;	
	}

	// }}}
	// {{{ public function set_params()

	/**
	 * 设置参数 
	 * 
	 * @param array $params 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_params(array $params)
	{
		$this->__invoke_params = array_merge($this->__invoke_params, $params);
		return $this;	
	}

	// }}}
	// {{{ public function get_param()

	/**
	 * 获取参数 
	 * 
	 * @param string $name 
	 * @access public
	 * @return string
	 */
	public function get_param($name)
	{
		if (isset($this->__invoke_params[$name])) {
			return $this->__invoke_params[$name];	
		}

		return null;
	}

	// }}}
	// {{{ public function get_params()

	/**
	 * 获取所有的参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_params()
	{
		return $this->__invoke_params;	
	}

	// }}}
	// {{{ public function clear_params()

	/**
	 * 清除参数 
	 * 
	 * @param string $name 
	 * @access public
	 * @return sw_controller_front
	 */
	public function clear_params($name = null)
	{
		if (null === $name) {
			$this->__invoke_params = array();	
		} elseif (is_string($name) && isset($this->__invoke_params[$name])) {
			unset($this->__invoke_params[$name]);	
		} elseif (is_array($name)) {
			foreach ($name as $key) {
				if (is_string($key) && isset($this->__invoke_params[$key])) {
					unset($this->__invoke_params[$key]);	
				}
			}	
		}

		return $this;
	}

	// }}}
	// {{{ public function register_plugin()

	/**
	 * 注册一个插件 
	 * 
	 * @param sw_controller_plugin_abstract $plugin 
	 * @param int $stack_index 
	 * @access public
	 * @return sw_controller_front
	 */
	public function register_plugin(sw_controller_plugin_abstract $plugin, $stack_index = null)
	{
		$this->__plugins->register_plugin($plugin, $stack_index);
		return $this;	
	}

	// }}}
	// {{{ public function unregister_plugin()

	/**
	 * 注销一个插件 
	 * 
	 * @param string|sw_controller_plugin_abstract $plugin 
	 * @access public
	 * @return sw_controller_front
	 */
	public function unregister_plugin($plugin)
	{
		$this->__plugins->unregister_plugin($plugin);
		return $this;	
	}

	// }}}
	// {{{ public function has_plugin()

	/**
	 * 判断是否存在该插件 
	 * 
	 * @param string $class 
	 * @access public
	 * @return boolean
	 */
	public function has_plugin($class)
	{
		return $this->__plugins->has_plugin($class);	
	}

	// }}}
	// {{{ public function get_plugin()

	/**
	 * 获取某个插件 
	 * 
	 * @param string $class 
	 * @access public
	 * @return false | sw_controller_plugin_abstract
	 */
	public function get_plugin($class)
	{
		return $this->__plugins->get_plugin($class);	
	}

	// }}}
	// {{{ public function get_plugins()

	/**
	 * 获取所有插件 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_plugins()
	{
		return $this->__plugins->get_plugins();	
	}

	// }}}
	// {{{ public function throw_exceptions()

	/**
	 * 判断是否抛出异常 
	 * 
	 * @param boolean|null $flag 
	 * @access public
	 * @return boolean
	 */
	public function throw_exceptions($flag = null)
	{
		if ($flag !== null) {
			$this->__throw_exceptions = (bool) $flag;
			return $this;
		}

		return $this->__throw_exceptions;
	}

	// }}}
	// {{{ public function return_response()

	/**
	 * 判断和设置是否返回请求 
	 * 
	 * @param boolean|null $flag 
	 * @access public
	 * @return sw_controller_front|boolean
	 */
	public function return_response($flag = null)
	{
		if (true === $flag) {
			$this->__return_response = true;
			return $this;	
		} elseif (false === $flag) {
			$this->__return_response = false;
			return $this;	
		}

		return $this->__return_response;
	}

	// }}}
	// {{{ public function dispatch()

	/**
	 * 分发请求所得到的内容 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @param sw_controller_response_abstract $response 
	 * @access public
	 * @return void
	 */
	public function dispatch(sw_controller_request_abstract $request = null, sw_controller_response_abstract $response = null)
	{
		if (!$this->get_param('no_error_handler') 
			&& !$this->__plugins->has_plugin('sw_controller_plugin_error_handler')) {
			require_once PATH_SWAN_LIB . 'controller/plugin/sw_controller_plugin_error_handler.class.php';
			$this->__plugins->register_plugin(new sw_controller_plugin_error_handler(), 100);		
		}

		//初始化request对象
		if (null !== $request) {
			$this->set_request($request);	
		} elseif ((null === $request) && (null === ($request = $this->get_request()))) {
			require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_http.class.php';
			$request = new sw_controller_request_http();
			$this->set_request($request);
		}

		if (is_callable(array($this->__request, 'set_base_url'))) {
			if (null !== $this->__base_url) {
				$this->__request->set_base_url($this->__base_url);	
			}	
		}

		//初始化response对象
		if (null !== $response) {
			$this->set_response($response);	
		} elseif ((null === $response) && (null == ($response = $this->get_response()))) {
			require_once PATH_SWAN_LIB . 'controller/response/sw_controller_response_http.class.php';
			$response = new sw_controller_response_http();
			$this->set_response($response);
		}

		//初始化插件助手
		$this->__plugins
			 ->set_request($this->__request)
			 ->set_response($this->__response);

		//初始化路由器
		$router = $this->get_router();
		$router->set_params($this->get_params());
		
		//初始化分发器
		$dispatcher = $this->get_dispatcher();
		$dispatcher->set_params($this->get_params())
				   ->set_response($this->__response);

		//开始分发
		try {
			$this->__plugins->route_startup($this->__request);
			
			try {
				$router->route($this->__request);	
			} catch (Exception $e) {
				if ($this->throw_exceptions()) {
					throw $e;
				}

				$this->__response->set_exception($e);
			}
			
			$this->__plugins->route_shutdown($this->__request);

			$this->__plugins->dispatch_loop_startup($this->__request);

			do {
				$this->__request->set_dispatched(true);
				
				$this->__plugins->pre_dispatch($this->__request);	

				if (!$this->__request->is_dispatched()) {
					continue;	
				}

				try {
					$dispatcher->dispatch($this->__request, $this->__response);	
				} catch (Exception $e) {
					if ($this->throw_exceptions()) {
						throw $e;	
					}
					$this->__response->set_exception($e);
				}

				$this->__plugins->post_dispatch($this->__request);	

			} while (!$this->__request->is_dispatched());
		
		} catch (Exception $e) {
			if ($this->throw_exceptions()) {
				throw $e;	
			}
			$this->__response->set_exception($e);
		}

		try {
			$this->__plugins->dispatch_loop_shutdown($this->__request);
		} catch (Exception $e) {
			if ($this->throw_exceptions()) {
				throw $e;	
			}
			$this->__response->set_exception($e);
		}

		if ($this->return_response()) {
			return $this->__response;	
		}

		$this->__response->send_response();
	}

	// }}}
	// }}}
}
