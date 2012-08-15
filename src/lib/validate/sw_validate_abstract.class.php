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
 
require_once PATH_SWAN_LIB . 'validate/sw_validate_interface.class.php'; 
/**
+------------------------------------------------------------------------------
* 验证约束规则抽象类
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_validate_abstract implements sw_validate_interface
{
	// {{{ members

	/**
	 * 将要验证的值 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__value;

	/**
	 * 错误提示信息中的变量 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__message_var = array();

	/**
	 * 提示信息模板 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__message_templates = array();

	/**
	 * 错误提示信息 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__messages = array();

	/**
	 * 错误提示信息的code（键值）
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__errors = array();

	/**
	 * 错误提示信息的长度
	 * 
	 * @var int
	 * @access protected
	 */
	protected static $__message_length = -1;

	// }}}	
	// {{{ functions
	// {{{ public function get_messages()

	/**
	 * 返回一个数组，提示信息
	 * 
	 * @access public
	 * @return array
	 */
	public function get_messages()
	{
		return $this->__messages;	
	}

	// }}}
	// {{{ public function is_valid()

	/**
	 * 返回true，子类进行重写
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_valid($value)
	{
		return true;	
	}

	// }}}
	// {{{ public function get_errors()

	/**
	 * 返回一个数组，错误信息的键值
	 * 
	 * @access public
	 * @return array
	 */
	public function get_errors()
	{
		return $this->__errors;	
	}

	// }}}
	// {{{ public function get_message_var()

	/**
	 * 获取提示信息中的变量 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_message_var()
	{
		return array_keys($this->__message_var);	
	}

	// }}}
	// {{{ public function get_message_templates()

	/**
	 * 获取提示信息模板 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_message_templates()
	{
		return $this->__message_templates;	
	}

	// }}}
	// {{{ public function set_message()

	/**
	 * 设置消息 
	 * 
	 * @param string $message_string 
	 * @param string $message_key 
	 * @access public
	 * @return sw_validate_abstract (提供连贯操作接口)
	 * @throws sw_validate_exception
	 */
	public function set_message($message_string, $message_key = null)
	{
		if ($message_key === null) {
			$keys = array_keys($this->__message_templates);
			foreach ($keys as $key) {
				$this->set_message($message_string, $key);	
			}
			return $this;
		}

		if (!isset($this->__message_templates[$message_key])) {
			require_once PATH_SWAN_LIB . 'validate/sw_validate_exception.class.php';
			throw new sw_validate_exception("No message template exists for key '$message_key'");
		}

		$this->__message_templates[$message_key] = $message_string;
		return $this;
	}

	// }}}
	// {{{ public function set_messages()

	/**
	 * 批量设置修改提示信息模板 
	 * 
	 * @param array $messages 
	 * @access public
	 * @return sw_validate_abstract
	 * @throws sw_validate_exception
	 */
	public function set_messages(array $messages)
	{
		foreach ($messages as $key => $message) {
			$this->set_message($message, $key);	
		}
		return $this;
	}

	// }}}
	// {{{ public function __get()

	/**
	 * 魔术方法，获取给定的属性值，当属性为value时获取的是给定校验值 
	 * 
	 * @param string $property 
	 * @access public
	 * @return mixed
	 * @throws sw_validate_exception
	 */
	public function __get($property)
	{
		if ($property == 'value') {
			return $this->__value;	
		}
		if (array_key_exists($property, $this->__message_var)) {
			return $this->{$this->__message_var[$property]};	
		}
		require_once PATH_SWAN_LIB . 'validate/sw_validate_exception.class.php';
		throw new sw_validate_exception("No property exists by the name '$property'");
	}

	// }}}
	// {{{ public static function get_message_length()
	
	/**
	 * 获取错误信息的最大长度 
	 * 
	 * @static
	 * @access public
	 * @return int
	 */
	public static function get_message_length()
	{
		return self::$__message_length;	
	}

	// }}}
	// {{{ public static function set_message_length()

	/**
	 * 设置错误信息的最长长度 
	 * 
	 * @param int $length 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function set_message_length($length = -1)
	{
		self::$__message_length = $length;
	}

	// }}}
	// {{{ protected function _create_message()

	/**
	 * 创建输出错误提示信息 
	 * 
	 * @param string $message_key 
	 * @param mixed $value 
	 * @access protected
	 * @return string
	 */
	protected function _create_message($message_key, $value)
	{
		if (!isset($this->__message_templates[$message_key])) {
			return null;	
		}	

		$message = gettext($this->__message_templates[$message_key]);
		
		if (is_object($value)) {
			if (!in_array('__toString', get_class_methods($value))) {
				$value = get_class($value) . ' object';
			} else {
				$value = $value->__toString();	
			}
		} else {
			$value = (string)$value;	
		}
		
		$message = str_replace('%value%', (string) $value, $message);
		foreach ($this->__message_var as $ident => $property) {
			$message = str_replace("%$ident%", (string) $this->$property, $message);	
		}
		
		$length = self::get_message_length();
		if (($length > -1) && (strlen($message) > $length)) {
			$message = substr($message, 0, (self::get_message_length() -3)) . '...';	
		}

		return $message;
	}

	// }}}
	// {{{ protected function _error()

	/**
	 * 抛出错误信息接口 
	 * 
	 * @param string $message_key 
	 * @param mixed $value 
	 * @access protected
	 * @return void
	 */
	protected function _error($message_key, $value = null)
	{
		if ($message_key === null) {
			$keys = array_keys($this->__message_templates);
			$message_key = current($keys);	
		}
		if ($value === null) {
			$value = $this->__value;	
		}
		$this->__errors[]               = $message_key;
		$this->__messages[$message_key] = $this->_create_message($message_key, $value);
	}

	// }}}
	// {{{ protected function _set_value()

	/**
	 * 设置校验值，已经清空错误信息 
	 * 
	 * @param mixed $value 
	 * @access protected
	 * @return void
	 */
	protected function _set_value($value)
	{
		$this->__value    = $value;
		$this->__messages = array();
		$this->__errors   = array();	
	}

	// }}}
	// }}}
}
