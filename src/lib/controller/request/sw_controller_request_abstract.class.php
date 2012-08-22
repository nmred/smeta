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
	 * 模块
	 *
	 * @var string
	 * @access protected
	 */
	protected $__module;

	/**
	 * 模块的关联key
	 *
	 * @var string
	 * @access protected
	 */
	protected $__module_key = 'module';

	/**
	 * 控制器
	 *
	 * @var string
	 * @access protected
	 */
	protected $__controller;

	/**
	 * 控制器关联key
	 *
	 * @var string
	 * @access protected
	 */
	protected $__controller_key = 'controller';

	/**
	 * 操作
	 *
	 * @var string
	 * @access protected
	 */
	protected $__action;

	/**
	 * 操作的关联key
	 *
	 * @var string
	 * @access protected
	 */
	protected $__action_key = 'action';

	/**
	 * 参数
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

		return $this->__module;
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
	// {{{ public function get_action_name()

	/**
	 * 获取操作的名称 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_action_name()
	{
		if (null === $this->__action) {
			$this->__action = $this->get_param($this->get_action_key());	
		}

		return $this->__action;
	}

	// }}}
	// {{{ public function set_action_name()

	/**
	 * 设置操作的名称 
	 * 
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function set_action_name($value)
	{
		$this->__action = $value;

		if (null === $value) {
			$this->set_param($this->get_action_key(), $value);	
		}

		return $this;
	}

	// }}}
	// {{{ public function get_module_key()

	/**
	 * 获取模块的关联key 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_module_key()
	{
		return $this->__module_key;		
	}

	//　}}}
	// {{{ public function set_module_key()

	/**
	 * 设置模块的关联key 
	 * 
	 * @param string $key
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function set_module_key($key)
	{
		$this->__module_key = (string) $key;
		return $this;
	}

	// }}}
	// {{{ public function get_controller_key()

	/**
	 * 获得控制器的关联key 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_controller_key()
	{
		return $this->__controller_key;
	}

	// }}}
	// {{{ public function set_controller_key()

	/**
	 * 设置控制器的关联key 
	 * 
	 * @param string $key 
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function set_controller_key($key)
	{
		$this->__controller_key = (string) $key;
		return $this;
	}

	// }}}
	// {{{ public function get_action_key()

	/**
	 * 获取操作的关联key 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_action_key()
	{
		return $this->__action_key;		
	}

	// }}}
	// {{{ public function set_action_key()

	/**
	 * 设置操作的关联的key 
	 * 
	 * @param string $key 
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function set_action_key($key)
	{
		$this->__action_key = (string) $key;
		return $this;
	}

	// }}}
	// {{{ public function get_param()
	
	/**
	 * 获取操作参数的值 
	 * 
	 * @param string $key 
	 * @param mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_param($key, $default = null)
	{
		$key = (string) $key;
		if (isset($this->__params[$key])) {
			return $this->__params[$key];	
		}

		return $default;
	}

	// }}}
	// {{{ public function get_user_params()
	
	/**
	 * 获取用户自定义的操作参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_user_params()
	{
		return $this->__params;	
	}

	// }}}
	// {{{ public function get_user_param()

	/**
	 * 获取用户自定义的参数 
	 * 
	 * @param string $key 
	 * @param mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_user_param($key, $default = null)
	{
		if (isset($this->__params[$key])) {
			return $this->__params[$key];	
		}

		return $default;
	}

	// }}}
	// {{{ public function set_param()

	/**
	 * 设置参数 
	 * 
	 * @param string $key 
	 * @param mixed $value 
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function set_param($key, $value)
	{
		$key = (string) $key;

		if ((null === $value) && isset($this->__params[$key])) {
			unset($this->__params[$key]);	
		} elseif (null !== $value) {
			$this->__params[$key] = $value;	
		}

		return $this;
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
		return $this->__params;	
	}

	// }}}
	// {{{ public function set_params()

	/**
	 * 批量设置参数 
	 * 
	 * @param array $array 
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function set_params(array $array)
	{
		$this->__params = $this->__params + (array) $array;
		
		foreach ($array as $key => $value) {
			if (null === $value) {
				unset($this->__params[$key]);	
			}
		}
		
		return $this;	
	}

	// }}}
	// {{{ public function clear_params()

	/**
	 * 将所有的参数置空 
	 * 
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function clear_params()
	{
		$this->__params = array();
		return $this;	
	}

	// }}}
	// {{{ public function set_dispatched()

	/**
	 * 设置是否分发 
	 * 
	 * @param boolean $flag 
	 * @access public
	 * @return sw_controller_request_abstract
	 */
	public function set_dispatched($flag = true)
	{
		$this->__dispatched = $flag ? true : false;
		return $this;
	}
	
	// }}}
	// {{{ public function is_dispatched()

	/**
	 * 获取分发状态 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_dispatched()
	{
		return $this->__dispatched;	
	}

	// }}}
	// }}} end functions
}
