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
use \lib\rrd_store\sw_create;

/**
+------------------------------------------------------------------------------
* 更新 rrd 存储文件 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_update
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
	 * 更新 RRD 库 
	 * 
	 * @param string $dm_id 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function update($dm_key, $data, $time)
	{
		// 更新 rrd 数据库
		if (!isset(self::$__redis))	{
			self::$__redis = \swan\redis\sw_redis::singleton();	
		}
		$file_name = sw_create::create($dm_key);

		$dm_info = self::$__redis->get('dm_' . $dm_key);
		if (!$dm_info) {
			throw new sw_exception('create rrd file faild. reason is get dm info fail.');	
		}
		$dm_info    = json_decode($dm_info, true);
		$monitor_id = $dm_info['monitor_id']; 

		$metrics = array();
		foreach ($data as $metric_id => $value) {
			// 获取metric info		
			$metric_info = self::$__redis->get('metric_' . $monitor_id . '_' . $metric_id);
			if (!$metric_info) {
				throw new sw_exception('create rrd file faild. reason is get monitor metric info fail.');	
			}
			$metric_info = json_decode($metric_info, true);
			$metrics[$metric_info['metric_name']] = $value;
		}
		
		try {
			$updater = new \RRDUpdater($file_name);
			$updater->update($metrics, $time);
		} catch (\Exception $e) {
			throw new sw_exception($e);	
		}
	}

	// }}}
}
