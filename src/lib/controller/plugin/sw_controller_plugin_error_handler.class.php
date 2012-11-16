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
* sw_controller_plugin_error_handler 
+------------------------------------------------------------------------------
* 
* @uses sw_controller_plugin_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_plugin_error_handler extends sw_controller_plugin_abstract
{
	// {{{ const

	/**
	 * 控制器不存在导致的异常  
	 */
	const EXCEPTION_NO_CONTROLLER = 'EXCEPTION_NO_CONTROLLER';

	/**
	 * 方法不存在导致的异常  
	 */
	const EXCEPTION_NO_ACTION = 'EXCEPTION_NO_ACTION';

	/**
	 * 路由解析导致的异常  
	 */
	const EXCEPTION_NO_ROUTE = 'EXCEPTION_NO_ACTION';

	/**
	 * 其他原因导致的异常  
	 */
	const EXCEPTION_NO_OTHER = 'EXCEPTION_NO_ACTION';

	// }}}
	// {{{ members
	
	/**
	 * 异常模块 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__error_module;

	/**
	 * 异常控制器 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__error_controller = 'error';

	/**
	 * 异常的action 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__error_action = 'error';

	/**
	 * 是否循环在异常中 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__is_inside_error_handler_loop = false;

	/**
	 * __exception_count_at_frist_encounter 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__exception_count_at_frist_encounter = 0;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param array $options 
	 * @access public
	 * @return void
	 */
	public function __construct(array $options = array())
	{
		$this->set_error_handler_user($options);
	}

	// }}}
	// {{{ public function set_error_handler_user()

	/**
	 * set_error_handler_user 
	 * 
	 * @param array $options 
	 * @access public
	 * @return sw_controller_plugin_error_handler
	 */
	public function set_error_handler_user(array $options = array())
	{
		if (isset($options['module'])) {
			$this->set_error_handler_module($options['module']);	
		}

		if (isset($options['controller'])) {
			$this->set_error_handler_controller($options['controller']);	
		}

		if (isset($options['action'])) {
			$this->set_error_handler_action($options['action']);	
		}

		return $this;
	}

	// }}}
	// {{{ public function set_error_handler_module()

	/**
	 * 设置错误的模块名 
	 * 
	 * @param string $module 
	 * @access public
	 * @return sw_controller_plugin_error_handler
	 */
	public function set_error_handler_module($module)
	{
		$this->__error_module = (string) $module;
		return $this;
	}

	// }}}
	// {{{ public function get_error_handler_module()

	/**
	 * 获取异常的模型名称 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_error_handler_module()
	{
		if (null === $this->__error_module) {
			$this->__error_module = sw_controller_front::get_instance()->get_dispatcher()->get_default_module();	
		}

		return $this->__error_module;
	}

	// }}}
	// {{{ public function set_error_handler_controller()

	/**
	 * 设置错误信息 
	 * 
	 * @param string $controller 
	 * @access public
	 * @return sw_controller_plugin_error_handler
	 */
	public function set_error_handler_controller($controller)
	{
		$this->__error_controller = (string) $controller;
		return $this;	
	}

	// }}}
	// {{{ public function get_error_handler_controller()

	/**
	 * 获取异常的控制器 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_error_handler_controller()
	{
		return $this->__error_controller;	
	}

	// }}}
	// {{{ public function set_error_handler_action()

	/**
	 * 设置异常的action 
	 * 
	 * @param string $action 
	 * @access public
	 * @return sw_controller_plugin_error_handler
	 */
	public function set_error_handler_action($action)
	{
		$this->__error_action = (string) $action;
		return $this;	
	}

	// }}}
	// {{{ public function get_error_handler_action()

	/**
	 * 获取错误的action· 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_error_handler_action()
	{
		return $this->__error_action;	
	}

	// }}}
	// {{{ public function route_shutdown()

	/**
	 * 路由解析完成后的异常 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function route_shutdown(sw_controller_request_abstract $request)
	{
		$this->_handle_error($request);	
	}

	// }}}
	// {{{ public function pre_dispatch()

	/**
	 * 在分发前收集异常 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function pre_dispatch(sw_controller_request_abstract $request)
	{
		$this->_handle_error($request);	
	}

	// }}}
	// {{{ public function post_dispatch()

	/**
	 * 在分发后收集异常 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function post_dispatch(sw_controller_request_abstract $request)
	{
		$this->_handle_error($request);	
	}

	// }}}
	// {{{ protected function _handle_error()

	protected function _handle_error(sw_controller_request_abstract $request)
	{
		$front_controller = sw_controller_front::get_instance();
		if ($front_controller->get_param('no_error_handler')) {
			return;	
		}

		$response = $this->get_response();

		if ($this->__is_inside_error_handler_loop) {
			$exceptions = $response->get_exception();
			if (count($exceptions) > $this->__exception_count_at_frist_encounter) {
				$front_controller->throw_exceptions(true);
				throw array_pop($exceptions);	
			}	
		}

		if (($response->is_exception()) && (!$this->__is_inside_error_handler_loop)) {
			$this->__is_inside_error_handler_loop = true;
			
			$error            = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
			$exceptions       = $response->get_exception();
			$exception        = $exceptions[0];
			$exception_type   = get_class($exception);
			$error->exception = $exception;

			switch ($exception_type) {
				case 'sw_controller_router_exception' : 
					if (404 == $exception->getCode()) {
						$error->type = self::EXCEPTION_NO_ROUTE;
					} else {
						$error->type = self::EXCEPTION_NO_OTHER;	
					}
					break;
				case 'sw_controller_action_exception':
					if (404 == $exception->getCode()) {
						$error->type = self::EXCEPTION_NO_ACTION;
					} else {
						$error->type = self::EXCEPTION_NO_OTHER;	
					}
					break;
				case 'sw_controller_dispatcher_exception':
					$error->type = self::EXCEPTION_NO_CONTROLLER;
					break;
				default:
					$error->type = self::EXCEPTION_NO_OTHER;
					break;
			}

			$error->request = clone $request;

			$this->__exception_count_at_frist_encounter = count($exceptions);

			$request->set_param('error_handler', $error)
					->set_module_name($this->get_error_handler_module())
					->set_controller_name($this->get_error_handler_controller())
					->set_action_name($this->get_error_handler_action())
					->set_dispatched(false);
			fb::info($error);
		}
	}

	// }}}
	// }}}
}
