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
use lib\db\profiler\sw_profiler_query as sw_profiler_query;
use lib\db\profiler\exception\sw_exception as sw_exception;

/**
+------------------------------------------------------------------------------
* sw_profiler 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_profiler
{
	// {{{ const

	/**
	 * 连接数据库类型  
	 */
	const CONNECT = 1;

	/**
	 * 任何通过数据库的查询类型  
	 */
	const QUERY = 2;

	/**
	 * 插入数据库记录， 例：INSERT操作 
	 */
	const INSERT = 4;

	/**
	 * 更新一条数据库中已存在的信息, 例如UPDATE操作 
	 */
	const UPDATE = 8;

	/**
	 * 删除操作 
	 */
	const DELETE = 16;

	/**
	 * 查询操作 
	 */
	const SELECT = 32;

	/**
	 * TRANSACTION 事务操作 
	 */
	const TRANSACTION = 64;

	/**
	 * 通知一个操作可以进行存储 
	 */
	const STORED = 'stored';

	/**
	 * 通知一个操作应该忽略 
	 */
	const IGNORED = 'ignored';

	// }}}
	// {{{ members

	/**
	 * 存储lib\db\profiler\sw_profiler_query 对象 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__query_profiles = array();
	
	/**
	 * 分析器的启用状态设置 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__enabled = false;

	/**
	 * 根据运行时间设定的过滤规则，当 NULL 时将不启用时间过滤规则，当设置整数秒数时，查询运行时间小于该时间时
	 *  将从分析器中过滤掉不进行分析
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__filter_elapse_secs = null;

	/**
	 * 根据操作类型设定的过滤规则，如果设置为 NULL 将不启用类型过滤规则，传递的是设定的常量，在查询结束时指定 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__filter_types = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param boolean $enabled 
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
	 * @param boolean $enabled 
	 * @access public
	 * @return $this
	 */
	public function set_enabled($enabled)
	{
		$this->__enabled = (boolean) $enabled;	

		return $this;
	}

	// }}}
	// {{{ public function get_enabled()

	/**
	 * 获取 SQL 分析器的状态 
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
	 * 设置时间的最小过滤器 
	 * 
	 * @param int $min_seconds 
	 * @access public
	 * @return lib\db\profiler\sw_profiler
	 */
	public function set_filter_elapsed_secs($min_seconds = null)
	{
		if (null == $min_seconds) {
			$this->__filter_elapse_secs = null;	
		} else {
			$this->__filter_elapse_secs = (integer) $min_seconds;	
		}

		return $this;
	}

	// }}}
	// {{{ public function get_filter_elapsed_secs()

	/**
	 * 获取过滤器最小时间 
	 * 
	 * @access public
	 * @return integer|null
	 */
	public function get_filter_elapsed_secs()
	{
		return $this->__filter_elapse_secs;	
	}

	// }}}
	// {{{ public function set_filter_query_type()
	
	/**
	 * 设置过滤器的操作类型 
	 * 
	 * @param integer $query_types 
	 * @access public
	 * @return lib\db\profiler\sw_profiler
	 */
	public function set_filter_query_type($query_types = null)
	{
		$this->__filter_types = $query_types;

		return $this;
	}

	// }}}
	// {{{ public function get_filter_query_type()

	/**
	 * 获取过滤器操作类型 
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
	 * @return lib\db\profiler\sw_profiler
	 */
	public function clear()
	{
		$this->__query_profiles = array();
		
		return $this;	
	}

	// }}}
	// {{{ public function query_clone()

	/**
	 * 克隆一个查询对象存储到 self::$__query_profiles中
	 * 
	 * @param sw_profiler_query $query 
	 * @access public
	 * @return integer 
	 */
	public function query_clone(sw_profiler_query $query)
	{
		$this->__query_profiles[] = clone $query;

		end($this->__query_profiles);

		return key($this->__query_profiles);
	}

	// }}}
	// {{{ public function query_start()

	/**
	 * SQL 操作开始 
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

		$this->__query_profiles[] = new sw_profiler_query($query_text, $query_type); 

		end($this->__query_profiles);

		return key($this->__query_profiles);
	}

	// }}}
	// {{{ public function query_end()

	/**
	 * 结束一个 SQL 操作 
	 * 
	 * @param integer $query_id 启动分析器时返回的 ID
	 * @access public
	 * @return integer
	 */
	public function query_end($query_id)
	{
		if (!$this->__enabled) {
			return self::IGNORED;	
		}

		if (!isset($this->__query_profiles[$query_id])) {
			throw new sw_exception("Profiler has no query with handle `$query_id` .");
		}

		$qp = $this->__query_profiles[$query_id];

		if ($qp->has_ended()) {
			throw new sw_exception("Query with profiler handle `$query_id` has already ended.");
		}

		$qp->end();

		// 开始过滤
		if (null !== $this->__filter_elapse_secs && $qp->get_elapsed_secs() < $this->__filter_elapse_secs) {
			unset($this->__query_profiles[$query_id]);
			return self::IGNORED;	
		}

		if (null !== $this->__filter_types && ($qp->get_query_type() & $this->__filter_types)) {
			unset($this->__query_profiles[$query_id]);
			return self::IGNORED;	
		}

		return self::STORED;
	}

	// }}}
	// {{{ public function get_query_profile()

	/**
	 * 获取查询对象 
	 * 
	 * @param integer $query_id 
	 * @access public
	 * @return sw_profiler_query
	 */
	public function get_query_profile($query_id)
	{
		if (!array_key_exists($query_id, $this->__query_profiles)) {
			throw new sw_exception("Query handle `$query_id` not found in profiler log.");
		}

		return $this->__query_profiles[$query_id];
	}

	// }}}
	// {{{ public function get_query_profiles()

	/**
	 * 获取所有查询或者是未完成的所有操作 
	 * 
	 * @param integer $query_type 
	 * @param boolean $show_unfinished 
	 * @access public
	 * @return array | boolean
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
	 * 获取一共所有操作执行的时间 
	 * 
	 * @param integer $query_type 
	 * @access public
	 * @return float
	 */
	public function get_total_elapsed_secs($query_type = null)
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
	 * 获取所有的SQL 总数 
	 * 
	 * @param integer $query_type 
	 * @access public
	 * @return integer
	 */
	public function get_total_num_queries($query_type = null)
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
	 *  获取最后一次操作 SQL语句 
	 * 
	 * @access public
	 * @return sw_profiler_query
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
	// }}}	
}
