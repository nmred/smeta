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
 
namespace lib\process;

/**
+------------------------------------------------------------------------------
* sw_abstract 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_abstract
{
	// {{{ members

	/**
	 * 日志对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__log;

	/**
	 * 日志的 message 对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__message;

	/**
	 * 进程的配置 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__proc_config = array();

	// }}}
	// {{{ functions
	// {{{ public function run()

	/**
	 * 运行 
	 * 
	 * @access public
	 * @return void
	 */
	public function run()
	{
		$this->_init();
		while (1) {
			$this->_run();
			usleep(100);	
		}	
	}

	// }}}
	// {{{ public function set_log()

	/**
	 * 设置日志对象 
	 * 
	 * @param \lib\log\sw_log $log 
	 * @access public
	 * @return void
	 */
	public function set_log($log)
	{
		$this->__log = $log;	
	}

	// }}}
	// {{{ public function set_message()

	/**
	 * 设置日志的 message 对象 
	 * 
	 * @access public
	 * @return void
	 */
	public function set_message($message)
	{
		$this->__message = $message;
	}

	// }}}
	// {{{ public function set_proc_config()

	/**
	 * 设置进程的配置 
	 * 
	 * @param array $config 
	 * @access public
	 * @return void
	 */
	public function set_proc_config($config)
	{
		$this->__proc_config = $config;	
	}

	// }}}
	// {{{ public function log()

	/**
	 * 日志方法 
	 * 
	 * @param string $message 
	 * @param intger $priority 
	 * @access public
	 * @return void
	 */
	public function log($message, $priority)
	{
		$this->__message->message = $message;
		$this->__log->log($this->__message, $priority);	
	}

	// }}}
	// {{{ abstract protected function _run()

	/**
	 * 运行抽象方法 
	 * 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function _run();

	// }}}
	// {{{ abstract protected function _init()

	/**
	 * 初始化抽象方法 
	 * 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function _init();

	// }}}
	// }}}
}
