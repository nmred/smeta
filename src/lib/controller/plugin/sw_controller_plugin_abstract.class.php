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
* sw_controller_plugin_abstract 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_controller_plugin_abstract
{
	// {{{ members

	/**
	 * 请求对象 
	 * 
	 * @var sw_controller_request_abstract
	 * @access protected
	 */
	protected $__request;
	
	/**
	 * 响应对象 
	 * 
	 * @var sw_controller_response_abstract
	 * @access protected
	 */
	protected $__response;

	// }}}
	// {{{ functions
	// {{{ public function set_request()

	/**
	 * 设置请求对象 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return sw_controller_plugin_abstract
	 */
	public function set_request(sw_controller_request_abstract $request)
	{
		$this->__request = $request;
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
	 * @return sw_controller_plugin_abstract
	 */
	public function set_response(sw_controller_response_abstract $response)
	{
		$this->__response = $response;
		return $this;	
	}

	// }}}
	// {{{ public function route_startup()

	/**
	 * 在路由解析地址之前执行 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function route_startup(sw_controller_request_abstract $request)
	{
		
	}

	// }}}
	// {{{ public function route_shutdown()

	/**
	 * 在路由解析地址之后执行 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function route_shutdown(sw_controller_request_abstract $request)
	{
		
	}

	// }}}
	// {{{ public function dispatch_loop_startup()

	/**
	 * 在循环分发之前 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function dispatch_loop_startup(sw_controller_request_abstract $request)
	{
		
	}

	// }}}
	// {{{ public function dispatch_loop_shutdown()

	/**
	 * 在循环分发之后 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function dispatch_loop_shutdown(sw_controller_request_abstract $request)
	{
		
	}

	// }}}
	// {{{ public function pre_dispatch()

	/**
	 * 在分发过程之前执行 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function pre_dispatch(sw_controller_request_abstract $request)
	{
		
	}

	// }}}
	// {{{ public function post_dispatch()

	/**
	 * 在分发过程之后执行 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function post_dispatch(sw_controller_request_abstract $request)
	{
		
	}

	// }}}
	// }}}
}
