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
* sw_controller_router_route_interface 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
interface sw_controller_router_route_interface
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
	public function match(sw_controller_request_http $request);

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
	public function assemble($data = array(), $reset = false, $encode = false);

	// }}}
	// }}}
}
