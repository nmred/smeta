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
 
require_once PATH_SWAN_LIB . 'controller/router/route/sw_controller_router_route_interface.class.php';
/**
+------------------------------------------------------------------------------
* sw_controller_router_route_abstract 
+------------------------------------------------------------------------------
* 
* @uses sw_controller_router_route_interface
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_controller_router_route_abstract implements sw_controller_router_route_interface
{	
	// {{{ functions
	// {{{ public function match()
	
	/**
	 * 匹配路由 
	 * 
	 * @param sw_controller_request_http $request 
	 * @access public
	 * @return array
	 */
	public function match(sw_controller_request_http $request) {
		return;		
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
		return;		
	}

	// }}}
	// }}}
} 
