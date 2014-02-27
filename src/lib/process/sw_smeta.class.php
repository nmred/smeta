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
use \lib\process\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_smeta 
+------------------------------------------------------------------------------
* 
* @uses sw
* @uses _abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_smeta extends sw_abstract
{
    // {{{ consts
    // }}}
    // {{{ members

    /**
     * Event base 
     * 
     * @var mixed
     * @access protected
     */
    protected $__event_base = null;

    /**
     * 侦听的 ip 地址
     *
     * @var string
     */
    protected $__listen = '127.0.0.1:8694';

    /**
     * event 退出 loop 的时间 
     * 
     * @var float
     * @access protected
     */
    protected $__loop_timeout = 60;

	/**
	 * __listener 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__listener = null;

    // }}} end members
    // {{{ functions
    // {{{ protected function _init()

    /**
     * 初始化
     *
     * @return void
     */
    protected function _init()
    {
        $this->log('Start swdata.', LOG_DEBUG);

        $array_config = array(
            'listen',
        );

        foreach ($array_config as $config_name) {
            if (!empty($this->__proc_config[$config_name])) {
                $var_name = '__' . $config_name;
                $this->$var_name = $this->__proc_config[$config_name];
            }
        }

        $this->__event_base = new \EventBase(); 
		$this->__listener = new \EventListener($this->__event_base, array($this, 'callback_accept'), $this->__event_base, \EventListener::OPT_CLOSE_ON_FREE | \EventListener::OPT_REUSEABLE, -1, $this->__listen);
		$this->__listener->setErrorCallback(array($this, 'callback_accept_error'));

		$this->log('init event ok.', LOG_INFO);
    }

    // }}}
    // {{{ protected function _run()

    /**
     * 单次执行
     *
     * @return void
     */
    protected function _run()
    {
		$is_exit = $this->__event_base->exit($this->__loop_timeout);
		if (false === $is_exit) {
			$log = "set loop exit timeout fail, timeout: {$this->__loop_timeout}.";
			$this->log($log, LOG_INFO);
			exit(1);
		}

		$is_loop = $this->__event_base->loop(\EventBase::NO_CACHE_TIME);
		if (false === $is_loop) {
			$log = "loop return fail, timeout: {$this->__loop_timeout}.";
			$this->log($log, LOG_INFO);
			exit(1);
		}
    }

    // }}}
	// {{{ public function callback_accept()
	
	/**
	 * callback_accept 
	 * 
	 * @param mixed $listener 
	 * @param mixed $fd 
	 * @param mixed $address 
	 * @access public
	 * @return void
	 */
	public function callback_accept($listener, $fd, $address)
	{
	}

	// }}}
	// {{{ public function callback_accept_error()
	
	/**
	 * callback_accept_error 
	 * 
	 * @param mixed $listener 
	 * @access public
	 * @return void
	 */
	public function callback_accept_error($listener)
	{
		
	}

	// }}}
    // }}} end functions
}
