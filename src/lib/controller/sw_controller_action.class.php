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
 
require_once PATH_SWAN_LIB . 'controller/action/sw_controller_action_interface.class.php';

/**
+------------------------------------------------------------------------------
* sw_controller_action 
+------------------------------------------------------------------------------
* 
* @uses sw_controller_action_interface
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_controller_action implements sw_controller_action_interface
{
	// {{{ members

	/**
	 * 控制器中的action 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__class_methods;

	/**
	 * 参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__invoke_args = array();

	/**
	 * 前端控制器 
	 * 
	 * @var sw_controller_front
	 * @access protected
	 */
	protected $__front_controller;
	
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
	 * 创建出的smarty对象 
	 * 
	 * @var sw_smarty
	 * @access protected
	 */
	protected $__view;

	/**
	 * 动作助手 
	 * 
	 * @var sw_controller_action_helper_broker
	 * @access protected
	 */
	protected $__helper = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct(sw_controller_request_abstract $request, sw_controller_response_abstract $response, array $invoke_args = array())
	{
		$this->set_request($request)
			 ->set_response($response)
			 ->_set_invoke_args($invoke_args);
			 
		$this->__helper = new sw_controller_action_helper_broker($this);
		$this->init();	
	}

	// }}}
	// {{{ public function init()

	/**
	 * 初始化对象 
	 * 
	 * @access public
	 * @return void
	 */
	public function init()
	{
		
	}

	// }}}
	// {{{ public function init_view()

	/**
	 * 初始化 
	 * 
	 * @access public
	 * @return void
	 */
	public function init_view()
	{
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
	// {{{ public function set_request()

	/**
	 * 设置请求对象 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return sw_controller_action
	 */
	public function set_request(sw_controller_request_abstract $request)
	{
		$this->__request = $request;
		return $this;	
	}

	// }}}
	// {{{ public function get_response()

	/**
	 * 获取请求对象 
	 * 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function get_response()
	{
		return $this->__response;	
	}

	// }}}
	// {{{ public function set_response()

	/**
	 * 设置响应对象 
	 * 
	 * @param sw_controller_response_abstract $response 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function set_response(sw_controller_response_abstract $response)
	{
		$this->__response = $response;
		return $this;	
	}

	// }}}
	// {{{ protected function _set_invoke_args()

	/**
	 * 设置参数 
	 * 
	 * @param array $args 
	 * @access protected
	 * @return sw_controller_action
	 */
	protected function _set_invoke_args(array $args = array())
	{
		$this->__invoke_args = $args;
		return $this;	
	}

	// }}}
	// {{{ public function get_invoke_args()

	/**
	 * 获取所有参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_invoke_args()
	{
		return $this->__invoke_args;	
	}

	// }}}
	// {{{ public function get_invoke_arg()

	/**
	 * 获取某个参数 
	 * 
	 * @param string $key 
	 * @access public
	 * @return mixed
	 */
	public function get_invoke_arg($key)
	{
		if (isset($this->__invoke_args[$key])) {
			return $this->__invoke_args[$key];	
		}	

		return null;
	}

	// }}}
	// {{{ public function get_helper()

	/**
	 * 获取helper 
	 * 
	 * @param string $helper_name 
	 * @access public
	 * @return sw_controller_action_helper_abstract
	 */
	public function get_helper($helper_name)
	{
		return $this->__helper->{$helper_name};	
	}

	// }}}
	// {{{ public function get_helper_copy()

	/**
	 * 获取一个克隆的helper 
	 * 
	 * @param string $helper_name 
	 * @access public
	 * @return sw_controller_action_helper_abstract
	 */
	public function get_helper_copy($helper_name)
	{
		return clone $this->__helper->{$helper_name};	
	}

	// }}}
	// {{{ public function set_front_controller()

	/**
	 * 设置前端控制器 
	 * 
	 * @param sw_controller_front $front 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_front_controller(sw_controller_front $front)
	{
		$this->__front_controller = $front;
		return $this;	
	}

	// }}}
	// {{{ public function get_front_controller()

	/**
	 * 获取前端控制器 
	 * 
	 * @access public
	 * @return sw_controller_front
	 */
	public function get_front_controller()
	{
		if (null !== $this->__front_controller) {
			return $this->__front_controller;	
		}	

		if (class_exists('sw_controller_front')) {
			$this->__front_controller = sw_controller_front::get_instance();
			return $this->__front_controller;	
		}

		require_once PATH_SWAN_LIB . 'controller/sw_controller_exception.class.php';
		throw new sw_controller_exception("Front controller class has not been loaded");
	}

	// }}}
	// {{{ public function pre_dispatch()
	
	/**
	 * 在分发前执行 
	 * 
	 * @access public
	 * @return void
	 */
	public function pre_dispatch()
	{
		
	}

	// }}}
	// {{{ public function post_dispatch()
	
	/**
	 * 在分发后执行 
	 * 
	 * @access public
	 * @return void
	 */
	public function post_dispatch()
	{
		
	}

	// }}}
	// {{{ public function __call()
	
	/**
	 * __call 
	 * 
	 * @param mixed $method_name 
	 * @param mixed $args 
	 * @access public
	 * @return void
	 */
	public function __call($method_name, $args)
	{
		require_once PATH_SWAN_LIB . 'controller/action/sw_controller_action_exception.class.php';
		if ('action' == substr($method_name, 0, 6)) {
			throw new sw_controller_action_exception(sprintf('Action "%s" does not exist and was not trapped in __call()', $method_name), 404);
		}

		throw new sw_controller_action_exception(sprintf('Method "%s" does not exist and was not trapped in __call()', $method_name), 500);
	}

	// }}}
	// {{{ public function dispatch()

	/**
	 * 分发action 
	 * 
	 * @param string $action 
	 * @access public
	 * @return void
	 */
	public function dispatch($action)
	{
		$this->__helper->notify_pre_dispatch();	

		$this->pre_dispatch();
		if ($this->get_request()->is_dispatched()) {
			if (null === $this->__class_methods) {
				$this->__class_methods = get_class_methods($this);	
			}	

			if (!($this->get_response()->is_redirect())) {
				if ($this->get_invoke_args('use_case_sensitive_actions') || in_array($action, $this->__class_methods)) {
					if ($this->get_invoke_args('use_case_sensitive_actions')) {
						trigger_error('Using case sensitive actions without word separators is deprecated; please do not rely on this "feature"');	
					}
					$this->$action();
				} else {
					$this->__call($action, array());	
				}
			}
			$this->post_dispatch();
		}

		$this->__helper->notify_post_dispatch();	
	}

	// }}}
	// }}}
}
