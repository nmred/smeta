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
* 验证规则接口类 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
interface sw_validate_interface
{
	// {{{ functions
	// {{{ public function is_valid()

	/**
	 * 返回true，如果验证通过
	 *
	 * 如果验证失败返回false，并且通过get_messages()返回一个错误提示信息的数组
	 * 
	 * @param mixed $value 
	 * @access public
	 * @return boolean
	 * @throws sw_validate_exception
	 */
	public function is_valid($value);

	// }}}
	// {{{ public function get_messages()

	/**
	 * 如果is_valid()返回false时将返回一个错误信息数组，如果没有调用is_valid()或返回true时将返回空数组
	 *
	 * @access public
	 * @return array
	 * @throws sw_validate_exception
	 */
	public function get_messages();

	// }}}
	// }}}
} 
