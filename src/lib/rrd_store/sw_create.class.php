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
 
namespace lib\rrd_store;
use \lib\rrd_store\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 创建 rrd 存储文件 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_create
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
	 * 创建 RRD 库 
	 * 
	 * @param string $dm_id 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function create($dm_id, $force = false)
	{
		$file_name = PATH_SWAN_RRD . $dm_id . '.rrd';
		if (file_exists($file_name) && !$force) {
			return $file_name;	
		}

		// 创建 rrd 数据库
		if (!isset(self::$__redis))	{
			self::$__redis = \swan\redis\sw_redis::singleton();	
		}
		
		$dm_info = self::$__redis->get('dm_' . $dm_id);
		if (!$dm_info) {
			throw new sw_exception('create rrd file faild. reason is get dm info fail.');	
		}
		$dm_info    = json_decode($dm_info, true);
		$monitor_id = $dm_info['monitor_id']; 
		$monitor_info = self::$__redis->get('monitor_' . $monitor_id);
		if (!$monitor_info) {
			throw new sw_exception('create rrd file faild. reason is get monitor info fail.');	
		}
		$monitor_info    = json_decode($monitor_info, true);
		$rrd_creater = new \RRDCreator($file_name, "now -10d", $monitor_info['steps']);
		
		// 获取 archive
		$archives = self::$__redis->get('archive_' . $monitor_id);
		if (!$archives) {
			throw new sw_exception('create rrd file faild. reason is get monitor archive fail.');	
		}
		$archives = json_decode($archives, true);
		foreach ($archives as $archive) {
			$archive = self::$__cf_types[$archive['cf_type']] . ':' . $archive['xff'] . ':' . $archive['steps'] . ':' . $archive['rows'];
			$rrd_creater->addArchive($archive);	
		}

		// 获取 metrics
		$metric_ids = self::$__redis->smembers('metric_ids_' . $monitor_id);
		if (empty($metric_ids)) {
			throw new sw_exception('not exists metric this dm. dm:' . $dm_id);	
		}

		foreach ($metric_ids as $metric_id) {
			// 获取metric info		
			$metric_info = self::$__redis->get('metric_' . $monitor_id . '_' . $metric_id);
			if (!$metric_info) {
				throw new sw_exception('create rrd file faild. reason is get monitor metric info fail.');	
			}
			$metric_info = json_decode($metric_info, true);
			if (!$metric_info['tmax']) {
				$metric_info['tmax'] = $monitor_info['steps'] * 2;
			}
			$ds_data = $metric_info['metric_name'] . ':' . self::$__dst_types[$metric_info['dst_type']] . ':' . 
					$metric_info['tmax'] . ':' . $metric_info['vmin'] . ':' . $metric_info['vmax'];
			$rrd_creater->addDataSource($ds_data);	
		}

		try {
			$rrd_creater->save();
		} catch (\Exception $e) {
			throw new sw_exception($e);	
		}

		return $file_name;
	}

	// }}}
}
