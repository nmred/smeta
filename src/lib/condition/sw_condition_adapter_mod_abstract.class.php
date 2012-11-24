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
 
require_once PATH_SWAN_LIB . 'condition/sw_condition_adapter_abstract.class.php';

/**
+------------------------------------------------------------------------------
* 修改的条件对象 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_condition_adapter_mod_abstract extends sw_condition_adapter_abstract
{
	// {{{ members

	/**
	 * 默认允许的参数数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__default_allow_params = array(
		'where'    => true,
		'property' => true,
	);

	// }}}
}
