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
 
require_once PATH_SWAN_LIB . 'operator/sw_operator_abstract.class.php';

/**
+------------------------------------------------------------------------------
* sw_operator_rrd_device 
+------------------------------------------------------------------------------
* 
* @uses sw_operator_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_operator_rrd_device extends sw_operator_abstract
{
	// {{{ functions
	// {{{ public funcction add_device()

	/**
	 * 添加设备 
	 * 
	 * @param sw_property_rrd_device $property 
	 * @access public
	 * @return void
	 */
	public function add_device(sw_property_rrd_device $property)
	{
		$attributes = $property->attributes();
		$require_fields = array('device_name', 'host',);
		$this->_check_require($attributes, $require_fields);

		$this->__db->insert(SWAN_TBN_DEVICE, $attributes);
	}

	// }}}
	// }}}	
} 
