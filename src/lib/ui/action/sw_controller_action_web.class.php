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
 
require_once PATH_SWAN_LIB . 'sw_view.class.php';
require_once PATH_SWAN_LIB . 'controller/sw_controller_action.class.php';

/**
+------------------------------------------------------------------------------
* sw_controller_action_web 
+------------------------------------------------------------------------------
* 
* @uses sw
* @uses _controller_action
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_action_web extends sw_controller_action
{
	// {{{ members
	// }}}
	// {{{ function
	// {{{ public function init_view()
	
	/**
	 * 初始化samrty 
	 * 
	 * @access public
	 * @return void
	 */
	public function init_view()
	{
		if (null !== $this->__view) {
			return $this->__view;	
		}

		$module_name = $this->get_request()->get_module_name();

		$template_dir = PATH_SWAN_TPL . $module_name;
		$compile_dir = PATH_SWAN_COMPILE . $module_name;
		$cache_dir = PATH_SWAN_CACHE . $module_name;
		$this->__view = new sw_view($template_dir, $compile_dir, $cache_dir);
		$staic_file_paths = $this->get_static_path();
		foreach ($this->get_static_path() as $key => $value) {
			$this->__view->assign($key, $value);
		}

		return $this->__view;
	}

	// }}}
	// {{{ public function render()

	/**
	 * render 
	 * 
	 * @param mixed $dispaly_file 
	 * @param array $args 
	 * @access public
	 * @return void
	 */
	public function render($dispaly_file, array $args)
	{
		$view = $this->init_view();
		foreach ($args as $key => $value) {
			$view->assign($key, $value);	
		}
		
		$module_name = $this->get_request()->get_module_name();
		$action_name = $this->get_request()->get_action_name();
		
		ob_start();
		$view->render(PATH_SWAN_TPL . $module_name . '/' . $dispaly_file);
		$this->get_response()->append_body(
			$action_name,
			ob_get_clean()
		);
	}

	// }}}
	// {{{ public function get_static_path()

	/**
	 * 获取全局静态文件的路劲 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_static_path()
	{
		$base_path = $this->get_request()->get_base_path();
		$static_base_path = $base_path . '/static/';
		$tpl_static_public = $static_base_path . 'public/';
		$tpl_static_user   = $static_base_path . 'user/';
		$tpl_static_admin  = $static_base_path . 'admin/';

		$map = array(
			//全局
			'TPL_STATIC_PATH'   => $static_base_path,
			'TPL_PREFIX_URL'    => $base_path,
			'TPL_STATIC_PUBLIC' => $tpl_static_public,
			'TPL_STATIC_USER'   => $tpl_static_user,
			'TPL_STATIC_ADMIN'  => $tpl_static_admin,

			// {{{ public

			'TPL_STATIC_PUBLIC_JS'    => $tpl_static_public . 'js/',
				'TPL_STATIC_JQUERY' => $tpl_static_public . 'js/jquery-1.7.2.min.js',
			'TPL_STATIC_PUBLIC_CSS'   => $tpl_static_public . 'css/',
			'TPL_STATIC_PUBLIC_IMAGE' => $tpl_static_public . 'image/',

			// }}}
			// {{{ user

			'PREFIX_URL_USER'       => $base_path . '/user/',
			'TPL_STATIC_USER_JS'    => $tpl_static_user . 'js/',
			'TPL_STATIC_USER_CSS'   => $tpl_static_user . 'css/',
			'TPL_STATIC_USER_IMAGE' => $tpl_static_user . 'image/',

			// }}}
			// {{{ admin

			'PREFIX_URL_ADMIN'       => $base_path . '/admin/',
			'TPL_STATIC_ADMIN_JS'    => $tpl_static_admin . 'js/',
			'TPL_STATIC_ADMIN_CSS'   => $tpl_static_admin . 'css/',
			'TPL_STATIC_ADMIN_IMAGE' => $tpl_static_admin . 'image/',

			// }}}
		);

		return $map;
	}

	// }}}
	// }}}	
}
