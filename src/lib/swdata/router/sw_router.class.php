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

namespace lib\swdata\router;

/**
* 路由-默认路由
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_router extends \swan\controller\router\route\sw_abstract
{
	// {{{ members

	/**
	 * 路由表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected static $__roadmap = array();

	// }}}
	// {{{ functions
	// {{{ public static function get_road_map()

	/**
	 * 获取路由表 
	 * 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function get_road_map()
	{
		return self::$__roadmap;	
	}

	// }}}
	// {{{ public static function set_road_map()

	/**
	 * 设置路由表 
	 * 
	 * @param array $road_map 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function set_road_map(array $road_map)
	{
		self::$__roadmap = $road_map;	
	}

	// }}}
	// {{{ public function add_path()

	/**
	 * 添加一条路由 
	 * 
	 * @param string $module 
	 * @param string $path 
	 * @access public
	 * @return swan\controller\router\route\sw_default
	 */
	public function add_path($module, $path)
	{
		$module = (string) $module;
		if (!isset(self::$__roadmap[$module])) {
			self::$__roadmap[$module] = array();	
		}

		self::$__roadmap[$module][$path] = true;
		return $this;
	}

	// }}}
	// {{{ public function del_path()

	/**
	 * 删除一个路由 
	 * 
	 * @param string $module 
	 * @param string $path 
	 * @access public
	 * @return swan\controller\router\route\sw_default
	 */
	public function del_path($module, $path)
	{
		$module = (string) $module;
		if (!isset(self::$__roadmap[$module])) {
			return $this;	
		}

		if (!isset(self::$__roadmap[$module][$path])) {
			return $this;	
		}

		unset(self::$__roadmap[$module][$path]);
		return $this;
	}

	// }}}
	// {{{ public function match()

	/**
	 * match 
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @access public
	 * @return array
	 */
	public function match(\swan\controller\request\sw_abstract $request)
	{
		$module = $request->get_module_name();
		$map = self::get_road_map();
		if (!isset($map[$module])) {
			return false;	
		}	

		$pathinfo = $request->get_pathinfo();
		list($controller, $action) = explode('/', ltrim($pathinfo, '/'));
		if (!isset($map[$module][$controller][$action])) {
			return false;	
		}

		$path = $map[$module][$controller][$action];
		if (false === $path) {
			return false;	
		}

		$action = 'action_' . $action;	

		$res = array(
			$request->get_action_key() => $action,
			$request->get_module_key() => $module,
			$request->get_controller_key() => $controller,
		);

		return $res;
	}

	// }}}
	// }}}
}
