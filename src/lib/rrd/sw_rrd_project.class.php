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
*  project操作对象 
+------------------------------------------------------------------------------
* 
* @uses sw_operator_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_rrd_project 
{
	//{{{ members

	/**
	 * project id 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__project_id = null;

	/**
	 * project name 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__project_name = null;

	/**
	 * project device id 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__device_id = null;

	/**
	 * project device name 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__device_name = null;

	/**
	 * project step 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__step = null;

	/**
	 * project start_time 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__start_time = null;

	// }}}
	// {{{ functions
	// {{{ public funcction set_project_id()

	/**
	 * 设置 project id 
	 * 
	 * @param int $project_id 
	 * @access public
	 * @return sw_rrd_project
	 */
	public function set_project_id($project_id)
	{
		if (!isset($project_id) || !is_numeric($project_id)) {
			require_once PATH_SWAN_LIB . 'rrd/sw_rrd_exception.class.php';
			throw new sw_rrd_exception('unknow project id.');	
		}

		$this->__project_id = $project_id;
		return $this;
	}

	// }}}
	// {{{ public funcction set_device_id()

	/**
	 * 设置 device id 
	 * 
	 * @param int $device_id 
	 * @access public
	 * @return sw_rrd_project
	 */
	public function set_device_id($device_id)
	{
		if (!isset($device_id) || !is_numeric($device_id)) {
			require_once PATH_SWAN_LIB . 'rrd/sw_rrd_exception.class.php';
			throw new sw_rrd_exception('unknow device id.');	
		}

		$this->__device_id = $device_id;
		return $this;
	}

	// }}}
	// {{{ public funcction set_project_name()

	/**
	 * 设置 project id 
	 * 
	 * @param int $project_id 
	 * @access public
	 * @return sw_rrd_project
	 */
	public function set_project_name($project_name)
	{
		if (!isset($project_name) || '' === trim($project_name)) {
			require_once PATH_SWAN_LIB . 'rrd/sw_rrd_exception.class.php';
			throw new sw_rrd_exception('unknow project name.');	
		}

		$this->__project_name = $project_name;
		return $this;
	}

	// }}}
	// {{{ public function process_key()

	/**
	 * process_key 
	 * 
	 * @access public
	 * @return void
	 */
	public function process_key()
	{
		// 至少需要设置 project_id 或 device_id、project_name 同时设置
		if (null === $this->__project_id && (null === $this->__device_id || null === $this->__project_name)) {
			require_once PATH_SWAN_LIB . 'rrd/sw_rrd_exception.class.php';
			throw new sw_rrd_exception('project attribute invalid.');	
		}

		// 如果设置了 project_id
		if (null !== $this->__project_id) {
			$condition = sw_orm::condition_factory('rrd', 'device_project:get_device_project');
			$condition->set_eq('project_id');
			$condition->set_project_id($this->__project_id);
			try {
				$project_operator = sw_orm::operator_factory('rrd', 'device_project');
				$projects = $project_operator->get_device_project($condition);	
			} catch (exception $e) {
				require_once PATH_SWAN_LIB . 'rrd/sw_rrd_exception.class.php';
				throw new sw_rrd_exception('project id invalid. ex:' . $e->getMessage());	
			}
			
			if (!isset($projects[0]) || 1 !== count($projects)) {
				require_once PATH_SWAN_LIB . 'rrd/sw_rrd_exception.class.php';
				throw new sw_rrd_exception('project id invalid. ex:' . $e->getMessage());	
			}
			$this->__device_id = $projects[0]['device_id'];
			$this->__project_name = $projects[0]['project_name'];
		} else {
			$condition = sw_orm::condition_factory('rrd', 'device_project:get_device_project');
			$condition->set_eq('device_id');
			$condition->set_eq('project_name');
			$condition->set_project_name($this->__project_name);
			try {
				$project_operator = sw_orm::operator_factory('rrd', 'device_project');
				$projects = $project_operator->get_device_project($condition);	
			} catch (exception $e) {
				require_once PATH_SWAN_LIB . 'rrd/sw_rrd_exception.class.php';
				throw new sw_rrd_exception('project_name and device_id invalid. ex:' . $e->getMessage());	
			}
			
			if (!isset($projects[0]) || 1 !== count($projects)) {
				require_once PATH_SWAN_LIB . 'rrd/sw_rrd_exception.class.php';
				throw new sw_rrd_exception('project_name and device_id invalid. ex:' . $e->getMessage());	
			}
			$this->__project_id = $projects[0]['project_id'];
		}

		$this->__step = $projects[0]['step'];
		$this->__start_time = $projects[0]['start_time'];
		// 获取 device_name
		$condition = sw_orm::condition_factory('rrd', 'device:get_device');
		$condition->set_eq('device_id');
		$condition->set_device_id($this->__device_id);

		try {
			$device_operator = sw_orm::operator_factory('rrd', 'device');
			$devices = $device_operator->get_device($condition);
		} catch (sw_exception $e) {
			require_once PATH_SWAN_LIB . 'rrd/sw_rrd_exception.class.php';
			throw new sw_rrd_exception('get device name faild. ex:' . $e->getMessage());	
		}
		$this->__device_name = $devices[0]['device_name'];
		return $this;
	}

	// }}}
	// {{{ public funcction get_project_id()

	/**
	 * 获取 project id 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_project_id()
	{
		return $this->__project_id;
	}

	// }}}
	// {{{ public funcction get_project_name()

	/**
	 * 获取 project name
	 * 
	 * @access public
	 * @return string
	 */
	public function get_project_name()
	{
		return $this->__project_name;
	}

	// }}}
	// {{{ public funcction get_device_name()

	/**
	 * 获取 device_name
	 * 
	 * @access public
	 * @return string
	 */
	public function get_device_name()
	{
		return $this->__device_name;
	}

	// }}}
	// {{{ public funcction get_device_id()

	/**
	 * 获取 device id 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_device_id()
	{
		return $this->__device_id;
	}

	// }}}
	// {{{ public funcction get_step()

	/**
	 * 获取 step
	 * 
	 * @access public
	 * @return int
	 */
	public function get_step()
	{
		return $this->__step;
	}

	// }}}
	// {{{ public funcction get_start_time()

	/**
	 * 获取 start time
	 * 
	 * @access public
	 * @return int
	 */
	public function get_start_time()
	{
		return $this->__start_time;
	}

	// }}}
	// }}}	
} 
