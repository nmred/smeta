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

namespace lib\member\operater\device;
use \lib\member\operater\device\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_abstract 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_abstract extends \lib\member\operater\sw_abstract
{
	// {{{ members

	/**
	 * 监控设备对象 
	 * 
	 * @var \lib\member\operater\sw_device
	 * @access protected
	 */
	protected $__operater = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * __construct 
	 * 
	 * @param \lib\member\operater\sw_device $operater 
	 * @access public
	 * @return void
	 */
	public function __construct(\lib\member\operater\sw_device $operater)
	{
		$this->__operater = $operater;	
	}

	// }}}		
	// {{{ public function get_device_operater()

	/**
	 * 获取监控设备对象 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_device_operater()
	{
		return $this->__operater;			
	}

	// }}}
	// {{{ abstract public function add_device_handler()

	/**
	 * 添加设备的处理器 
	 * 
	 * @param \lib\member\property\sw_device_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function add_device_handler($property = null);

	// }}}
	// {{{ abstract public function mod_device_handler()

	/**
	 * 修改设备的处理器 
	 * 
	 * @param \lib\member\property\sw_device_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function mod_device_handler($property = null);

	// }}}
	// {{{ abstract public function del_device_handler()

	/**
	 * 删除设备的处理器 
	 * 
	 * @param \lib\member\property\sw_device_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function del_device_handler($property = null);

	// }}}
	// }}}
}
