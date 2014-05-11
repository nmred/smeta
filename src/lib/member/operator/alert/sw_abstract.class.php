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

namespace lib\member\operator\alert;
use \lib\member\operator\alert\exception\sw_exception;

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
abstract class sw_abstract extends \lib\member\operator\sw_abstract
{
	// {{{ members

	/**
	 * 告警对象 
	 * 
	 * @var \lib\member\operator\sw_alert
	 * @access protected
	 */
	protected $__operator = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * __construct 
	 * 
	 * @param \lib\member\operator\sw_alert $operator 
	 * @access public
	 * @return void
	 */
	public function __construct(\lib\member\operator\sw_alert $operator)
	{
		$this->__operator = $operator;	
	}

	// }}}		
	// {{{ public function get_alert_operator()

	/**
	 * 获取告警对象 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_alert_operator()
	{
		return $this->__operator;			
	}

	// }}}
	// {{{ abstract public function add_alert_handler()

	/**
	 * 添加告警的处理器 
	 * 
	 * @param \lib\member\property\sw_alert_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function add_alert_handler($property = null);

	// }}}
	// {{{ abstract public function mod_alert_handler()

	/**
	 * 修改告警的处理器 
	 * 
	 * @param \lib\member\property\sw_alert_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function mod_alert_handler($property = null);

	// }}}
	// {{{ abstract public function del_alert_handler()

	/**
	 * 删除告警的处理器 
	 * 
	 * @param \lib\member\property\sw_alert_key $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function del_alert_handler($property = null);

	// }}}
	// }}}
}
