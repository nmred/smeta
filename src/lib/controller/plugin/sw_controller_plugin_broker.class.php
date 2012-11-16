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
 
require_once PATH_SWAN_LIB . 'controller/plugin/sw_controller_plugin_abstract.class.php';

/**
+------------------------------------------------------------------------------
* sw_controller_plugin_broker 
+------------------------------------------------------------------------------
* 
* @uses sw_controller_plugin_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_plugin_broker extends sw_controller_plugin_abstract
{
	// {{{ members

	/**
	 * 插件对象的集合 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__plugins = array();

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

	// }}}
	// {{{ functions
	// {{{ public function register_plugin()

	/**
	 * 注册插件 
	 * 
	 * @param sw_controller_plugin_abstract $plugin 
	 * @param int $stack_index 
	 * @access public
	 * @return sw_controller_plugin_broker
	 */
	public function register_plugin(sw_controller_plugin_abstract $plugin, $stack_index = null)
	{
		if (false !== array_search($plugin, $this->__plugins, true)) {
			require_once PATH_SWAN_LIB . 'controller/sw_controller_exception.class.php';
			throw new sw_controller_exception('Plugin already registered');
		}

		$stack_index = (int) $stack_index;

		if ($stack_index) {
			if (isset($this->__plugins[$stack_index])) {
				require_once PATH_SWAN_LIB . 'controller/sw_controller_exception.class.php';
				throw new sw_controller_exception('Plugin with stackIndex "' . $stack_index . '" already registered');
			}	
			$this->__plugins[$stack_index] = $plugin;
		} else {
			$stack_index = count($this->__plugins);
			while(isset($this->__plugins[$stack_index])) {
				++$stack_index;	
			}	
			$this->__plugins[$stack_index] = $plugin;
		}

		$request = $this->get_request();
		if ($request) {
			$this->__plugins[$stack_index]->set_request($request);	
		}
		$response = $this->get_response();
		if ($response) {
			$this->__plugins[$stack_index]->set_response($response);	
		}

		krsort($this->__plugins);

		return $this;
	}

	// }}}
	// {{{ public function unregister_plugin()

	/**
	 * 取消注册一个插件 
	 * 
	 * @access public
	 * @param string|sw_controller_plugin_abstract $plugin 
	 * @return sw_controller_plugin_broker
	 */
	public function unregister_plugin($plugin)
	{
		if ($plugin instanceof sw_controller_plugin_abstract) {
			$key = array_search($plugin, $this->__plugins, true);
			if (false === $key) {
				require_once PATH_SWAN_LIB . 'controller/sw_controller_exception.class.php';
				throw new sw_controller_exception('Plugin never registered.');	
			}	
			unset($this->__plugins[$key]);
		} elseif (is_string($plugin)) {
			foreach ($this->__plugins as $key => $plugin_obj) {
				$type = get_class($plugin_obj);
				if ($plugin == $type) {
					unset($this->__plugins[$key]);	
				}	
			}
		}

		return $this;
	}

	// }}}
	// {{{ public function has_plugin()

	/**
	 * 判断是否存在某个插件 
	 * 
	 * @param string $class 
	 * @access public
	 * @return boolean
	 */
	public function has_plugin($class)
	{
		foreach ($this->__plugins as $key => $plugin) {
			$type = get_class($plugin);
			if ($class == $type) {
				return true;	
			}
		}	

		return false;
	}

	// }}}
	// {{{ public function get_plugin()

	/**
	 * 获取插件 
	 * 
	 * @param string $class 
	 * @access public
	 * @return sw_controller_plugin_abstract | boolean | array
	 */
	public function get_plugin($class)
	{
		$found = array();
		foreach ($this->__plugins as $plugin) {
			$type = get_class($plugin);
			if ($class == $type) {
				$found[] = $plugin;	
			}	
		}

		switch (count($found)) {
			case 0:
				return false;
			case 1:
				return $found[0];
			default:
				return $found;	
		}
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
		return $this->__plugins;	
	}

	// }}}
	// {{{ public function set_request()

	/**
	 * 设置请求对象 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return sw_controller_plugin_broker
	 */
	public function set_request(sw_controller_request_abstract $request)
	{
		$this->__request = $request;

		foreach ($this->__plugins as $plugin) {
			$plugin->set_request($request);	
		}

		return $this;
	}

	// }}}
	// {{{ public function get_request()

	/**
	 * 获取请求对象 
	 * 
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function get_request()
	{
		return $this->__request;
	}

	// }}}
	// {{{ public function set_response()

	/**
	 * 设置响应对象 
	 * 
	 * @param sw_controller_response_abstract $response 
	 * @access public
	 * @return sw_controller_plugin_broker
	 */
	public function set_response(sw_controller_response_abstract $response)
	{
		$this->__response = $response;

		foreach ($this->__plugins as $plugin) {
			$plugin->set_response($response);	
		}

		return $this;
	}

	// }}}
	// {{{ public function get_response()

	/**
	 * 获取响应对象 
	 * 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function get_response()
	{
		return $this->__response;
	}

	// }}}
	// {{{ public function route_startup()

	/**
	 * 在路由解析前执行 
	 * 
	 * @access public
	 * @param sw_controller_request_abstract $request 
	 * @return void
	 */
	public function route_startup(sw_controller_request_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->route_startup($request);	
			} catch (Exception $e) {
				if (sw_controller_front::get_instance()->throw_exception()) {
					throw new sw_controller_exception($e->getMessage() . $e->getTraceAsString());	
				} else {
					$this->get_response()->set_exception($e);	
				}
			}	
		}
	}

	// }}}
	// {{{ public function route_shutdown()

	/**
	 * 在路由解析后执行 
	 * 
	 * @access public
	 * @param sw_controller_request_abstract $request 
	 * @return void
	 */
	public function route_shutdown(sw_controller_request_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->route_shutdown($request);	
			} catch (Exception $e) {
				if (sw_controller_front::get_instance()->throw_exception()) {
					throw new sw_controller_exception($e->getMessage() . $e->getTraceAsString());	
				} else {
					$this->get_response()->set_exception($e);	
				}
			}	
		}
	}

	// }}}
	// {{{ public function dispatch_loop_startup()

	/**
	 * 在循环分发时执行 
	 * 
	 * @access public
	 * @param sw_controller_request_abstract $request 
	 * @return void
	 */
	public function dispatch_loop_startup(sw_controller_request_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->dispatch_loop_startup($request);	
			} catch (Exception $e) {
				if (sw_controller_front::get_instance()->throw_exception()) {
					throw new sw_controller_exception($e->getMessage() . $e->getTraceAsString());	
				} else {
					$this->get_response()->set_exception($e);	
				}
			}	
		}
	}

	// }}}
	// {{{ public function dispatch_loop_shutdown()

	/**
	 * 在循环分发后执行 
	 * 
	 * @access public
	 * @param sw_controller_request_abstract $request 
	 * @return void
	 */
	public function dispatch_loop_shutdown(sw_controller_request_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->dispatch_loop_shutdown($request);	
			} catch (Exception $e) {
				if (sw_controller_front::get_instance()->throw_exception()) {
					throw new sw_controller_exception($e->getMessage() . $e->getTraceAsString());	
				} else {
					$this->get_response()->set_exception($e);	
				}
			}	
		}
	}

	// }}}
	// {{{ public function pre_dispatch()

	/**
	 * 在分发时执行 
	 * 
	 * @access public
	 * @param sw_controller_request_abstract $request 
	 * @return void
	 */
	public function pre_dispatch(sw_controller_request_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->pre_dispatch($request);	
			} catch (Exception $e) {
				if (sw_controller_front::get_instance()->throw_exception()) {
					throw new sw_controller_exception($e->getMessage() . $e->getTraceAsString());	
				} else {
					$this->get_response()->set_exception($e);	
				}
			}	
		}
	}

	// }}}
	// {{{ public function post_dispatch()

	/**
	 * 在分发后执行 
	 * 
	 * @access public
	 * @param sw_controller_request_abstract $request 
	 * @return void
	 */
	public function post_dispatch(sw_controller_request_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->post_dispatch($request);	
			} catch (Exception $e) {
				if (sw_controller_front::get_instance()->throw_exception()) {
					throw new sw_controller_exception($e->getMessage() . $e->getTraceAsString());	
				} else {
					$this->get_response()->set_exception($e);	
				}
			}	
		}
	}

	// }}}
	// }}}
}
