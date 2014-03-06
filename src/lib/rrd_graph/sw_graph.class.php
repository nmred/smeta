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
 
namespace lib\rrd_graph;
use \lib\rrd_store\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 绘制 rrd 图片 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_graph
{
	// {{{ consts
	
	const T_15_MIN  = 900;
	const T_60_MIN  = 3600;
	const T_1_DAY   = 86400;
	const T_7_DAY   = 604800;
	const T_30_DAY  = 2592000;
	const T_365_DAY = 31536000;

	// }}}
	// {{{ members

	/**
	 * redis 连接 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected static $__redis = null;

	/**
	 *  rrd cf_type 对应关系 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected static $__x_grid = array(
		self::T_15_MIN => 'MINUTE:1:MINUTE:3:MINUTE:3:0:%M',
		self::T_60_MIN => 'MINUTE:5:MINUTE:10:MINUTE:10:0:%M',
		self::T_1_DAY  => 'DAY:1:HOUR:2:HOUR:2:0:%M',
		self::T_7_DAY  => 'HOUR:12:DAY:1:DAY:1:0:%M',
		self::T_30_DAY => 'DAY:2:DAY:5:DAY:5:0:%M',
		self::T_365_DAY => 'DAY:15:MONTH:1:MONTH:1:0:%M',
	);

	// }}}
	// {{{ functions

	/**
	 * 绘制图片 
	 * 
	 * @param string $dm_id 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function graph($dm_id, $metric_id, $options = array())
	{
		// 创建 rrd 数据库
		if (!isset(self::$__redis))	{
			self::$__redis = \swan\redis\sw_redis::singleton();	
		}
		
		$dm_info = self::$__redis->get('dm_' . $dm_id);
		if (!$dm_info) {
			throw new sw_exception('graph rrd file faild. reason is get dm info fail.');	
		}
		$dm_info    = json_decode($dm_info, true);
		$monitor_id = $dm_info['monitor_id']; 
		// 获取metric info		
		$metric_info = self::$__redis->get('metric_' . $monitor_id . '_' . $metric_id);
		if (!$metric_info) {
			throw new sw_exception('graph rrd file faild. reason is get monitor metric info fail.');	
		}
		$metric_info = json_decode($metric_info, true);

		$graph_params = self::_get_graph_params($dm_info, $metric_info, $options);
		$time_grid = isset($options['time_grid']) ? $options['time_grid'] : self::T_15_MIN;
		if (!array_key_exists($options['time_grid'], self::$__x_grid)) {
			$time_grid = self::T_15_MIN;
		}
		$out_file = PATH_SWAN_RRD_GRAPH . $time_grid . '/' . $dm_id . '_' . $metric_id . '.png';
		try {
			$graph = new \RRDGraph($out_file);
			$graph->setOptions($graph_params);
			$graph->save();
		} catch (\Exception $e) {
			throw new sw_exception($e);	
		}

		return $out_file;
	}

	// }}}
	// {{{ protected static function _get_graph_params()
	
	/**
	 * 获取绘图的参数 
	 * 
	 * @param array $dm_info 
	 * @param array $metric_info 
	 * @param array $options 
	 * @static
	 * @access protected
	 * @return void
	 */
	protected static function _get_graph_params($dm_info, $metric_info, $options)
	{
		$dm_id = $dm_info['device_id'] . '_' . $dm_info['dm_id'];
		$file_name = PATH_SWAN_RRD . $dm_id . '.rrd';
		if (!file_exists($file_name)) {
			throw new sw_exception('not exists rrd file:' . $file_name);
		}

		$time_grid = isset($options['time_grid']) ? $options['time_grid'] : self::T_15_MIN;
		if (!array_key_exists($options['time_grid'], self::$__x_grid)) {
			$time_grid = self::T_15_MIN;
		}

		$end_time   = time();
		$start_time = $end_time - $time_grid;

		$metric_name = $metric_info['metric_name'];
		$title       = $dm_info['device_name'] . '-' . $metric_info['title'];
		$options = array(
			'--color' => "SHADEA#DDDDDD",
			'--color' => "SHADEB#808080",
			'--color' => "FRAME#006600",
			'--color' => "FONT#006699",
			'--color' => "ARROW#FF0000",
			'--color' => "AXIS#000000",
			'--color' => "BACK#FFFFFF",	
			'--x-grid' => self::$__x_grid[$time_grid],
			"-X 1 ",
			"-t $title",
			"-v GB",
			"-s " . $start_time,
			"-e " . $end_time,
			"DEF:value1=$file_name:$metric_name:AVERAGE",
			"COMMENT: \\n",
			"COMMENT: \\n",
			"AREA:value1#00ff00:已使用 ",
			"GPRINT:value1:LAST:当前\:%.0lf",
			"GPRINT:value1:AVERAGE:平均\:%.0lf ",
			"GPRINT:value1:MAX:最大\:%.0lf",
			"GPRINT:value1:MIN:最小\:%.0lf",
			"COMMENT: \\n",
			"COMMENT: \\n",
			"COMMENT: \\t\\t\\t\\t\\t最后更新 \:" . date('Y-m-d H\\\:i\\\:s', time()) . "\\n",
			"COMMENT: \\t\\t\\t\\t\\tSWAN 监控数据中心\\n",
		);
	
		return $options;	
	}

	// }}}
	// }}}
}
