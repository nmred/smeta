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
	// }}}
}
