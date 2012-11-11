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
 
require_once PATH_SWAN_LIB . 'controller/dispatcher/sw_controller_dispatcher_interface.class.php';

/**
+------------------------------------------------------------------------------
* 分发器抽象
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class  sw_controller_dispatcher_abstract implements
{
	// {{{ members

	/**
	 * 设置默认的action 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_action = 'action_default';

	/**
	 * 设置默认控制 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_controller = 'login';
	
	/**
	 * 设置默认的模块
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_module = 'admin';

	/**
	 * 前端控制器对象 
	 * 
	 * @var sw_controller_front
	 * @access protected
	 */
	protected $__front_controller;

	/**
	 * 参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__invoke_params = array();

	/**
	 * 路径的分隔符 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__path_delimiter = '_';

	/**
	 * 响应对象 
	 * 
	 * @var sw_controller_response_abstract
	 * @access protected
	 */
	protected $__response = null;

	/**
	 * 字符分隔符 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__word_delimiter = array('-', '.');

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造器 
	 * 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public function __construct(array $params = array())
	{
		$this->set_params($params);
	}

	// }}}
	// {{{ public function set_param()

	/**
	 * 设置参数 
	 * 
	 * @param string $name 
	 * @param string $value 
	 * @access public
	 * @return sw_controller_dispatcher_abstract
	 */
	public function set_param($name, $value)
	{
		$name = (string) $name;
		$this->__invoke_params[$name] = $value;
		return $this;	
	}

	// }}}
	// {{{ public function set_params()

	/**
	 * 设置参数 
	 * 
	 * @param array $params 
	 * @access public
	 * @return sw_controller_dispatcher_abstract
	 */
	public function set_params(array $params)
	{
		$this->__invoke_params = array_merge($this->__invoke_params, $params);
		return $this;	
	}

	// }}}
	// {{{ public function get_param()

	/**
	 * 获取某个参数 
	 * 
	 * @param string $name 
	 * @access public
	 * @return mixed
	 */
	public function get_param($name)
	{
		if (isset($this->__invoke_params[$name])) {
			return $this->__invoke_params[$name];	
		}

		return null;
	}

	// }}}
	// {{{ public function get_params()

	/**
	 * 获取所有的参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_params()
	{
		return $this->__invoke_params;	
	}

	// }}}
	// {{{ public function clear_params()

	/**
	 * 清除全部或某个参数 
	 * 
	 * @param null|string|array $name 
	 * @access public
	 * @return sw_controller_dispatcher_abstract
	 */
	public function clear_params($name = null)
	{
		if (null === $name) {
			$this->__invoke_params = array();	
		} elseif (is_string($name) && isset($this->__invoke_params[$name])) {
			unset($this->__invoke_params[$name]);	
		} elseif (is_array($name)) {
			foreach ($name as $key) {
				if (is_string($key) && isset($this->__invoke_params[$key])) {
					unset($this->__invoke_params[$key]);	
				}	
			}	
		}

		return $this;
	}

	// }}}
	// {{{ public function set_response()

	/**
	 * 设置响应对象 
	 * 
	 * @param sw_controller_response_abstract $response 
	 * @access public
	 * @return sw_controller_dispatcher_abstract
	 */
	public function set_response(sw_controller_response_abstract $response = null)
	{
		$this->__response = $response;
		return $this;	
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
		return $this->__response;	
	}

	// }}}
	// {{{ public function get_default_module()

	/**
	 * 获取默认的模型 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_module()
	{
		return $this->__default_module;	
	}

	// }}}
	// {{{ public function get_default_controller_name()

	/**
	 * 获取默认的控制器名称 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_controller_name()
	{
		return $this->__default_controller;	
	}

	// }}}
	// {{{ public function get_default_action()

	/**
	 * 获取默认的方法 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_action()
	{
		return $this->__default_action;
	}

	// }}}
	// }}}	
}
