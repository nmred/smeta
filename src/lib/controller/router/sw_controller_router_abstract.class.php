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
* 路由框架抽象类 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class controller_router_abstract
{
	// {{{ members

	/**
	 * sw_controlloer实例 
	 * 
	 * @var sw_controlloer
	 * @access protected
	 */
	protected $__controller;

	/**
	 * 初始化Action传入的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__invoke_params = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造函数 
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
	 * 增加或修改一个参数 
	 * 
	 * @param string $name 
	 * @param string $value 
	 * @access public
	 * @return sw_controlloer_router
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
	 * 批量修改或添加参数 
	 * 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public function set_params(array $params)
	{
		$this->__invoke_params = array_merge($this->__invoke_params, $params);
	}

	// }}}
	// {{{ public function get_param()

	/**
	 * 获取一个参数 
	 * 
	 * @param string $name 
	 * @access public
	 * @return void
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
	 * 获取多个参数 
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
	 * 默认清除所有的参数，当指定$name为string将清除以其作为key值的参数，当其为array时清除该数组中所有的参数 
	 * 
	 * @param mixed $name 
	 * @access public
	 * @return sw_controlloer_router
	 */
	public function clear_params($name = null)
	{
		if (null === $name) {
			$this->__invoke_params = array();	
		} else if (is_string($name) && isset($this->__invoke_params[$name])) {
			unset($this->__invoke_params[$name]);	
		} else if (is_array($name)) {
			foreach ($name as $key) {
				if (is_string($key) && isset($this->__invoke_params[$key])) {
					unset($this->__invoke_params[$key]);	
				}	
			}	
		}
		
		return $this;
	}

	// }}}
	// {{{ public function get_controller()
	
	/**
	 * 获取controller对象 
	 * 
	 * @access public
	 * @return sw_controller
	 */
	public function get_controller()
	{
		if (null !== $this->__controller) {
			return $this->__controller;	
		}

		require_once PATH_SWAN_LIB . 'sw_controller.class.php';
		$this->__controller = sw_controlloer::get_instance();
		return $this->__controller;
	}

	// }}}
	// {{{ public function set_controller()
	
	/**
	 * 设置sw_controlloer对象实例
	 * 
	 * @param sw_controlloer $controller 
	 * @access public
	 * @return void
	 */
	public function set_controller(sw_controlloer $controller)
	{
		$this->__controller = $controller;
		return $this;
	}

	// }}}
	// }}} end functions
}
