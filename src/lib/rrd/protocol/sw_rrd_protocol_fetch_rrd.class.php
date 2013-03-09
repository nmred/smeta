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
require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_abstract.class.php';

/**
+------------------------------------------------------------------------------
* 遍历 rrd 数据库
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_rrd_protocol_fetch_rrd extends sw_rrd_protocol_abstract
{
	// {{{ consts

	const CF_AVERAGE = 0;
	const CF_MAX = 1;
	const CF_MIN = 2;
	const CF_LAST = 3;

	// }}}
	// {{{ members

	/**
	 * project 对象 
	 * 
	 * @var sw_rrd_project
	 * @access protected
	 */
	protected $__project = null;

	/**
	 * start time 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__start_time;

	/**
	 * end time 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__end_time;

	/**
	 * resolution 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__resolution;

	/**
	 * cf 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__cf;

	/**
	 * CF map 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__cf_map = array(
		'AVERAGE',
		'MAX',
		'MIN',
		'LAST'
	);

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造函数
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct(sw_rrd_project $project)
	{
		$this->__project = $project;
		parent::__construct();
	}

	// }}}
	// {{{ public function fetch()

	/**
	 * 遍历 rrd数据库 
	 * 
	 * @access public
	 * @return void
	 */
	public function fetch()
	{
		if (!isset($this->__start_time) || !is_numeric($this->__start_time)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('start time set invalid. ');	
		}

		if (!isset($this->__end_time) || !is_numeric($this->__end_time)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('end time set invalid. ');	
		}

		$string = sprintf('fetch %s %s -s %s -e %s',
					$this->_get_rrd_path(),
					$this->__cf,
					$this->__start_time,
					$this->__end_time
				);

		if (0 < $this->__resolution) {
			$string .= ' -r ' . $this->__resolution;	
		}
		echo $string;
		$return = $this->set_command($string)
			->exec();

		print_r($return);

		/*
		if (0 !== $return['res'] || 'OK' !== substr(trim($return['message']), 0, 2)) {
			require_once PATH_SWAN_LIB . 'rrd/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception("update rra database failed . ");	
		}

		return $return;
		*/
	}

	// }}}
	// {{{ public function set_start_time()

	/**
	 * 设置起始时间
	 * 
	 * @param int $start_time 
	 * @access public
	 * @return void
	 */
	public function set_start_time($start_time)
	{
		if ($start_time) {
			$this->__start_time = $start_time;	
		} else {
			$this->__start_time = 'N';	
		}

		return $this;
	}

	// }}}
	// {{{ public function set_end_time()

	/**
	 * 设置结束时间
	 * 
	 * @param int $end_time 
	 * @access public
	 * @return void
	 */
	public function set_end_time($end_time)
	{
		if ($end_time) {
			$this->__end_time = $end_time;	
		} else {
			$this->__end_time = 'N';	
		}

		return $this;
	}

	// }}}
	// {{{ public function set_resolution()

	/**
	 * 设置 resolution
	 * 
	 * @param int $resolution 
	 * @access public
	 * @return void
	 */
	public function set_resolution($resolution)
	{
		if (is_numeric($resolution)) {
			$this->__resolution = $resolution;	
		} else {
			$this->__resolution = 0;	
		}

		return $this;
	}

	// }}}
	// {{{ public function set_cf()

	/**
	 * 设置 CF
	 * 
	 * @param int $cf 
	 * @access public
	 * @return void
	 */
	public function set_cf($cf = self::CF_AVERAGE)
	{
		if (isset($this->__cf_map[$cf])) {
			$this->__cf = $this->__cf_map[$cf];
		} else {
			$this->__cf = $this->__cf_map[0];	
		}
		return $this;
	}

	// }}}
	// {{{ protected function _get_rrd_path()

	/**
	 * 获取 rrd 数据库路劲 
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _get_rrd_path()
	{
		$project_name = $this->__project->get_project_name();
		$device_name = $this->__project->get_device_name();

		require_once PATH_SWAN_LIB . 'sw_hash_dir.class.php';
		$rrd_name = sprintf('%s_%s.rrd', $device_name, $project_name);
		$hash_dir = sw_hash_dir::get_hash_dir($rrd_name);

		$full_path = PATH_SWAN_RRA . $hash_dir . $rrd_name; 
		
		if (!file_exists($full_path)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('rrd db not exists.');	
		}
		return $full_path;
	}

	// }}}
	// }}}	
}
