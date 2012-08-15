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
	// }}}
}
