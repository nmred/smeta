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
use \swan\gearman\sw_worker;

/**
+------------------------------------------------------------------------------
* sw_redis_store 
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
class sw_redis_store extends sw_abstract
{
    // {{{ consts
	
	/**
	 * 缓存时间  
	 */
	const EXPIRE_TIME = '86400';

    // }}}
    // {{{ members

	/**
	 * 处理 rrd 数据的 gearman work 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__gmw_redis = null;

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
        $this->log('Start redis store worker.', LOG_DEBUG);

        $array_config = array(
        );

        foreach ($array_config as $config_name) {
            if (!empty($this->__proc_config[$config_name])) {
                $var_name = '__' . $config_name;
                $this->$var_name = $this->__proc_config[$config_name];
            }
        }

		$this->__gmw_rrd = new sw_worker();
		$this->__gmw_rrd->set_log($this->__log);
		$this->__gmw_rrd->add_servers_by_config('gmw_update_redis');
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
			$this->__gmw_rrd->add_function('redis_store', array($this, 'process_receive_data'));
			$this->__gmw_rrd->work_daemon();
		} catch (\swan\exception\sw_exception $e) {
			$this->log($e->getMessage());	
		}
    }

    // }}}
    // {{{ public function process_receive_data()

    /**
     * 处理收到的信息并转发出去
     *
     * @param string $data 收到的信息
     * @param string $client_key 客户端连接的 key
     * @return void
     */
    public function process_receive_data($job)
    {
		$data = $job->workload();
		$this->log("log rrd_store " . $data, LOG_DEBUG);
        $data = rtrim($data);
		$data = json_decode($data, true);
		if (isset($data[0]) && isset($data[1]) && $data[0]) {
			$redis = \swan\redis\sw_redis::singleton();
			$redis->set(SWAN_MONITOR_REDIS_DATA . $data[0], json_encode($data[1]), self::EXPIRE_TIME);
		}
    }

    // }}}
    // }}} end functions
}
