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

require PATH_SWAN_LIB . 'validate/sw_validate_abstract.class.php';

/**
+------------------------------------------------------------------------------
* sw_validate_int 
+------------------------------------------------------------------------------
* 
* @uses sw
* @uses _validate_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_validate_int extends sw_validate_abstract
{
	// {{{ const

	const INVALID = 'int_invalid';
	const NOT_INT = 'not_int';

	// }}}
	// {{{ members
	
	/**
	 * 定义模板 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__message_templates = array(
		self::INVALID => "Invalid type given. String or integer expected",
		self::NOT_INT => "'%value%' does not appear to be an integer",
	);

	// }}}	
	// {{{ functions
	// {{{ public function is_valid()

	/**
	 * 通过接口sw_validate_interface定义的接口
	 * 当是INT类型的true 
	 * 
	 * @param mixed $value 
	 * @access public
	 * @return boolean
	 */
	public function is_valid($value)
	{
		if (!is_string($value) && !is_int($value))	{
			$this->_error(self::INVALID);
			return false;	
		}

		$this->_set_value($value);
		if (strval(intval($value)) !== $value) {
			$this->_error(self::NOT_INT);
			return false;	
		}

		return true;
	}

	// }}}
	// }}}
}
