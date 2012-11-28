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
 
/**
+------------------------------------------------------------------------------
* DEBUG 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_plugin_test extends sw_controller_plugin_abstract
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function route_startup()

	/**
	 * 在路由解析地址之前执行 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function route_startup(sw_controller_request_abstract $request)
	{
		
	}

	// }}}
	// {{{ public function route_shutdown()

	/**
	 * 在路由解析地址之后执行 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function route_shutdown(sw_controller_request_abstract $request)
	{
		
	}

	// }}}
	// {{{ public function dispatch_loop_startup()

	/**
	 * 在循环分发之前 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function dispatch_loop_startup(sw_controller_request_abstract $request)
	{
		
	}

	// }}}
	// {{{ public function dispatch_loop_shutdown()

	/**
	 * 在循环分发之后 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function dispatch_loop_shutdown(sw_controller_request_abstract $request)
	{
		
	}

	// }}}
	// {{{ public function pre_dispatch()

	/**
	 * 在分发过程之前执行 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function pre_dispatch(sw_controller_request_abstract $request)
	{
		require_once PATH_SWAN_LIB . 'sw_db.class.php';
		$db = sw_db::singleton();
		$db->get_profiler()->set_enabled(true);	
	}

	// }}}
	// {{{ public function post_dispatch()

	/**
	 * 在分发过程之后执行 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return void
	 */
	public function post_dispatch(sw_controller_request_abstract $request)
	{
		$class_file = 'sw_controller_' . $request->get_module_name() . '_' . $request->get_controller_name() . '_action()';
		
		require_once PATH_SWAN_LIB . 'sw_db.class.php';		
		$db = sw_db::singleton();
		$profile = $db->get_profiler()->get_query_profiles(null, true);
		if (!empty($profile)) {
			$arr_sql = array(array('No', 'Sql', 'Bind', 'Process Time(ms)' ));
			$no = 1;	
			foreach ($profile as $sql_object) {
				$type = $sql_object->get_query_type();
				$sql = $sql_object->get_query();
				if ($type <= 2 || empty($sql)) {
					continue;	
				}

				$time = $sql_object->get_elapsed_secs() * 1000;
				$bind = $sql_object->get_query_params();
				$arr_sql[] = array($no, $sql, var_export($bind, true), $time);
			}

			fb::table($class_file, $arr_sql);
		}

		$db->get_profiler()->set_enabled(false);
	}

	// }}}
	// }}}
}
