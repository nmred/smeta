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
abstract class sw_abstract
{
	// {{{ members

	/**
	 * operater 子类会用到该类的对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__opeater = null;

	/**
	 * 子类的命名空间前缀, 此属性必须有实现者来覆盖  
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__namespace = "\lib\member\operater\\";

	/**
	 * 允许获取的子类名称，此属性必须有实现者来覆盖 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__operater_types = array();

	// }}}		
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		
	}

	// }}}
	// {{{ public function get_operater()

	/**
	 * 获取子类的对象 
	 * 
	 * @param string $type 
	 * @access public
	 * @return void
	 */
	public function get_operater($type)
	{
		if (!array_key_exists($type, $this->__operater_types)) {
			throw new sw_exception("Invalid operator type `$type`");	
		}		

		$class_name = $this->__namespace . "sw_$type";

		return new $class_name($this);
	}

	// }}}
	// {{{ public function add_operator_types()

	/**
	 * 添加成员对象 
	 * 
	 * @param string $operater_type 
	 * @access public
	 * @return void
	 */
	public function add_operator_types($operater_type)
	{
		$operater_type = (string) $operater_type;
		$this->__operater_types[$operater_type] = true;	
	}

	// }}}
	// {{{ public function del_operator_types()

	/**
	 * 删除成员对象 
	 * 
	 * @param string $operater_type 
	 * @access public
	 * @return void
	 */
	public function del_operator_types($operater_type)
	{
		$operater_type = (string) $operater_type;
		if (isset($this->__operater_types[$operater_type])) {
			unset($this->__operater_types[$operater_type]);	
		}
	}

	// }}}
	// }}}
}
