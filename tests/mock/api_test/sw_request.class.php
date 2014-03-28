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
 
namespace mock\api_test;

/**
+------------------------------------------------------------------------------
* 模拟 request 对象 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_request
{
	// {{{ consts
	// }}}
	// {{{ members
	
	/**
	 * request 单例对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected static $__instance = null;

	/**
	 * post 数据 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__post = array();

	// }}}
	// {{{ functions
	// {{{ private function __construct()
	
	/**
	 * __construct 
	 * 
	 * @access private
	 * @return void
	 */
	private function __construct()
	{
	}

	// }}}
	// {{{ public static function get_instance()
	
	/**
	 * 获取 request 对象 
	 * 
	 * @param array $init_data 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function get_instance($init_data = array())
	{
		if (!isset(self::$__instance)) {
			self::$__instance = new self();
		}
		if (!empty($init_data)) {
			self::$__instance->init_post($init_data);	
		}	

		return self::$__instance;
	}

	// }}}
	// {{{ public function init_post()
	
	/**
	 * 初始化 post 数据 
	 * 
	 * @param array $data 
	 * @access public
	 * @return void
	 */
	public function init_post($data)
	{
		if (is_array($data)) {
			$this->__post = $data;
		}
	}

	// }}}
	// {{{ public function get_post()
	
	/**
	 * 获取 post 数据 
	 * 
	 * @param string $key 
	 * @param mixed $default 
	 * @access public
	 * @return void
	 */
	public function get_post($key, $default = null)
	{
		if (isset($this->__post[$key])) {
			return $this->__post[$key];	
		} else {
			return $default;	
		}
	}

	// }}}
	// }}}
}
