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
	public function __construct(sw_controller_request_http $request) {
		$this->set_request($request);
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
	// {{{ public static function get_static_map()

	/**
	 * 设置静态路由 
	 * 
	 * @access public
	 */
	public function get_static_map()
	{
		return array (
			// {{{ user
			'user' => array(
				'base'     => true,
				'base.do'  => true,
			),
 			// }}}
			// {{{ admin
			'admin' => array(
				'base'     => true,
				'base.do'  => true,
				'device_manage'     => true,
				'device_manage.do'  => true,
				'device_list'       => true,
				'device_list.do'    => true,
			),
			// }}}
			// {{{ datadesc
			'datadesc' => array(
				'base'    => true,
				'base.do' => true,  
			),		
			// }}}
		);
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
	public function match(sw_controller_request_http $request, sw_controller_dispatcher_abstract $dispatcher)
	{
		$this->set_request($request);
		$this->__module_key     = $this->__request->get_module_key();
		$this->__controller_key = $this->__request->get_controller_key();
		$this->__action_key     = $this->__request->get_action_key();

		$request_query = $this->__request->get_query();
		$request_pathinfo = $this->__request->get_pathinfo();

		$values  = array();
		$params = array(); 

		if (false !== strpos(trim($request_pathinfo, '/'), '/')) {
			return false;
		}

		$module = trim($request_pathinfo, '/');
		if (empty($module)) {
			$module = $dispatcher->get_default_module();	
		}

		if (isset($request_query['q'])) {
			$controller = $request_query['q'];
			unset($request_query['q']);
		} else {	
			$controller = $dispatcher->get_default_controller();
		}

		$params = $request_query;			

		$allow = $this->get_static_map();
		if (isset($allow[$module][$controller])) {
			if ('.do' === substr($controller, -3)) {
				$action = 'action_do';
				$controller = substr($controller, 0, -3);
			} else {
				$action = 'action_default';	
			}

			$values[$this->__module_key]     = $module;
			$values[$this->__controller_key] = $controller;
			$values[$this->__action_key]     = $action;
		} else {
			return false;	
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
