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
* sw_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_test extends sw_abstract
{
	// {{{ functions
	// {{{ protected function _init()

	/**
	 * 进程初始化 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _init()
	{
		
	}

	// }}}
	// {{{ protected function _run()

	/**
	 * 进程运行 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _run()
	{
		while (1) {
			$this->log("runing ...... time:" . time(), LOG_DEBUG);	
			sleep(1);
		}
	}

	// }}}
	// }}}
}
