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
	protected static $__cf_types = array(
		1 => 'AVERAGE',
		2 => 'MIN',
		3 => 'MAX',
		4 => 'LAST',
	);

	/**
	 *  rrd dst_type 对应关系 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected static $__dst_types = array(
		1 => 'GAUGE',
		2 => 'COUNTER',
		3 => 'DERIVE',
		4 => 'ABSOLUTE',
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
		$out_file = PATH_SWAN_RRD_GRAPH . $dm_id . '_' . $metric_id . '.png';
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
		
		$metric_name = $metric_info['metric_name'];
		$title       = $dm_info['device_name'] . '-' . $metric_info['metric_name'];
		$options = array(
			'--color' => "SHADEA#DDDDDD",
			'--color' => "SHADEB#808080",
			'--color' => "FRAME#006600",
			'--color' => "FONT#006699",
			'--color' => "ARROW#FF0000",
			'--color' => "AXIS#000000",
			'--color' => "BACK#FFFFFF",	
			'--x-grid' => "MINUTE:12:HOUR:1:HOUR:1:0:%H",
			"-X 1 ",
			"-t $title",
			"-v GB",
			"-s " . (time() - 7200),
			"-e " . time(),
			"DEF:value1=$file_name:$metric_name:AVERAGE",
			"COMMENT: \\n",
			"COMMENT: \\n",
			"AREA:value1#00ff00:已使用 ",
			"GPRINT:value1:LAST:当前\:%.0lf",
			"GPRINT:value1:AVERAGE:平均\:%.0lf ",
			"GPRINT:value1:MAX:最大\:%.0lf",
			"GPRINT:value1:MIN:最小\:%.0lf",
			"COMMENT: \\n",
			"COMMENT: \\t\\t\\t\\t\\t\\t\\t最后更新 \:" . date('Y-m-d H\\\:m', time()) . "\\n",
			"COMMENT: \\t\\t\\t\\t\\t\\t\\tSWAN 监控数据中心\\n",
		);
	
		return $options;	
	}

	// }}}
	// }}}
}
