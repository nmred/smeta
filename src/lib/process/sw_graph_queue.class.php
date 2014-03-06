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
* graph 模块队列 
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
class sw_graph_queue extends sw_abstract
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
	 * loop timeout 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__loop_timeout = 10;

	/**
	 * 绘图间隔时间 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__graph_interval = 300;

	/**
	 * 定时器 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__event_timer = array();

	/**
	 * 入队列预处理数据 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__prepare_data = array();

	/**
	 * redis 连接对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__redis = null;

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
        $this->log('Start graph queue.', LOG_DEBUG);
        if (!empty($this->__proc_config['graph_interval'])) {
            $this->__graph_interval = $this->__proc_config['graph_interval'];
        }
        if (!empty($this->__proc_config['reconfig_interval'])) {
            $this->__loop_timeout = $this->__proc_config['reconfig_interval'];
        }
        $this->__event_base = new \EventBase();
		$this->__redis = \swan\redis\sw_redis::singleton();

		// 读取所有的入队列数据
		$this->_get_config_data();
		$this->_create_timer($this->__graph_interval);
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

		$this->_get_config_data();
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
        $this->_insert_queue($interval);
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
	// {{{ protected function _insert_queue()

	/**
	 * 入队列操作 
	 * 
	 * @param int $interval 
	 * @access protected
	 * @return void
	 */
	protected function _insert_queue($interval)
	{
		if (empty($this->__prepare_data)) {
			$this->log('not data need insert graph queue', LOG_DEBUG);
			return;
		}

		foreach ($this->__prepare_data as $key => $metric_ids) {
			foreach ($metric_ids as $metric_id) {
				$data = array($key, $metric_id);
				$data = json_encode($data);
				$this->__redis->rpush(SWAN_QUEUE_GRAPH, $data);
			}
		}
	}

	// }}}
	// {{{ protected function _get_config_data()

	/**
	 * 获取配置数据 
	 * 
	 * @access protected
	 * @return array
	 */
	protected function _get_config_data()
	{
		$this->__prepare_data = array();
		$ids = $this->__redis->smembers(SWAN_CACHE_DM_IDS);
		foreach ($ids as $key) {
			$dm_info = $this->__redis->get('dm_' . $key);
			$dm_info = json_decode($dm_info, true);
			if (!$dm_info) {
				$this->log('get dm info fail. dm_id:' . $key, LOG_DEBUG);
				continue;
			}

			$monitor_id = $dm_info['monitor_id'];
			$metric_ids = $this->__redis->smembers('metric_ids_' . $monitor_id);
			if (!$metric_ids) {
				$this->log('get metric fail. monitor_id:' . $monitor_id . 'dm_id:' . $key, LOG_DEBUG);
				continue;
			}
			$this->__prepare_data[$key] = $metric_ids;
		}
	}

	// }}}
    // }}} end functions
}
