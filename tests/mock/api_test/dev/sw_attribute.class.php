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
 
namespace mock\api_test\dev;

/**
+------------------------------------------------------------------------------
* 监控适配器 attribute 测试 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_attribute extends \lib\swdata\action\dev\sw_madapter_attr
{
	// {{{ consts
	// }}}
	// {{{ members
	
	/**
	 * __instance 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected static $__instance = null;

	// }}}
	// {{{ functions
	// {{{ public function get_instance()
	
	/**
	 * get_instance 
	 * 
	 * @param mixed $unit_obj 单元测试类
	 * @static
	 * @access public
	 * @return void
	 */
	public static function get_instance($unit_obj)
	{
		if (!isset(self::$__instance)) {
			$request  = $unit_obj->getMockForAbstractClass('swan\controller\request\sw_abstract');
			$response = $unit_obj->getMockForAbstractClass('swan\controller\response\sw_abstract');
			self::$__instance = new self($request, $response);
		}

		return self::$__instance;
	}

	// }}}
	// {{{ public function init()
	
	/**
	 * 初始化 
	 * 
	 * @access public
	 * @return void
	 */
	public function init()
	{
		$this->__request = \mock\api_test\sw_request::get_instance();
	}

	// }}}
	// {{{ public function render_json()
	
	/**
	 * 重写输出函数 
	 * 
	 * @param mixed $data 
	 * @param mixed $code 
	 * @param mixed $msg 
	 * @access public
	 * @return void
	 */
	public function render_json($data, $code, $msg = null) 
	{
		$data = array(
			'code' => $code,
			'msg'  => $msg,
			'data' => $data,
		);		

		return $data;
	}

	// }}}
	// }}}
}
