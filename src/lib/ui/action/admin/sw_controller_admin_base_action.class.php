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
 
require_once PATH_SWAN_LIB . 'ui/action/sw_controller_action_web.class.php';

/**
+------------------------------------------------------------------------------
* 管理端base页面
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_admin_base_action extends sw_controller_action_web
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function action_default()

	/**
	 * action_default 
	 * 
	 * @access public
	 * @return void
	 */
	public function action_default()
	{
		$tpl_param['test'] = '测试';
		$tpl_param['user'] = 'admin@test.swan.com';
		$this->render('base.html', $tpl_param);
	}

	// }}}
	// {{{ public function action_do()

	/**
	 * action_do
	 * 
	 * @access public
	 * @return void
	 */
	public function action_do()
	{
		return $this->json_stdout(array('res' => 0, 'data' => 'test'));	
	}

	// }}}
	// }}}
}
