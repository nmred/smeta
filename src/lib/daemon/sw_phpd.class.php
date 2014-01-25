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
 
namespace lib\daemon;

/**
+------------------------------------------------------------------------------
* PHPD 守护进程 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_phpd extends \swan\daemon\sw_base
{	
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->__log_id = \lib\log\sw_log::LOG_PHPD_ID;
		$this->__proc_name = 'phpd';	
		$this->__log_options = \lib\log\sw_log::get_logsvr_config();
		$this->_init_log('phpd', true);
	}

	// }}}
	// {{{ public function process_cfg()

	/**
	 * 解析配置 
	 * 
	 * @access public
	 * @return void
	 */
	public function process_cfg()
	{
		$cfg = parse_ini_file($this->__ini_file, true);
		if (!$cfg) {
			$this->log("process config faild `{$this->__ini_file}`", LOG_INFO);
			return false;	
		}	

		$process_cfg = array();
		$cfg['parent']['debug'] = isset($cfg['parent']['debug']) ? $cfg['parent']['debug'] : false;
		$process_cfg['parent'] = $cfg['parent'];
		unset($cfg['parent']);

		foreach ($cfg as $proc_name => $config) {
			if (!isset($config['enable']) || !$config['enable']) {
				continue;	
			}

			$proc_num = isset($config['proc_num']) ? $config['proc_num'] : 0;

			$config['max_process'] = max(0, (int)$proc_num);
			$config['debug'] = isset($config['debug']) ? $config['debug'] : false;
			$process_cfg[$proc_name] = $config;
		}

		return $process_cfg;
	}

	// }}}
	// {{{ public function log()

	/**
	 * log 
	 * 
	 * @param mixed $message 
	 * @param mixed $priority 
	 * @access public
	 * @return void
	 */
	public function log($message, $priority)
	{
		$this->__message->message = $message;
		parent::log($this->__message, $priority);	
	}

	// }}}
	// {{{ public function get_implement_class_name()

	/**
	 * 获取接口的类名 
	 * 
	 * @param string $proc_name 
	 * @access public
	 * @return void
	 */
	public function get_implement_class_name($proc_name)
	{
		if (!isset($this->__process_cfg[$proc_name])) {
			return false;
		}

		return "\\lib\\process\\sw_" . $proc_name;
	}

	// }}}
	// }}}
}
