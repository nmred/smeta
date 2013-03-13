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
 
require_once PATH_SWAN_LIB . 'member/operator/sw_member_operator.class.php';

/**
+------------------------------------------------------------------------------
* sw_member_operator_device 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_member_operator_device extends sw_member_operator_abstract
{
	// {{{ members

	/**
	 * 保存成员的关键信息属性对象
	 * 会随着操作对象改变 
	 * 
	 * @var sw_member_property_abstract
	 * @access protected
	 */
	protected $__key_property = null;

	/**
	 * 保存成员的关键信息属性对象
	 * 此对象在调用的时候传递后就不会改变 
	 * 用来还原 self::$__key_property属性的
	 * 
	 * @var sw_member_property_abstract
	 * @access protected
	 */
	protected $__key_property_construct = null;

	/**
	 * 保存设备属性操作的各个类型 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__operator_types = array(
		'key'   => true,
		'basic' => true,
		'snmp'  => true,
	);

	// }}}	
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造函数 
	 * 
	 * @param sw_member_property_abstract|null $key_property
	 * @access public
	 * @return void
	 */
	public function __construct(sw_member_property_abstract $key_property = null)
	{
		if (!isset($key_property)) {
			require_once PATH_SWAN_LIB . 'sw_member.class.php';	
			$key_property = em_member::property_factory('device_key');
		}	

		$this->__key_property = clone $key_property;
		$this->__key_property_construct = clone $key_property;
	}

	// }}}
	// {{{ public function project()

	/**
	 * 生成 project 对象 
	 * 
	 * @access public
	 * @return sw_member_operator_project
	 */
	public function project()
	{
		$class_name = 'sw_member_operator_project';

		if (!class_exists($class_name)) {
			require_once PATH_SWAN_LIB . 'member/operator/' . $class_name . '.class.php';
		}

		if (!class_exists($class_name)) {
			require_once PATH_SWAN_LIB . 'member/operator/sw_member_operator_exception.class.php';
			throw new sw_member_operator_exception('Can not load class' . $class_name);
		}

		return new $class_name($this);
	}

	// }}}
	// {{{ public function restore_key_property()

	/**
	 * 使用 self::$__key_property_construct 还原 self::$__key_property 
	 * 
	 * @access public
	 * @return void
	 */
	public function restore_key_property()
	{
		$this->__key_property = clone $this->__key_property_construct;
	}

	// }}}
	// {{{ public function get_project_key_property()

	/**
	 * 获取项目的关键信息的 property 
	 * 
	 * @access public
	 * @return sw_member_property_abstract
	 * @throws sw_member_operator_exception
	 */
	public function get_project_key_property()
	{
		$property = $this->__key_property;
		if (!$property instanceof sw_member_property_project_key) {
			require_once PATH_SWAN_LIB . 'member/operator/sw_member_operator_exception.class.php';
			throw new sw_member_operator_exception('get project_key_property faild.');	
		}

		return $property;
	}

	// }}}
	// {{{ public function get_device_key_property()

	/**
	 * 获取设备的关键信息的 property 
	 * 
	 * @access public
	 * @return sw_member_property_abstract
	 * @throws sw_member_operator_exception
	 */
	public function get_device_key_property()
	{
		$property = $this->__key_property;
		if (!$property instanceof sw_member_property_device_key) {
			$property = $property->get_device_property();
		}
		if (!$property instanceof sw_member_property_device_key) {
			require_once PATH_SWAN_LIB . 'member/operator/sw_member_operator_exception.class.php';
			throw new sw_member_operator_exception('get device_key_property faild.');	
		}

		return $property;
	}

	// }}}
	// }}}
}
