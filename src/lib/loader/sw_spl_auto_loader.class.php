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
 
namespace lib\loader;

use Traversable;

if (interface_exists('lib\loader\sw_spl_auto_loader')) return;

/**
+------------------------------------------------------------------------------
* sw_spl_auto_loader 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
interface sw_spl_auto_loader
{
	/**
	 * __construct 
	 * 
	 * @param  null|array|Traversable $options 
	 * @access public
	 */
	public function __construct($options = null);

	/**
	 * set_options 
	 * 
	 * @param  array|Traversable $options 
	 * @access public
	 * @return void
	 */
	public function set_options($options);

	/**
	 * autoload 
	 * 
	 * @param  string $class 
	 * @access public
	 * @return mixed
	 *         false [如果加载类失败]
	 *         get_class($class) [如果成功]
	 */
	public function autoload($class);
	
	/**
	 * 注册 auto_loader 的方法通过 spl_autoload 
	 * 
	 * @access public
	 * @return void
	 */
	public function register();
}
