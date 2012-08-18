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
* sw_db_expr 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_db_expr
{
	// {{{ members

	/**
	 * 存放sql表达式 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__expression;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param string $expression 
	 * @access public
	 * @return void
	 */
	public function __construct($expression)
	{
		$this->__expression = (string) $expression;	
	}

	// }}}
	// {{{ public function __toString()

	/**
	 * __toString 
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		return $this->__expression;	
	}

	// }}}
	// }}} end functions
}
