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
	protected $__namespace = "\lib\member\operator\device\\";

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
	protected $__operator_types = array(
		'key'   => true,
		'basic' => true,
		'monitor' => true,
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
	public function __construct($property = null)
	{
		$this->__property_key = $property;	
	}

	// }}}
	// {{{ public function get_device_key_property()

	/**
	 * 获取设备 key 的属性 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_device_key_property()
	{
		return $this->__property_key;	
	}

	// }}}
	// {{{ public function get_device()
	
	/**
	 * 获取设备列表 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_device(\lib\member\condition\get\sw_device $condition)
	{	
		$condition->check_params();
		$params = $condition->params();
		$key_columns   = $condition->columns(SWAN_TBN_DEVICE_KEY);
		$basic_columns = $condition->columns(SWAN_TBN_DEVICE_BASIC);
		if (empty($basic_columns)) { // 没有指定查询关联的字段
			$basic_columns = null;	
		}
		$select = $this->__db->select()
							 ->from(array('k' => SWAN_TBN_DEVICE_KEY), $key_columns)
							 ->join(array('b' => SWAN_TBN_DEVICE_BASIC), "k.device_id = b.device_id", $basic_columns);
		$condition->set_columns(array());
		$condition->where($select, true);
		return $this->_get($select, $condition->params());	
	}

	// }}}
	// }}}
}
