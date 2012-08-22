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
* sw_controller_router_abstract 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_controller_router_abstract
{
	// {{{ members

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
	protected $__ivoke_params = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
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
	 * 添加或修改一个参数，当实例化一个控制器的时候 
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @access public
	 * @return sw_controller_router_abstract
	 */
	public function set_param($name, $value)
	{
		$name = (string) $name;
		$this->__ivoke_params[$name] = $value;
		return $this;
	}

	// }}}
	// {{{ public function set_params()
	
	/**
	 * 批量设置参数
	 * 
	 * @param array $params
	 * @access public
	 * @return sw_controller_router_abstract
	 */
	public function set_params(array $params)
	{
		$this->__ivoke_params = array_merge($this->__ivoke_params, $params);
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
		if (isset($this->__ivoke_params[$name])) {
			return $this->__ivoke_params[$name];	
		}

		return null;
	}

	// }}}
	// {{{ public function get_params()

	/**
	 * 获取参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_params()
	{
		return $this->__ivoke_params;	
	}

	// }}}
	// {{{ public function clear_params()

	/**
	 * 清除参数 
	 * 
	 * @param string|array|null $name 
	 * @access public
	 * @return sw_controller_router_abstract
	 */
	public function clear_params($name = null)
	{
		if (null === $name) {
			$this->__ivoke_params = array();	
		} elseif (is_string($name) && isset($this->__ivoke_params[$name])) {
			unset($this->__ivoke_params[$name]);	
		} elseif (is_array($name)) {
			foreach ($name as $key) {
				if (is_string($key) && isset($this->__ivoke_params[$key])) {
					unset($this->__ivoke_params[$key]);
				}	
			}	
		}

		return $this;
	}

	// }}}
	// {{{ public function get_front_controller()

	/**
	 * 获取前端控制器 
	 * 
	 * @access public
	 * @return sw_controller_front
	 */
	public function get_front_controller()
	{
		if (null !== $this->__front_controller) {
			return $this->__front_controller;	
		}	

		require_once PATH_SWAN_LIB . 'controller/sw_controller_front.class.php';
		$this->__front_controller = sw_controller_front::get_instance();
		return $this->__front_controller;
	}

	// }}}
	// {{{ public function set_front_controller()

	/**
	 * 设置前端控制器 
	 * 
	 * @param sw_controller_front $controller 
	 * @access public
	 * @return sw_controller_front
	 */
	public function set_front_controller(sw_controller_front $controller)
	{
		$this->__front_controller = $controller;	
		return $this;
	}

	// }}}
	// }}} functions end		
}
