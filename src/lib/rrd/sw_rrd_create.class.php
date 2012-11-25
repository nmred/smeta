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
 
require_once PATH_SWAN_LIB . 'sw_orm.class.php';

/**
+------------------------------------------------------------------------------
* 创建rrd数据库
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_rrd_create
{
	// {{{ const

	/**
	 * rrdtool命令行换行符  
	 */
	const RRD_NL = "\\\n";

	/**
	 *	rrdtool命令bin目录  
	 */
	const RRD_BIN = '/usr/local/swan/opt/rrdtool/bin/';

	// }}}
	// {{{ members

	/**
	 * 数据源计算规则 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__ds_dst = array (
		'GAUGE',
		'COUNTER',
		'DERIVE',
		'ABSOLUTE',
	);

	/**
	 * 归档运算法则 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__rra_cf = array(
		'AVERAGE',
		'MIN',
		'MAX',
		'LAST',
	);

	// }}}
	// {{{ functions
	// {{{ public function __construct()
	
	public function __construct()
	{
				
	}

	// }}}
	// {{{ public function create_rrd()

	/**
	 * 创建rrd数据库 
	 * 
	 * @access public
	 * @return void
	 */
	public function create_rrd($project_id = null)
	{
		$condition = sw_orm::condition_factory('rrd', 'device_project:get_device_project');
		if (null !== $project_id) {
			$condition->set_in('project_id');
			$condition->set_project_id($project_id);
		}
		$operator = sw_orm::operator_factory('rrd', 'device_project');

		$project_result = $operator->get_device_project($condition);	
		
		foreach ($project_result as $value) {
			$string = '';			
			$string = self::RRD_BIN . 'rrdtool create ' . PATH_SWAN_RRA . $value['project_name'] . '.rrd' . self::RRD_NL;
			$string .= ' --start ' . $value['start_time'] . self::RRD_NL;
			$string .= ' --step ' . $value['step'] . ' ' . self::RRD_NL;
			$string .= $this->create_ds($value['project_id']);
			$string .= $this->create_rra($value['project_id']);
			$string = rtrim($string, self::RRD_NL);
			
			exec($string, $return_val, $status);
			if (0 !== $status) {
				require_once PATH_SWAN_LIB . 'rrd/sw_rrd_exception.class.php';
				throw new sw_rrd_exception("create rra database failed . ");	
			}
		}
	}

	// }}}
	// {{{ public function create_ds()
	
	/**
	 * 创建数据源语句 
	 * 
	 * @access public
	 * @return string 
	 */
	public function create_ds($project_id)
	{
		$condition = sw_orm::condition_factory('rrd', 'rrd_ds:get_rrd_ds');
		$condition->set_in('project_id');
		$condition->set_project_id($project_id);
		$operator = sw_orm::operator_factory('rrd', 'rrd_ds');
		
		$ds_result = $operator->get_rrd_ds($condition);	
		$string = '';
		foreach ($ds_result as $value) {
			$string .= 'DS:' . $value['ds_name'] . ':' . $this->__ds_dst[$value['source_type']];
			$string .= ':' . $value['heart_time'] . ':' . $value['min'] . ':' . $value['max'] . self::RRD_NL;
		}

		return $string;
	}

	// }}}
	// {{{ public function create_rra()

	/**
	 * 创建归档策略语句 
	 * 
	 * @param int $project_id 
	 * @access public
	 * @return void
	 */
	public function create_rra($project_id)
	{
		$condition = sw_orm::condition_factory('rrd', 'rrd_rra:get_rrd_rra');
		$condition->set_in('project_id');
		$condition->set_project_id($project_id);
		$operator = sw_orm::operator_factory('rrd', 'rrd_rra');

		$rra_result = $operator->get_rrd_rra($condition);	

		$string = '';
		foreach ($rra_result as $value) {
			$string .= 'RRA:' . $this->__rra_cf[$value['rra_cf']];
			$string .= ':' . $value['rra_xff'] . ':' . $value['steps'] . ':' . $value['rows'] . self::RRD_NL;
		}

		return $string;
			
	}

	// }}}
	// }}}	
}
