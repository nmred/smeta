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
 
/**
+------------------------------------------------------------------------------
* sw_controller_action_helper_abstract 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_controller_action_helper_abstract
{
	// {{{ consts

	/**
	 *  
	 * 所有的helper的类名的前缀
	 *  
	 */
	const PREFIX_HELPER_CLASS = 'sw_controller_action_helper_';
	// }}}
	// {{{ members

	/**
	 * 控制器对象 
	 * 
	 * @var sw_controller_action
	 * @access protected
	 */
	protected $__action_controller = null;

	/**
	 * 前端控制器对象 
	 * 
	 * @var sw_controller_front
	 * @access protected
	 */
	protected $__front_controller = null;

	// }}}
	// {{{ functions
	// {{{ public function set_action_controller()

	/**
	 * 设置控制器 
	 * 
	 * @param sw_controller_action $action_controller 
	 * @access public
	 * @return sw_controller_action_helper_abstract
	 */
	public function set_action_controller(sw_controller_action $action_controller = null)
	{
		$this->__action_controller = $action_controller;
		return $this;
	}

	// }}}
	// {{{ public function get_action_controller()
	
	/**
	 * 获取控制器对象 
	 * 
	 * @access public
	 * @return sw_controller_action
	 */
	public function get_action_controller()
	{
		return $this->__action_controller;	
	}

	// }}}
	// {{{ public function get_front_controller()
	
	/**
	 * 获取前端控制器对象 
	 * 
	 * @access public
	 * @return sw_controller_front
	 */
	public function get_front_controller()
	{
		return $this->__front_controller;	
	}

	// }}}
	// {{{ public function init()

	/**
	 * 初始化helper 
	 * 
	 * @access public
	 * @return void
	 */
	public function init()
	{
		
	}

	// }}}
	// {{{ public funciton pre_dispatch()

	/**
	 * 在分发动作前执行 
	 * 
	 * @access public
	 * @return void
	 */
	public function pre_dispatch()
	{
		
	}

	// }}}
	//{{{ public function post_dispatch()

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
	// {{{ public function get_request()

	/**
	 * 获取请求对象 
	 * 
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function get_request()
	{
		$controller = $this->get_action_controller();
		if (null === $controller) {
			$controller = $this->get_front_controller();	
		}

		return $controller->get_request();
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
		$controller = $this->get_action_controller();
		if (null === $controller) {
			$controller = $this->get_front_controller();	
		}

		return $controller->get_response();
	}

	// }}}
	// {{{ public function get_name()

	/**
	 * 获取helper的名称 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_name()
	{
		$full_name = get_class($this);
		if (false !== strpos($full_name, self::PREFIX_HELPER_CLASS)) {
			return str_replace(self::PREFIX_HELPER_CLASS, '', $full_name);	
		}	

		return $full_name;
	}

	// }}}
	// }}}
}
