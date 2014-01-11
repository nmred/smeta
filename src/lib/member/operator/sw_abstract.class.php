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
 
namespace lib\member\operator;
use \lib\member\operator\exception\sw_exception;

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
abstract class sw_abstract extends \swan\operator\sw_abstract
{
	// {{{ members

	/**
	 * operator 子类会用到该类的对象 
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
	protected $__namespace = "\lib\member\operator\\";

	/**
	 * 允许获取的子类名称，此属性必须有实现者来覆盖 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__operator_types = array();

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
	// {{{ public function get_operator()

	/**
	 * 获取子类的对象 
	 * 
	 * @param string $type 
	 * @access public
	 * @return void
	 */
	public function get_operator($type)
	{
		if (!array_key_exists($type, $this->__operator_types)) {
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
	 * @param string $operator_type 
	 * @access public
	 * @return void
	 */
	public function add_operator_types($operator_type)
	{
		$operator_type = (string) $operator_type;
		$this->__operator_types[$operator_type] = true;	
	}

	// }}}
	// {{{ public function del_operator_types()

	/**
	 * 删除成员对象 
	 * 
	 * @param string $operator_type 
	 * @access public
	 * @return void
	 */
	public function del_operator_types($operator_type)
	{
		$operator_type = (string) $operator_type;
		if (isset($this->__operator_types[$operator_type])) {
			unset($this->__operator_types[$operator_type]);	
		}
	}

	// }}}
	// }}}
}
