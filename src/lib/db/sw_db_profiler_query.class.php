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
 
/**
+------------------------------------------------------------------------------
* sw_db_profiler_query 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_db_profiler_query
{
	// {{{ members

	/**
	 * SQL语句或注释 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__query = '';

	/**
	 * SQL语句类型 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__query_type = 0;

	/**
	 * 开始执行的微妙 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__started_microtime = null;

	/**
	 * 运行结束的微秒 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__ended_microtime = null;

	/**
	 * __bound_params 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bound_params = array();

	// }}} end members
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * 构造器 
	 * 
	 * @param string $query 
	 * @param integer $query_type 
	 * @access public
	 * @return void
	 */
	public function __construct($query, $query_type)
	{
		$this->__query      = $query;
		$this->__query_type	= $query_type;
		$this->start();
	}

	// }}}
	// {{{ public funciton __clone()

	/**
	 * 克隆魔术方法 
	 * 
	 * @access public
	 * @return void
	 */
	public function __clone()
	{
		$this->__bound_params = array();
		$this->__ended_microtime = null;
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
	 * 结束 
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
	 * 判断一个SQL语句是否执行完毕 
	 * 
	 * @access public
	 * @return bool
	 */
	public function has_ended()
	{
		return $this->__ended_microtime !== null;	
	}

	// }}}
	// {{{ public function get_query()
	
	/**
	 * get_query 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_query()
	{
		return $this->__query;		
	}

	// }}}
	// {{{ public function get_query_type()

	/**
	 * 获取SQL语句的类型 
	 * 
	 * @access public
	 * @return integer
	 */
	public function get_query_type()
	{
		return $this->__query_type;	
	}

	// }}}
	// {{{ public function bind_param()

	/**
	 * 设置参数 
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
	// {{{ public funcion bind_params()

	/**
	 * bind_params 
	 * 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public function bind_params(array $params)
	{
		if (array_key_exists(0, $params)) {
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
	 * 获取参数 
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
	 * 获取SQL语句运行时间 
	 * 
	 * @access public
	 * @return float | bool
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

	public function get_started_microtime()
	{
		if (null === $this->__started_microtime) {
			return false;	
		}	

		return $this->__started_microtime;
	}

	// }}}
	// }}} end functions
}
