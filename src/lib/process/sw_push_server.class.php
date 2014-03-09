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
use \swan\gearman\sw_worker;

/**
+------------------------------------------------------------------------------
* sw_push_server 
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
class sw_push_server extends sw_abstract
{
    // {{{ consts
    // }}}
    // {{{ members

	/**
	 * redis 连接 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__redis = null;

	/**
	 * 处理 push 数据的 gearman work 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__gmw_push = null;

	/**
	 * 允许推送的频道 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__allow_channel = array(
		'monitor' => true,
	);

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
        $this->log('Start push server worker.', LOG_DEBUG);

        $array_config = array(
        );

        foreach ($array_config as $config_name) {
            if (!empty($this->__proc_config[$config_name])) {
                $var_name = '__' . $config_name;
                $this->$var_name = $this->__proc_config[$config_name];
            }
        }
		$this->__redis = \swan\redis\sw_redis::singleton();

		$this->__gmw_push = new sw_worker();
		$this->__gmw_push->set_log($this->__log);
		$this->__gmw_push->add_servers_by_config('gmw_push_server');
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
		try {
			$this->__gmw_push->add_function('push_server', array($this, 'push_server'));
			$this->__gmw_push->work_daemon();
		} catch (\swan\exception\sw_exception $e) {
			$this->log($e->getMessage());	
		}
    }

    // }}}
    // {{{ public function push_server()

    /**
     * 处理push 数据
     *
     * @param string $data 收到的信息
     * @param string $client_key 客户端连接的 key
     * @return void
     */
    public function push_server($job)
    {
		$data = $job->workload();
		$this->log("log push server data: " . $data, LOG_DEBUG);
        $data = rtrim($data);
		$data = json_decode($data, true);
		if (isset($data['channel']) && isset($data['data'][1])) {
			if (array_key_exists($data['channel'], $this->__allow_channel)) {
				try {
					$this->__redis->publish($data['channel'], json_encode($data['data']));
				} catch (\swan\exception\sw_exception $e) {
					$this->log('push data fail:' . $e->getMessage(), LOG_INFO);
				}
			}
		}
    }

    // }}}
    // }}} end functions
}
