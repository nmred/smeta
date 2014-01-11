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
 
namespace lib\member\operater;
use \lib\member\operater\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_device 
+------------------------------------------------------------------------------
* 
* @uses sw
* @uses _abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_device extends sw_abstract
{
	// {{{ members

	/**
	 * 子类的命名空间 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__namespace = "\lib\member\operater\device\\";

	/**
	 * device key
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__property_key = null;

	/**
	 * 允许创建的子类 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__operater_types = array(
		'key'   => true,
		'basic' => true,__operater_types
	);

	// }}}		
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct(\lib\member\property\sw_device_key $property)
	{
		$this->__property_key = $property;	
	}

	// }}}
	// }}}
}
