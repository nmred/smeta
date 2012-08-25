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
* 查询分析器
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_db_profiler
{
	// {{{ members

	/**
	 * 是一个连接数据库的操作，或select一个数据库  
	 */
	const CONNECT = 1;

	/**
	 * 是一个连接数据库的操作，或select一个数据库  
	 */
	const CONNECT = 1;

	/**
	 * 任何通过的数据库查询
	 */
	const QUERY = 2;

	/**
	 * 添加一条新的记录到数据库，例如：INSERT操作  
	 */
	const INSERT = 4;

	/**
	 * 更新一条数据库中已存在的信息，例如：UPDATE操作  
	 */
	const UPDATE = 8;

	/**
	 * 一条相关数据库的相关删除操作， 例如：DELETE操作  
	 */
	const DELETE = 16;

	/**
	 * 获取记录从数据库，例如：SELECT操作  
	 */
	const SELECT = 32;

	/**
	 * 事务操作，例如：start transaction,commit或roolback 
	 */
	const TRANSACTION = 64;

	/**
	 * 通知一个查询存储（为了过滤） 
	 */
	const STORED = 'stored';

	/**
	 * 通知一个查询忽略（为了过滤）
	 */
	const IGNORED = 'ignored';

	/**
	 * 存储sw_db_profiler_query对象 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__query_profiles = array();

	/**
	 * 分析器的启用状态设置 
	 * 
	 * @var bool
	 * @access protected
	 */
	protected $__enabled = false;

	/**
	 * 根据运行时间设定过滤规则，当NULL时将不启用时间过滤规则，当设置整数秒数时，查询运行时间小于
	 * 时间时将从分析器中过滤掉即不分析 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__filter_elapse_secs = null;

	/**
	 * 根据操作类型设定的过滤规则，如果NULL将不启用类型过滤规则，传递的是设定的常量，在查询结束时
	 * 指定，如果不指定则将被直接过滤掉 ，例如：self::SELECT,self::UPDATE
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__filter_types = null;

	// }}}	
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造器  
	 * 
	 * @param bool $enabled 
	 * @access public
	 * @return void
	 */
	public function __construct($enabled = false)
	{
		$this->set_enabled($enabled);	
	}

	// }}}
	// {{{ public function set_enabled()

	/**
	 * 设置是否开启分析器 
	 * 
	 * @param  bool $enabled
	 * @access public
	 * @return sw_db_profiler
	 */
	public function set_enabled($enabled)
	{
		$this->__enabled = (boolean) $enabled;
		
		return $this;	
	}

	// }}}
	// {{{ public function get_enabled()

	/**
	 * 获取DB分析器的状态 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function get_enabled()
	{
		return $this->__enabled;	
	}

	// }}}
	// {{{ public function set_filter_elapsed_secs()
	
	/**
	 * 设置时间过滤器的最小时间 
	 * 
	 * @param integer $min_seconds 
	 * @access public
	 * @return void
	 */
	public function set_filter_elapsed_secs($min_seconds = null)
	{
		if (null === $min_seconds) {
			$this->__filter_elapse_secs = null;	
		} else {
			$this->__filter_elapse_secs = (integer) $min_seconds;	
		}

		return $this;
	}

	// }}}
	// {{{ public function get_filter_elapsed_secs()

	/**
	 * 获取时间过滤器设定的最小时间 
	 * 
	 * @access public
	 * @return integer |null
	 */
	public function get_filter_elapsed_secs()
	{
		return $this->__filter_elapse_secs;	
	}

	// }}}
	// {{{ public function set_filter_query_type()

	/**
	 * 设置类型过滤器的规则 
	 * 
	 * @param integer $query_types 
	 * @access public
	 * @return sw_db_profiler
	 */
	public function set_filter_query_type($query_types = null)
	{
		$this->__filter_types = $query_types;

		return $this;
	}

	// }}}
	// {{{ public function get_filter_query_type()

	/**
	 * 获取类型过滤器规则 
	 * 
	 * @access public
	 * @return integer|null
	 */
	public function get_filter_query_type()
	{
		return $this->__filter_types;	
	}

	// }}}
	// {{{ public function clear()

	/**
	 * 清除所有的历史记录 
	 * 
	 * @access public
	 * @return sw_db_profiler
	 */
	public function clear()
	{
		$this->__query_profiles = array();
		
		return $this;	
	}

	// }}}
	// {{{ public function query_clone()
	
	/**
	 * 克隆一个查询的对象存储到$__query_profiles中 
	 * 
	 * @param sw_db_profiler_query $query 
	 * @access public
	 * @return integer 返回克隆出的键值
	 */
	public function query_clone(sw_db_profiler_query $query)
	{
		$this->__query_profiles[] = clone $query;
		
		end($this->__query_profiles);
		
		return key($this->__query_profiles);	
	}

	// }}}
	// {{{ public function query_start()
	
	/**
	 * 查询操作开始，返回查询分析器操作句柄，创建一个sw_db_profiler_query 对象，然后运行查询操作
	 * ，最后调用query_end()从而运算出执行时间
	 * 
	 * @param string $query_text 
	 * @param integer|null $query_type 
	 * @access public
	 * @return integer
	 */
	public function query_start($query_text, $query_type = null)
	{
		if (!$this->__enabled) {
			return null;	
		}	
		
		if (null === $query_type) {
			switch (strtolower(substr(ltrim($query_text), 0, 6))) {
				case 'insert':
					$query_type = self::INSERT;
					break;
				case 'update':
					$query_type = self::UPDATE;
					break;
				case 'delete':
					$query_type = self::DELETE;
					break;
				case 'select':
					$query_type = self::SELECT;
					break;
				default:
					$query_type = self::QUERY;
					break;	
			}	
		}

		require_once PATH_SWAN_LIB . 'db/sw_db_profiler_query.class.php';
		$this->__query_profiles[] = new sw_db_profiler_query($query_text, $query_type);

		end($this->__query_profiles);

		return key($this->__query_profiles);
	}

	// }}}
	// {{{ public function query_end()

	/**
	 * 处理操作结束 
	 * 
	 * @param integer $query_id 
	 * @access public
	 * @return string 返回处理发送的标志，在常量中定义，例如：self::IGNORED
	 */
	public function query_end($query_id)
	{
		if (!$this->__enabled) {
			return self::IGNORED;	
		}		

		if (!isset($this->__query_profiles[$query_id])) {
			require_once PATH_SWAN_LIB . 'db/sw_db_profiler_exception.class.php';
			throw new sw_db_profiler_exception("Profiler has no query with handle '$query_id'.");
				
		}

		$qp = $this->__query_profiles[$query_id];
		
		if ($qp->has_ended()) {
			require_once PATH_SWAN_LIB . 'db/sw_db_profiler_exception.class.php';
			throw new sw_db_profiler_exception("Query with profiler handle '$queryId' has already ended.");
		}
		$qp->end();

		//开始过滤
		if (null !== $this->__filter_elapse_secs && $qp->get_elapsed_secs() < $this->__filter_elapse_secs) {
			unset($this->__query_profiles[$query_id]);
			return self::IGNORED;	
		}

		if (null !== $this->__filter_types && !($qp->get_query_type() & $this->__filter_types)) {
			unset($this->__query_profiles[$query_id]);
			return self::IGNORED;	
		}

		return self::STORED;
	}

	// }}}
	// {{{ public function get_query_profile()

	/**
	 * 获取在分析器中的查询单个记录 
	 * 
	 * @param integer $query_id 
	 * @access public
	 * @return sw_db_profiler_query
	 */
	public function get_query_profile($query_id)
	{
		if (!array_key_exists($query_id, $this->__query_profiles)) {
			require_once PATH_SWAN_LIB . 'db/sw_db_profiler_exception.class.php';
			throw new sw_db_profiler_exception("Query handle '$query_id' not found in profiler log.");	
		}
		return $this->__query_profiles[$query_id];
	}

	// }}}
	// {{{ public function get_query_profiles()

	/**
	 * 根据操作类型或是否完成 
	 * 
	 * @param integer $query_type 
	 * @param bool $show_unfinished 
	 * @access public
	 * @return array|bool
	 */
	public function get_query_profiles($query_type = null, $show_unfinished = false)
	{
		$query_profiles = array();
		foreach ($this->__query_profiles as $key => $qp) {
			if ($query_type === null) {
				$condition = true;	
			} else {
				$condition = ($qp->get_query_type() & $query_type);
			}

			if (($qp->has_ended() || $show_unfinished) && $condition) {
				$query_profiles[$key] = $qp;	
			}
		}

		if (empty($query_profiles)) {
			$query_profiles = false;	
		}

		return $query_profiles;
	}

	// }}}
	// {{{ public function get_total_elapsed_secs()

	/**
	 * 获取运行时间的总数 
	 * 
	 * @param integer $query_type 
	 * @access public
	 * @return integer
	 */
	public function get_total_elapsed_secs($query_type)
	{
		$elapsed_secs = 0;
		foreach ($this->__query_profiles as $key => $qp) {
			if (null === $query_type) {
				$condition = true;	
			} else {
				$condition = ($qp->get_query_type() & $query_type);	
			}
			if (($qp->has_ended()) && $condition) {
				$elapsed_secs += $qp->get_elapsed_secs();	
			}
		}	
		return $elapsed_secs;
	}

	// }}}
	// {{{ public function get_total_num_queries()
	
	/**
	 * 获取运行的SQL语句的总数 
	 * 
	 * @param integer $query_type 
	 * @access public
	 * @return integer
	 */
	public function get_total_num_queries($query_type)
	{
		if (null === $query_type) {
			return count($this->__query_profiles);	
		}
		
		$num_queries = 0;
		foreach ($this->__query_profiles as $qp) {
			if ($qp->has_ended() && ($qp->get_query_type() & $query_type)) {
				$num_queries++;	
			}	
		}
		return $num_queries;
	}

	// }}}
	// {{{ public function get_last_query_profile()
	
	/**
	 * 获取最后操作的SQL语句的对象 
	 * 
	 * @access public
	 * @return sw_db_profiler_query
	 */
	public function get_last_query_profile()
	{
		if (empty($this->__query_profiles)) {
			return false;	
		}	

		end($this->__query_profiles);

		return current($this->__query_profiles);
	}

	// }}}
	// }}} end functions
}
