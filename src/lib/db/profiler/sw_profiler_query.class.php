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

namespace lib\db\profiler;

/**
+------------------------------------------------------------------------------
* sw_profiler_query 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_profiler_query
{
	// {{{ members
	
	/**
	 * SQL 语句或注释 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__query = '';

	/**
	 * SQL 语句类型 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__query_type = 0;

	/**
	 * 开始执行的微秒 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__started_microtime = null;

	/**
	 * 执行的结束时间 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__ended_microtime = null;

	/**
	 * 绑定的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bound_params = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param string $query 
	 * @param integer $query_type 
	 * @access public
	 * @return void
	 */
	public function __construct($query, $query_type)
	{
		$this->__query      = $query;
		$this->__query_type = $query_type;	
		$this->start();
	}

	// }}}
	// {{{ public function __clone()

	/**
	 * __clone 
	 * 
	 * @access public
	 * @return void
	 */
	public function __clone()
	{
		$this->__ended_microtime = null;
		$this->__bound_params = array();
		$this->start();	
	}

	// }}}
	// {{{ public function start()
	
	/**
	 * 开始 
	 * 
	 * @access public
	 * @return void
	 */
	public function start()
	{
		$this->__started_microtime = microtime(true);	
	}

	// }}}
	// {{{ public function end()

	/**
	 * 设置执行操作结束时间 
	 * 
	 * @access public
	 * @return void
	 */
	public function end()
	{
		$this->__ended_microtime = microtime(true);	
	}

	// }}}
	// {{{ public function has_ended()

	/**
	 * 判断是否 SQL 语句执行完毕 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function has_ended()
	{
		return $this->__ended_microtime !== null;	
	}

	// }}}
	// {{{ public function get_query()

	/**
	 * 获取操作语句 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_query()
	{
		return $this->__query;	
	}

	// }}}
	// {{{ public function get_query_type()
	
	/**
	 * 获取 SQL 语句的类型 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_query_type()
	{
		return $this->__query_type;	
	}

	// }}}	
	// {{{ public function bind_param()

	/**
	 * 设置绑定参数 
	 * 
	 * @param string $param 
	 * @param mixed $variable 
	 * @access public
	 * @return void
	 */
	public function bind_param($param, $variable)
	{
		$this->__bound_params[$param] = $variable;	
	}

	// }}}
	// {{{ public function bind_params()

	/**
	 * 批量设置绑定参数 
	 * 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public function bind_params(array $params)
	{
		if (array_key_exists(0, $params)) { // 如果存在 key =0 的将其改为 1
			array_unshift($params, null);
			unset($params[0]);	
		}
		foreach ($params as $param => $value) {
			$this->bind_param($param, $value);
		}
	}

	// }}}
	// {{{ public function get_query_params()
	
	/**
	 * 获取绑定参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_query_params()
	{
		return $this->__bound_params;	
	}

	// }}}	
	// {{{ public function get_elapsed_secs()

	/**
	 * 获取 SQL 语句运行时间 
	 * 
	 * @access public
	 * @return float|booean
	 */
	public function get_elapsed_secs()
	{
		if (null === $this->__ended_microtime) {
			return false;	
		}

		return $this->__ended_microtime - $this->__started_microtime;
	}

	// }}}
	// {{{ public function get_started_microtime()

	/**
	 * 获取 SQL 运行的起始时间 
	 * 
	 * @access public
	 * @return float | boolean
	 */
	public function get_started_microtime()
	{
		if (null === $this->__started_microtime) {
			return false;	
		}	

		return $this->__started_microtime;
	}

	// }}}
	// }}}	
}
