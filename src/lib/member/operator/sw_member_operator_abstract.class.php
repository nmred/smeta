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
* sw_member_operator_abstract 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_member_operator_abstract
{
	// {{{ members
	
	/**
	 * 设备对象，此属性只针对此设备下的成员有效，如 project 
	 * 
	 * @var sw_member_operator_device
	 * @access protected
	 */
	protected $__operator_device;

	/**
	 * 此属性默认是 设备 的前缀，子类必须通过覆盖来修改此属性 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__prefix_property_operator_class_name = 'sw_member_property_operator_device_';

	/**
	 * 保存子类成员属性操作的对象 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__operator_types = array();

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
		$this->__operator_device = $operator;	
	}

	// }}}
	// {{{ public function get_device_operator()

	/**
	 * 获取设备的对象
	 * 
	 * @access public
	 * @return sw_member_operator_device
	 */
	public function get_device_operator()
	{
		return $this->__operator_device;
	}

	// }}}
	// {{{ public function get_operator()

	/**
	 * 获取子类成员类的对象
	 * 
	 * @param st $operator_type 
	 * @access public
	 * @return sw_member_property_operator_abstract
	 */
	public function get_operator($operator_type)
	{
		if (!array_key_exists($operator_type, $this->__operator_types)) {
			require_once PATH_SWAN_LIB . 'member/sw_member_operator_exception.class.php';
			throw new sw_member_operator_exception("Invalid operator type `$operator_type`");	
		}

		$class_name = $this->__prefix_property_operator_class_name . $operator_type;

		if (!class_exists($class_name)) {
			$path = $this->__operator_types[$operator_type];
			if ($path && is_string($path)) {
				require_once $path . "/$class_name.class.php";	
			} else {
				require_once PATH_SWAN_LIB . 'member/operator/' . $class_name . '.class.php';	
			}
		}

		if (!class_exists($class_name)) {
			require_once PATH_SWAN_LIB . 'member/sw_member_operator_exception.class.php';
			throw new sw_member_operator_exception("can not load class `$class_name`");	
		}

		return new $class_name($this);
	}

	// }}}
	// {{{ public function add_operator_types()

	/**
	 * 添加设备的成员对象
	 * 
	 * @access public
	 * @return void
	 */
	public function add_operator_types($operator_type, $path)
	{
		$operator_type = (string)$operator_type;
		$this->__operator_types[$operator_type] = $path;
	}

	// }}}
	// {{{ public function del_operator_types()

	/**
	 * 删除设备的成员对象
	 * 
	 * @access public
	 * @return void
	 */
	public function del_operator_types($operator_type)
	{
		$operator_type = (string)$operator_type;
		unset($this->__operator_types[$operator_type]);
	}

	// }}}
	// }}}
}
