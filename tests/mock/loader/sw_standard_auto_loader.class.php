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
 
namespace mock\loader;
use lib\loader\sw_standard_auto_loader as sw_mock_standard_auto_loader;

/**
+------------------------------------------------------------------------------
* sw_standard_auto_loader 
+------------------------------------------------------------------------------
* 
* @uses sw_mock_standard_auto_loader
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_standard_auto_loader extends sw_mock_standard_auto_loader
{
	// {{{ functions

	/**
	 * get_namespaces 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_namespaces()
	{
		return $this->__namespaces;	
	}

	// }}}	
}
