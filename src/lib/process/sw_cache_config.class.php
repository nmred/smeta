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
* smeta 缓存配置
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
class sw_cache_config extends sw_abstract
{
    // {{{ consts

	/**
	 * 缓存时间  
	 */
	const EXPIRE_TIME = '86400';

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
	 * loop timeout 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__loop_timeout = 1;

	/**
	 * 定时器 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__event_timer = array();

	/**
	 * 重新获取配置的时间间隔 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__reconfig_interval = 10;

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
        $this->log('Start smond config.', LOG_DEBUG);
        if (!empty($this->__proc_config['reconfig_interval'])) {
            $this->__reconfig_interval = $this->__proc_config['reconfig_interval'];
        }
        $this->__event_base = new \EventBase();
        $this->_create_timer($this->__reconfig_interval);
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
        //$this->log("start loop get config from data center event.", LOG_DEBUG);                
        $is_exit = $this->__event_base->exit($this->__loop_timeout);

        if (false === $is_exit) {
            $log = "set loop exit timeout fail, timeout: {$this->__loop_timeout}.";
            $this->log($log, LOG_WARNING);
            throw new sw_exception($log);    
        }

        $is_loop = $this->__event_base->loop(\EventBase::NO_CACHE_TIME);

        if (false === $is_loop) {
            $log = "loop return fail, timeout: {$this->__loop_timeout}.";
            $this->log($log, LOG_WARNING);
            throw new sw_exception($log);    
        }
    }

    // }}}
    // {{{ public function callback()
        
    /**
     * 定时器回调函数
     * 
     * @access public
     * @return void
     */
    public function callback($interval)
    {
        $this->_reconfig($interval);
        if (!isset($this->__event_timer[$interval])) {
            $log = "this event timer has free, interval: {$interval}.";
            $this->log($log, LOG_DEBUG);
            return;
        }

        $is_settimer = $this->__event_timer[$interval]->setTimer($this->__event_base, array($this, __FUNCTION__), $interval);
        if (false === $is_settimer) {
            $log = "reset event timer faild, interval: {$interval}.";
            $this->log($log, LOG_WARNING);
            throw new sw_exception($log);    
        }

        $this->__event_timer[$interval]->addTimer($interval);
    }

    // }}}
    // {{{ protected function _create_timer()

    /**
     * 创建一个定时器 
     * 
     * @param int $interval 
     * @access protected
     * @return void
     */
    protected function _create_timer($interval)
    {
        $this->__event_timer[$interval] = \Event::timer($this->__event_base, array($this, 'callback'), $interval);       
        if (false === $this->__event_timer[$interval]) {
            $log = "create event timer faild, interval: {$interval}.";
            $this->log($log, LOG_WARNING);
            throw new sw_exception($log);    
        }
        $this->__event_timer[$interval]->add($interval);

		$log = "create a event timer success, interval: {$interval}.";
		$this->log($log, LOG_DEBUG);
    }

    // }}}
	// {{{ protected function _reconfig()

	/**
	 * 重新更新配置 
	 * 
	 * @param int $interval 
	 * @access protected
	 * @return void
	 */
	protected function _reconfig($interval)
	{
		// 缓存监控器数据
		$redis = \swan\redis\sw_redis::singleton();
		$monitor_data = array();
		try {
			$monitor_data = \lib\inner_client\sw_inner_client::call('user', 'dconfig.monitor');
			if (isset($monitor_data['data'])) {
				$monitor_data = $monitor_data['data'];
			} else {
				$monitor_data = array();	
			}
		} catch (\swan\exception\sw_exception $e) {
			$this->log($e->getMessage(), LOG_INFO);
		}

		$old_monitor_ids = $redis->smembers(SWAN_CACHE_MONITOR_IDS);
		$monitor_ids = array();
		foreach ($monitor_data as $key => $value) {	
			$cache_data = json_encode($value);
			$redis->set(SWAN_CACHE_MONITOR_PREFIX . $key, $cache_data, self::EXPIRE_TIME);

			$monitor_ids[] = $key;
			$redis->sadd(SWAN_CACHE_MONITOR_IDS, $key);
			$redis->expire(SWAN_CACHE_MONITOR_IDS, self::EXPIRE_TIME);
		}
		$del_monitor_ids = array_diff($old_monitor_ids, $monitor_ids);
		foreach ($del_monitor_ids as $key) {
			$redis->srem(SWAN_CACHE_MONITOR_IDS, $key);
			$redis->delete(SWAN_CACHE_MONITOR_PREFIX . $key);
		}

		// 缓存监控适配器相关数据
		try {
			$madapter_data = \lib\inner_client\sw_inner_client::call('user', 'dconfig.madapter');
			if (isset($madapter_data['data'])) {
				$madapter_data = $madapter_data['data'];
			} else {
				$madapter_data = array();	
			}
		} catch (\swan\exception\sw_exception $e) {
			$this->log($e->getMessage(), LOG_INFO);
		}

		$old_madapter_ids = $redis->smembers(SWAN_CACHE_MADAPTER_IDS);
		$madapter_ids = array();
		foreach ($madapter_data as $madapter_id => $value) {	
			if (isset($value['archives'])) {
				$cache_data = json_encode($value['archives']);
				$redis->set(SWAN_CACHE_MADAPTER_ARCHIVE_PREFIX . $madapter_id, $cache_data, self::EXPIRE_TIME);
			}

			if (isset($value['metrics'])) {
				$scache_id = SWAN_CACHE_METRIC_IDS . $madapter_id;
				$old_metric_ids = $redis->smembers($scache_id);
				$metric_ids = array();
				foreach ($value['metrics'] as $val) {
					$cache_id = SWAN_CACHE_METRIC_PREFIX . $madapter_id . '_' . $val['metric_id'];
					$cache_data = json_encode($val);
					$redis->set($cache_id, $cache_data, self::EXPIRE_TIME);
					$metric_ids[] = $val['metric_id'];
					$redis->sadd($scache_id, $val['metric_id']);
					$redis->expire($scache_id, self::EXPIRE_TIME);
				}
				$del_metric_ids = array_diff($old_metric_ids, $metric_ids);
				foreach ($del_metric_ids as $metric_id) {
					$redis->srem($scache_id, $metric_id);
					$redis->delete(SWAN_CACHE_METRIC_PREFIX . $madapter_id . '_' . $metric_id);
				}
			}

			if (isset($value['basic'])) {
				$cache_data = json_encode($value['basic']);
				$redis->set(SWAN_CACHE_MADAPTER_PREFIX . $madapter_id, $cache_data, self::EXPIRE_TIME);
				$madapter_ids[] = $madapter_id;
				$redis->sadd(SWAN_CACHE_MADAPTER_IDS, $madapter_id);
				$redis->expire(SWAN_CACHE_MADAPTER_IDS, self::EXPIRE_TIME);
			}
		}

		$del_madapter_ids = array_diff($old_madapter_ids, $madapter_ids);
		foreach ($del_madapter_ids as $madapter_id) {
			$redis->srem(SWAN_CACHE_MONITOR_IDS, $madapter_id);
			$redis->delete(SWAN_CACHE_MADAPTER_PREFIX . $madapter_id);
		}
	}

	// }}}
    // }}} end functions
}
