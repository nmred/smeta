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

namespace lib\member\operator\monitor;
use \lib\member\operator\monitor\exception\sw_exception;

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
	 * 监控器对象 
	 * 
	 * @var \lib\member\operator\sw_monitor
	 * @access protected
	 */
	protected $__operator = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * __construct 
	 * 
	 * @param \lib\member\operator\sw_monitor $operator 
	 * @access public
	 * @return void
	 */
	public function __construct(\lib\member\operator\sw_monitor $operator)
	{
		$this->__operator = $operator;	
	}

	// }}}		
	// {{{ public function get_monitor_operator()

	/**
	 * 获取监控器对象 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_monitor_operator()
	{
		return $this->__operator;			
	}

	// }}}
	// {{{ abstract public function add_monitor_handler()

	/**
	 * 添加监控器的处理器 
	 * 
	 * @param \lib\member\property\sw_monitor_basic $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function add_monitor_handler($property = null);

	// }}}
	// {{{ abstract public function mod_monitor_handler()

	/**
	 * 修改监控器处理器 
	 * 
	 * @param \lib\member\property\sw_monitor_basic $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function mod_monitor_handler($property = null);

	// }}}
	// {{{ abstract public function del_monitor_handler()

	/**
	 * 删除监控器的处理器 
	 * 
	 * @param \lib\member\property\sw_monitor_basic $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function del_monitor_handler($property = null);

	// }}}
	// }}}
}
