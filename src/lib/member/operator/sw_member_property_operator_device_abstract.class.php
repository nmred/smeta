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
* sw_member_property_operator_device_abstract 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_member_property_operator_device_abstract extends sw_operator_abstract
{
	// {{{ members
	
	/**
	 * 设备操作对象 
	 * 
	 * @var sw_member_operator_device
	 * @access protected
	 */
	protected $__device_operator = null;

	// }}}	
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造函数 
	 * 
	 * @param sw_member_operator_device $operator 
	 * @access public
	 * @return void
	 */
	public function __construct(sw_member_operator_device $operator)
	{
		$this->__device_operator = $operator;	
		parent::__construct();
	}

	// }}}
	// {{{ public function get_device_operator()
	
	/**
	 * 返回设备操作对象 
	 * 
	 * @access public
	 * @return sw_member_operator_device
	 */
	public function get_device_operator()
	{
		return $this->__device_operator;	
	}

	// }}}
	// {{{ abstract public function add_device_handler()
	
	/**
	 * 添加设备的时候运行的处理器
	 * 
	 * @param mixed $property
	 * @access public
	 * @return void
	 * @throws sw_member_property_operator_exception
	 */
	abstract public function add_device_handler($property = null);

	// }}}
	// {{{ abstract public function del_device_handler()
	
	/**
	 * 删除设备的时候运行的处理器
	 * 
	 * @param mixed $property
	 * @access public
	 * @return void
	 * @throws sw_member_property_operator_exception
	 */
	abstract public function del_device_handler($property = null);

	// }}}
	// {{{ abstract public function mod_device_handler()
	
	/**
	 * 修改设备的时候运行的处理器
	 * 
	 * @param mixed $property
	 * @access public
	 * @return void
	 * @throws sw_member_property_operator_exception
	 */
	abstract public function mod_device_handler($property = null);

	// }}}
	// {{{ abstract public function clear_device_handler()
	
	/**
	 * 清除设备的时候运行的处理器
	 * 
	 * @param mixed $property
	 * @access public
	 * @return void
	 * @throws sw_member_property_operator_exception
	 */
	abstract public function clear_device_handler($property = null);

	// }}}
	// {{{ abstract public function recover_device_handler()
	
	/**
	 * 恢复设备的时候运行的处理器
	 * 
	 * @param mixed $property
	 * @access public
	 * @return void
	 * @throws sw_member_property_operator_exception
	 */
	abstract public function recover_device_handler($property = null);

	// }}}
	// }}}
}
