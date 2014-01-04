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
use \swan\controller\sw_controller;

/**
+------------------------------------------------------------------------------
* sw_swdata 
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
class sw_swdata extends sw_abstract
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
     * http
     *
     * @var mixed
     * @access protected
     */
    protected $__http = null;

    /**
     * 业务进程个数
     *
     * @var integer
     */
    protected $__proc_num = 1;

    /**
     * 侦听的 ip 地址
     *
     * @var string
     */
    protected $__listen_host = '127.0.0.1';

    /**
     * 最大 body size 1024k
     *
     * @var float
     * @access protected
     */
    protected $__max_body = 1048576;

    /**
     * 最大的 header
     *
     * @var float
     * @access protected
     */
    protected $__max_header = 8192;

    /**
     * 超时时间
     *
     * @var float
     * @access protected
     */
    protected $__timeout = 30;

    /**
     * 侦听的起始端口
     *
     * @var integer
     */
    protected $__listen_port = 8010;

    /**
     * event 退出 loop 的时间 
     * 
     * @var float
     * @access protected
     */
    protected $__loop_timeout = 60;

	/**
	 * 加载的模块 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__modules = array();

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
            'proc_num',
            'listen_host',
            'listen_port',
            'timeout',
            'max_body',
            'max_header',
        );

        foreach ($array_config as $config_name) {
            if (!empty($this->__proc_config[$config_name])) {
                $var_name = '__' . $config_name;
                $this->$var_name = $this->__proc_config[$config_name];
            }
        }

		// 初始化模块
		foreach ($this->__proc_config as $key => $value) {
			if (0 === strpos($key, 'module')) {
				$this->__modules[] = $value;					
			}	
		}
		var_dump($this->__modules);

        $this->__event_base = new \EventBase();
    }

    // }}}
    // {{{ protected function _create_server()

    /**
     * 创建 server
     *
     * @access protected
     * @return void
     */
    protected function _create_server()
    {
        set_error_handler(create_function('$a, $b, $c, $d', 'return;'));
        $port_max = $this->__listen_port + $this->__proc_num;
        for ($port = $this->__listen_port; $port <= $port_max; $port++) {
            $params = array('server_host' => "{$this->__listen_host}:$port");
            $http = new \swan\ehttp\sw_ehttp($this->__event_base, $params);
            $bind = $http->bind();
            $this->log("try to create server and listen {$this->__listen_host}", LOG_DEBUG);
            if ($bind) {
                $this->log("create server and listen {$this->__listen_host}:{$this->__listen_port} success", LOG_DEBUG);
                break;
            } else {
                $this->log("create server and listen {$this->__listen_host} fail", LOG_DEBUG);
            }
        }
        restore_error_handler();

        if (!$bind) {
            $this->log('create server and listen fail', LOG_INFO);
            return false;
        }

        $this->__http = $http;
        return true;
    }

    // }}}
    // {{{ protected function _set_callback()

    /**
     * 设置 server 回调分发
     *
     * @access protected
     * @return void
     */
    protected function _set_callback()
    {
		//use ui\router\sw_router;
		//$controller = sw_controller::get_instance();

		//// 添加控制器命名空间
		//$controller->add_controller_namespace('\ui\action\user',
		//		'user');

		//// 设置路由
		//$road_map = array(
		//		'user' => array('default' => true),
		//		);
		//sw_router::set_road_map($road_map);
		//$router = new sw_router();
		//$controller->get_router()->add_route('user',
		//		$router);

		//// 分发
		//$controller->dispatch();
		foreach ($this->__modules as $path) {
			$path = trim($path, '/');
			$path = '/' . $path . '/';	

			// 回调函数待定
			$this->__http->set_callback($path, array($this, 'test_callback'));
		}
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
        if (!isset($this->__http) && !$this->_create_server()) {
            $log = "create swdata server fail.";
            $this->log($log, __FILE__, __LINE__, LOG_INFO);
            exit(1);
        }

        // 设置 server 的相关参数
        try {
            $this->__http->set_max_headers_size($this->__max_header)
                         ->set_timeout($this->__timeout)
                         ->set_max_body_size($this->__max_body);
            $this->_set_callback();
        } catch (exception $e) {
            $this->log($e->getMessage(), __FILE__, __LINE__, LOG_INFO);
            exit(1);
        }

		while (1) {
			$is_exit = $this->__event_base->exit($this->__loop_timeout);
			if (false === $is_exit) {
				$log = "set loop exit timeout fail, timeout: {$this->__loop_timeout}.";
				$this->log($log, __FILE__, __LINE__, LOG_INFO);
				exit(1);
			}

			$is_loop = $this->__event_base->loop(\EventBase::NO_CACHE_TIME);
			if (false === $is_loop) {
				$log = "loop return fail, timeout: {$this->__loop_timeout}.";
				$this->log($log, __FILE__, __LINE__, LOG_INFO);
				exit(1);
			}
		}
    }

    // }}}
	// {{{ public function test_callback()

	public function test_callback($request)
	{
		$request->sendReply('200', 'OK');
		$log ="debug";
            $this->log($log, LOG_INFO);
	}

	// }}}
    // }}} end functions
}
