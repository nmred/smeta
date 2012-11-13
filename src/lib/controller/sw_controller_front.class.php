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

	// }}}
	// {{{ functions
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
	// }}} function end
}
