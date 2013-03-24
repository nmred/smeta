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
 
namespace lib\db\adapter;
use lib\config\sw_config as sw_config;
use lib\db\profiler\sw_profiler as sw_profiler;
use lib\db\adapter\exception\sw_exception as sw_exception;
/**
+------------------------------------------------------------------------------
* sw_abstract 
+------------------------------------------------------------------------------
* 
* @package lib
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_abstract
{
	// {{{ members

	/**
	 * 连接数据库的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__config = array();

	/**
	 *  SQL 语句操作分析器对象 
	 * 
	 * @var lib\db\profiler\sw_profiler
	 * @access protected
	 */
	protected $__profiler = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param array $config 
	 * @access public
	 * @return void
	 */
	public function __construct($config = null)
	{
		$config_default = sw_config::get_config('db');
		if (!isset($config) || is_array($config)) {
			$this->__config = array_merge($config_default, (array) $config);
			$this->_check_required_options($this->__config);	
		} else {
			throw new sw_exception('config param must is array');	
		}	

		// 开启 SQL 分析器
		$this->set_profiler(true);
	}

	// }}}
	// {{{ protected function _check_required_options()

	/**
	 * 创建对象时对参数进行必要的检测
	 * 
	 * @param array $config 
	 * @access protected
	 * @return void
	 * @throws lib\db\adapter\exception\sw_exception
	 */
	protected function _check_required_options(array $config)
	{
		if (!array_key_exists('dbname', $config)) {
			throw new sw_exception('Configuration array must have a key for `dbname` that names the database instance');	
		}

		if (!array_key_exists('username', $config)) {
			throw new sw_exception('Configuration array must have a key for `username` that names the database instance');	
		}

		if (!array_key_exists('password', $config)) {
			throw new sw_exception('Configuration array must have a key for `password` that names the database instance');	
		}
	}

	// }}}
	// {{{ public function set_profiler()

	/**
	 * 设置和创建 SQL 分析器对象 
	 * 
	 * @param boolean $enable 
	 * @access public
	 * @return lib\db\adapter\sw_abstract
	 */
	public function set_profiler($enable = false)
	{
		if (!isset($this->__profiler)) {
			$this->__profiler = new sw_profiler($enable);	
			return $this;
		}

		$this->__profiler->set_enabled($enable);
		return $this;
	}

	// }}}
	// {{{ public function get_profiler()

	/**
	 *  获取 SQL 分析器对象 
	 * 
	 * @access public
	 * @return lib\db\profiler\sw_profiler
	 */
	public function get_profiler()
	{
		return $this->__profiler;	
	}

	// }}}
	// }}}	
}
