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

namespace lib\member\operator\madapter;
use \lib\member\operator\madapter\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 监控适配器抽象 
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
	 * 监控适配器对象 
	 * 
	 * @var \lib\member\operator\sw_madapter
	 * @access protected
	 */
	protected $__operator = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * __construct 
	 * 
	 * @param \lib\member\operator\sw_madapter $operator 
	 * @access public
	 * @return void
	 */
	public function __construct(\lib\member\operator\sw_madapter $operator)
	{
		$this->__operator = $operator;	
	}

	// }}}		
	// {{{ public function get_madapter_operator()

	/**
	 * 获取监控适配器对象 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_madapter_operator()
	{
		return $this->__operator;			
	}

	// }}}
	// {{{ abstract public function add_madapter_handler()

	/**
	 * 添加监控适配器的处理器 
	 * 
	 * @param \lib\member\property\sw_madapter_basic $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function add_madapter_handler($property = null);

	// }}}
	// {{{ abstract public function mod_madapter_handler()

	/**
	 * 修改监控适配器处理器 
	 * 
	 * @param \lib\member\property\sw_madapter_basic $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function mod_madapter_handler($property = null);

	// }}}
	// {{{ abstract public function del_madapter_handler()

	/**
	 * 删除监控适配器的处理器 
	 * 
	 * @param \lib\member\property\sw_madapter_basic $property 
	 * @abstract
	 * @access public
	 * @return void
	 */
	abstract public function del_madapter_handler($property = null);

	// }}}
	// }}}
}
