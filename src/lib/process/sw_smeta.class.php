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
use \lib\rrd_store\sw_update;

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
    protected $__listen = '127.0.0.1:8649';

	/**
	 * server 的最大连接数 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__max_connections = 100;

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

	/**
	 * 读超时 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__read_timeout = 1;

	/**
	 * 写超时 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__write_timeout = 1;

	/**
	 * 请求连接池 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__buffer_event = array();

	/**
	 * __bev_key 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__bev_key = 0;

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
        $this->log('Start smeta.', LOG_DEBUG);

        $array_config = array(
            'listen',
			'max_connections',
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
		list($client_ip, $port) = $address;
		$max_conns = $this->__max_connections;
		$current_conns = count($this->__buffer_event);

		if ($current_conns >= $max_conns) {
			$this->log("reach max_connections $max_conns, client ip: $client_ip, disconnect client", LOG_INFO);
			// 断开连接
			$bev = new \EventBufferEvent($this->__event_base, $fd, \EventBufferEvent::OPT_CLOSE_ON_FREE);
			$bev->free();
			unset($bev);
			return;	
		}

		$current_conns ++;

		$this->__bev_key = $this->__bev_key + 1;
		$bev = new \EventBufferEvent($this->__event_base, $fd, \EventBufferEvent::OPT_CLOSE_ON_FREE);
		$bev->setCallbacks(array($this, 'callback_buffer_read'), NULL, array($this, 'callback_buffer_event'), $this->__bev_key);
		$bev->setTimeouts($this->__read_timeout, $this->__write_timeout);

		$is_enable = $bev->enable(\Event::READ);
		if (!$is_enable) {
			$log = "can not enable EventBufferEvent, client: $client_ip:$port, current connections: $current_conns";
			$this->log($log, LOG_INFO);
			return;
		}

		$this->__buffer_event[$this->__bev_key] = array($bev, $client_ip, $port);
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
        $error_no = \EventUtil::getLastSocketErrno();
        $error_msg = \EventUtil::getLastSocketError();

        $log = "listener accept error, $error_msg ($error_no)";
        $this->log($log, LOG_WARNING);
	}

	// }}}
	// {{{ public function callback_buffer_read()

	/**
	 * 请求读回调 
	 * 
	 * @param mixed $bev 
	 * @param mixed $arg 
	 * @access public
	 * @return void
	 */
	public function callback_buffer_read($bev, $arg)
	{
		$buffer_input = $bev->getInput();
		if (!$buffer_input) {
			$log = "can not get buffer input from buffer event";
			$this->log($log, LOG_WARNING);
			$this->_free_bev($arg);
			return;
		}

		while (true) {
			$data = $buffer_input->readLine(\EventBuffer::EOL_CRLF);
			if (is_null($data)) {
				break;
			}

			$this->_process_receive_data($data, $arg);
		}		
	}

	// }}}
    // {{{ public function callback_buffer_event()

    /**
     * buffer event 的回调函数
     * 必须是 public 方法
     *
     * @param object $bev buffer event
     * @param int $event Bit mask of events
     * @return void
     */
    public function callback_buffer_event($bev, $events, $arg)
    {
        if ($events & \EventBufferEvent::TIMEOUT) {
            $this->_free_bev($arg);

            $conns_num = count($this->__buffer_event);
            $log = "callback_buffer_event for timeout, disconnect client, current connections: $conns_num";
            $this->log($log, LOG_INFO);
        }

        if ($events & (\EventBufferEvent::EOF | \EventBufferEvent::ERROR)) {
            $this->_free_bev($arg);

            $conns_num = count($this->__buffer_event);
            $log = "callback_buffer_event for eof or error, disconnect client, current connections: $conns_num";
            $this->log($log, LOG_INFO);
        }
    }

    // }}}
    // {{{ protected function _free_bev()

    /**
     * 释放 bev 资源
     *
     * @return void
     */
    protected function _free_bev($key)
    {
        if (isset($this->__buffer_event[$key])) {
            $this->__buffer_event[$key][0]->free();
            unset($this->__buffer_event[$key]);
        }
    }

    // }}}
    // {{{ protected function _process_receive_data()

    /**
     * 处理收到的信息并转发出去
     *
     * @param string $data 收到的信息
     * @param string $client_key 客户端连接的 key
     * @return void
     */
    protected function _process_receive_data($data, $client_key)
    {
        $client_ip = $this->__buffer_event[$client_key][1];
        $client_port = $this->__buffer_event[$client_key][2];
        $client_name = "$client_ip:$client_port";
        $data = rtrim($data);
		$data = json_decode($data, true);
		if (isset($data[1])) {
			try {
				sw_update::update($data[0], $data[1]);
				$this->log('update data ' . json_encode($data[1]) . ' to ' . $data[0], LOG_DEBUG);
			} catch (\swan\exception\sw_exception $e) {
				$this->log($e->getMessage(), LOG_INFO);	
			}
		}
    }

    // }}}

    // }}} end functions
}
