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
* 请求对象  ---抽象类
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
+------------------------------------------------------------------------------
*/
abstract class sw_controller_request_abstract
{
	// {{{ members

	/**
	 * 是否已经分发
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $__dispatched = false;

	/**
	 * __module
	 *
	 * @var string
	 * @access protected
	 */
	protected $__module;

	/**
	 * __module_key
	 *
	 * @var string
	 * @access protected
	 */
	protected $__module_key = 'module';

	/**
	 * __controller
	 *
	 * @var string
	 * @access protected
	 */
	protected $__controller;

	/**
	 * __controller_key
	 *
	 * @var string
	 * @access protected
	 */
	protected $__controller_key = 'controller';

	/**
	 * __action
	 *
	 * @var string
	 * @access protected
	 */
	protected $__action;

	/**
	 * __action_key
	 *
	 * @var string
	 * @access protected
	 */
	protected $__action_key = 'action';

	/**
	 * __params
	 *
	 * @var array
	 * @access protected
	 */
	protected $__params = array();

	// }}} end members
	// {{{ functions
	// {{{ public function get_module_name()

	/**
	 * 获取模块的名称
	 *
	 * @access public
	 * @return string
	 */
	public function get_module_name()
	{
		if (null === $this->__module) {
			$this->__module = $this->get_param($this->get_module_key());
		}

		return $this->module;
	}

	// }}}
	// {{{ public function set_module_name()

	/**
	 * 设置模块的名称
	 *
	 * @param string $value
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function set_module_name($value)
	{
		$this->__module = $value;
		return $this;
	}

	// }}}
	// {{{ public function get_controller_name()

	/**
	 * 获取控制器名称 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_controller_name()
	{
		if (null === $this->__controller) {
			$this->__controller = $this->get_param($this->__controller_key);
		}
		
		return $this->__controller;
	}

	// }}}
	// {{{ public function set_controller_name()

	/**
	 * 设置控制器名称 
	 * 
	 * @param string $value 
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function set_controller_name($value)
	{
		$this->__controller = $value;
		return $this;	
	}

	// }}}
	// }}} end functions
}
