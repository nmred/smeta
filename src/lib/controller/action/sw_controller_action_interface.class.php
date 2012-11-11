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
* sw_controller_action_interface 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
interface sw_controller_action_interface
{
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造器 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @param sw_controller_response_abstract $response 
	 * @param array $invoke_args 
	 * @access public
	 * @return void
	 */
	public function __construct(sw_controller_request_abstract $request, sw_controller_response_abstract $response,array $invoke_args = array());

	// }}}
	// {{{ public function dispatch()
	
	/**
	 * 分发器 
	 * 
	 * @param string $action 
	 * @access public
	 * @return void
	 */
	public function dispatch($action);

	// }}}
	// }}}
} 
