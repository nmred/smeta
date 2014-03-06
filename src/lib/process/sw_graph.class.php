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
use \lib\rrd_graph\sw_graph as lib_graph;
use \lib\process\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 定时绘制图片 
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
class sw_graph extends sw_abstract
{
    // {{{ consts
    // }}}
    // {{{ members

    /**
     * 业务进程个数
     *
     * @var integer
     */
    protected $__proc_num = 1;

    /**
     * event 退出 loop 的时间 
     * 
     * @var float
     * @access protected
     */
    protected $__loop_timeout = 60;

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
        $this->log('Start graph.', LOG_DEBUG);

        $array_config = array(
            'proc_num',
        );

        foreach ($array_config as $config_name) {
            if (!empty($this->__proc_config[$config_name])) {
                $var_name = '__' . $config_name;
                $this->$var_name = $this->__proc_config[$config_name];
            }
        }

		$this->__redis = \swan\redis\sw_redis::singleton();
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
		while(1) {
			$graph_info = $this->__redis->lpop(SWAN_QUEUE_GRAPH);
			if (!$graph_info) { // 空队列需要休眠
				usleep(500);	
			}
			$graph_info = json_decode($graph_info, true);
			if (!isset($graph_info[1])) {
				continue;
			}

			list($dm_id, $metric_id) = $graph_info;
			try {
				lib_graph::graph($dm_id, $metric_id, array('time_grid' => lib_graph::T_15_MIN));
				lib_graph::graph($dm_id, $metric_id, array('time_grid' => lib_graph::T_60_MIN));
				lib_graph::graph($dm_id, $metric_id, array('time_grid' => lib_graph::T_1_DAY));
				lib_graph::graph($dm_id, $metric_id, array('time_grid' => lib_graph::T_7_DAY));
				lib_graph::graph($dm_id, $metric_id, array('time_grid' => lib_graph::T_30_DAY));
				lib_graph::graph($dm_id, $metric_id, array('time_grid' => lib_graph::T_365_DAY));
			} catch (\swan\exception\sw_exception $e) {
				$this->log($e->getMessage(), LOG_INFO);
			}
		}
    }

    // }}}
    // }}} end functions
}
