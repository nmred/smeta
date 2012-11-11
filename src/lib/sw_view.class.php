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
 
require_once PATH_SWAN_LIB . 'view/Smarty.class.php';
/**
+------------------------------------------------------------------------------
* sw_view 
+------------------------------------------------------------------------------
* 
* @uses Smarty
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_view extends Smarty
{
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param string $template_dir 
	 * @param string $compile_dir 
	 * @param string $cache_dir 
	 * @access public
	 * @return void
	 */
	public function __construct($template_dir, $compile_dir, $cache_dir)
	{
		$this->template_dir    = $template_dir;
		$this->compile_dir     = $compile_dir;
		$this->caching         = SW_CACHE_START;
		$this->cache_lifetime  = SW_CACHE_TIME;
		$this->cache_dir       = $cache_dir;
		$this->left_delimiter  = SW_LEFT_DELIMITER; 
		$this->right_delimiter = SW_RIGHT_DELIMITER;

		parent::__construct();
	}

	// }}}
	// {{{ public function display()

	/**
	 * display 
	 * 
	 * @param mixed $resource_name 
	 * @param mixed $cache_id 
	 * @param mixed $compile_id 
	 * @access public
	 * @return void
	 */
	public function display($resource_name = null, $cache_id = null, $compile_id = null)
	{
		//禁用display
		return;
	}

	// }}}
	// {{{ public function render()

	/**
	 * render 
	 * 
	 * @param mixed $resource_name 
	 * @param mixed $cache_id 
	 * @param mixed $compile_id 
	 * @access public
	 * @return void
	 */
	public function render($resource_name = null, $cache_id = null, $compile_id = null)
	{
		parent::display($resource_name, $cache_id, $compile_id);
	}

	// }}}
	// }}}	
}
