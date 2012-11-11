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
 
require_once PATH_SWAN_LIB . 'controller/action/sw_controller_action_helper_broker_stack.class.php';

/**
+------------------------------------------------------------------------------
* sw_controller_action_helper_broker 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_action_helper_broker
{
	// {{{ const

	/**
	 * helper类名的前缀  
	 */
	const HELPER_PREFIX = 'sw_controller_action_helper_';
	// }}} 
	// {{{ members

	/**
	 * 控制器对象 
	 * 
	 * @var sw_controller_action
	 * @access protected
	 */
	protected $__action_controller;

	/**
	 * 堆栈 
	 * 
	 * @var sw_controller_action_helper_broker_stack
	 * @access protected
	 */
	protected $__stack = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param sw_controller_action $action_controller 
	 * @access public
	 * @return void
	 */
	public function __construct(sw_controller_action $action_controller)
	{
		$this->__action_controller = $action_controller;
		foreach (self::get_stack() as $helper) {
			$helper->set_action_controller($action_controller);
			$helper->init();	
		}
	}

	// }}}
	// {{{ static public function add_helper()

	/**
	 * 添加helper 
	 * 
	 * @param sw_controller_action_helper_abstract $helper 
	 * @static
	 * @access public
	 * @return void
	 */
	static public function add_helper(sw_controller_action_helper_abstract $helper)
	{
		self::get_stack()->push($helper);	
		return;
	}

	// }}}
	// {{{ static public function reset_helpers()

	/**
	 * 重置helper的堆栈 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	static public function reset_helpers()
	{
		self::$__stack = null;	
		return;
	}

	// }}}
	// {{{ static public function get_static_helper()

	/**
	 * 获取helper 
	 * 
	 * @param string $name 
	 * @static
	 * @access public
	 * @return sw_controller_action_helper_abstract
	 */
	static public function get_static_helper($name)
	{
		$name = self::_normalize_helper_name($name);
		$stack = self::get_stack();
		
		if (!isset($stack->{$name})) {
			self::_load_helper($name);	
		}	

		return $stack->{$name};
	}

	// }}}
	// {{{ static public function get_existing_helper()

	/**
	 * 获取存在的helper 
	 * 
	 * @param string $name 
	 * @static
	 * @access public
	 * @return sw_controller_action_helper_abstract
	 */
	static public function get_existing_helper($name)
	{
		$name  = self::_normalize_helper_name($name);
		$stack = self::get_stack();
		
		if (!isset($stack->{$name})) {
			require_once PATH_SWAN_LIB . 'controller/action/sw_controller_action_exception.class.php';
			throw new sw_controller_action_exception('Action helper "' . $name . '" has not been registered with the helper broker');
		} 	

		return $stack->{$name};
	}

	// }}}
	// {{{ static public function get_existing_helpers()

	/**
	 * 获取所有已经注册的helper 
	 * 
	 * @static
	 * @access public
	 * @return array
	 */
	static public function get_existing_helpers()
	{
		return self::get_stack()->get_helpers_by_name();	
	}

	// }}}
	// {{{ static public function has_helper()

	/**
	 * 判断堆栈中是否存在该helper 
	 * 
	 * @param string $name 
	 * @static
	 * @access public
	 * @return boolean
	 */
	static public function has_helper($name)
	{
		$name = self::_normalize_helper_name($name);
		return isset(self::get_stack()->{$name});
	}

	// }}}
	// {{{ static public function remove_helper()

	/**
	 * 删除helper 
	 * 
	 * @param string $name 
	 * @static
	 * @access public
	 * @return boolean
	 */
	static public function remove_helper($name)
	{
		$name  = self::_normalize_helper_name($name);
		$stack = self::get_stack();
		if (isset($stack->{$name})) {
			unset($stack->{$name});	
		}

		return false;
	}

	// }}}
	// {{{ static public function get_stack()

	/**
	 * 获取helper堆栈 
	 * 
	 * @static
	 * @access public
	 * @return sw_controller_action_helper_broker_stack
	 */
	static public function get_stack()
	{
		if (null == self::$__stack) {
			self::$__stack = new sw_controller_action_helper_broker_stack();	
		}

		return self::$__stack;
	}

	// }}}
	// {{{ public function notify_pre_dispatch()

	/**
	 * 执行堆栈中helper中的pre_dispatch()方法 
	 * 
	 * @access public
	 * @return void
	 */
	public function notify_pre_dispatch()
	{
		foreach (self::get_stack() as $helper) {
			$helper->pre_dispatch();	
		}
	}

	// }}}
	// {{{ public function notify_post_dispatch()

	/**
	 * 执行堆栈中helper中的post_dispatch()方法 
	 * 
	 * @access public
	 * @return void
	 */
	public function notify_post_dispatch()
	{
		foreach (self::get_stack() as $helper) {
			$helper->post_dispatch();	
		}
	}

	// }}}
	// {{{ public function get_helper()

	/**
	 * 获取helper 
	 * 
	 * @param string $name 
	 * @access public
	 * @return sw_controller_action_helper_abstract
	 */
	public function get_helper($name)
	{
		$name  = self::_normalize_helper_name($name);
		$stack = self::get_stack();

		if (!isset($stack->{$name})) {
			self::_load_helper($name);
		}

		$helper = $stack->{$name};

		$initialize = false;
		if (null === ($action_controller = $helper->get_action_controller())) {
			$initialize = true;	
		} elseif ($action_controller !== $this->__action_controller) {
			$initialize = true;	
		}

		if ($initialize) {
			$helper->set_action_controller($this->__action_controller)
				   ->init();	
		}

		return $helper;
	}

	// }}}
	// {{{ public function __call()

	/**
	 * __call 
	 * 可以就在控制器中：$this->__helper->xxxx();这样用调用默认的方法
	 * 
	 * @param string $method 
	 * @param mixed $args 
	 * @access public
	 * @return void
	 */
	public function __call($method, $args)
	{
		$helper = $this->get_helper($method);
		if (!method_exists($helper, 'direct')) {
			require_once PATH_SWAN_LIB . 'controller/action/sw_controller_action_exception.class.php';
			throw new sw_controller_action_exception('Helper "' . $method . '" does not support overloading via direct()');
		}	
		return call_user_func_array(array($helper, 'direct'), $args);
	}

	// }}}
	// {{{ public function __get()

	/**
	 * __get 
	 * 
	 * @param string $name 
	 * @access public
	 * @return sw_controller_action_helper_abstract
	 */
	public function __get($name)
	{
		return $this->get_helper($name);
	}

	// }}}
	// {{{ protected static function _normalize_helper_name()

	/**
	 * 格式化helper名称 
	 * 
	 * @param string $name 
	 * @static
	 * @access protected
	 * @return void
	 */
	protected static function _normalize_helper_name($name)
	{
		return $name;		
	}

	// }}}
	// {{{ protected static _load_helper()

	/**
	 * 加载一个helper 
	 * 
	 * @param mixed $name 
	 * @static
	 * @access protected
	 * @return void
	 */
	protected static function _load_helper($name)
	{
		$helper = null;
		$full_class = self::HELPER_PREFIX . $name;
		if (!class_exists($full_class)) {
			require_once PATH_SWAN_LIB . 'controller/action/helper/' . $full_class . '.class.php';
			$helper = new $full_class();
		}

		if (null === $helper) {
			require_once PATH_SWAN_LIB . 'controller/action/sw_controller_action_exception.class.php';
			throw new sw_controller_action_exception('Action Helper by name ' . $name . ' not found');
		}

		if (!($helper instanceof sw_controller_action_helper_abstract)) {
			require_once PATH_SWAN_LIB . 'controller/action/sw_controller_action_exception.class.php';
			throw new sw_controller_action_exception('Helper name ' . $name . ' -> class ' . $full_class . ' is not of type sw_controller_action_helper_abstract');
		}

		self::get_stack()->push($helper);
	}

	// }}}
	// }}}
}
