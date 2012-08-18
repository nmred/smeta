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
* DB类中stmt抽象类
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_db_statement_abstract
{
	// {{{ members

	/**
	 * stmt对象 
	 * 
	 * @var stmt object
	 * @access protected
	 */
	protected $__stmt = null;

	/** 
	 * 
	 * @var sw_db_adapter_abstract
	 * @access protected
	 */
	protected $__adapter = null;
	
	/**
	 * __fetch_mode 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__fetch_mode = PDO::FETCH_ASSOC;

	/**
	 * 属性 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__attribute = array();

	/**
	 * 绑定的字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bind_column = array();

	/**
	 * 绑定的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bind_param = array();

	/**
	 * 以分隔符将sql语句分隔为数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__sql_split = array();

	/**
	 * SQL语句中对应的占位符参数值 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__sql_param = array();

	/** 
	 * 
	 * @var sw_db_profiler_query
	 * @access protected
	 */
	protected $__query_id = null;

	// }}}	
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * __construct 
	 * 
	 * @param sw_db_adapter_abstract $adapter 
	 * @param sw_db_select $sql 
	 * @access public
	 * @return void
	 */
	public function __construct($adapter, $sql)
	{
		$this->__adapter = $adapter;
		if ($sql instanceof sw_db_select) {
			$sql = $sql->assemble();	
		}
		$this->_parse_parameters($sql);
		$this->_prepare($sql);

		$this->__query_id = $this->__adapter->get_profiler()->query_start($sql);
	}

	// }}}
	// {{{ public function _parse_parameters()

	/**
	 * 从sql语句中提取绑定参数 
	 * 
	 * @param string $sql 
	 * @access public
	 * @return void
	 */
	public function _parse_parameters($sql)
	{
		$sql = $this->_strip_quoted($sql);
		
		//分隔占位符和参数
		$this->__sql_split = preg_split('/(\?|\:[a-zA-Z0-9_]+)/',
			$sql, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
		
		$this->__sql_param = array();
		foreach ($this->__sql_split as $key => $val) {
			if ($val == '?') {
				if ($this->__adapter->supports_parameters('positional') === false) {
					require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
					throw new sw_db_statement_exception("Invalid bind-variable position '$val'");	
				}
			} else if ($val[0] == ':') {
				if ($this->__adapter->supports_parameters('named') === false) {
					require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
					throw new sw_db_statement_exception("Invalid bind-variable position '$val'");	
				}	
			}
			$this->__sql_param[] = $val;
		}		

		$this->__bind_param = array();
	}

	// }}}
	// {{{ public function _strip_quoted()

	/**
	 * 去除sql语句的quote字符 
	 * 
	 * @param string $sql 
	 * @access public
	 * @return string
	 */
	public function _strip_quoted($sql)
	{
		//获取引用边界符，一般是双引号，mysql中是反引号
		$d = $this->__adapter->quote_indentifier('a');
		$d = $d[0];
		
		// get the value used as an escaped delimited id quote,
		// e.g. \" or "" or \`
		$de = $this->__adapter->quote_indentifier($d);
		$de = substr($qe, 1, 2);
		$qe = str_replace('\\', '\\\\', $qe);
		
		$sql = preg_replace("/$q($qe|\\\\{2}|[^$q])*$q/", '', $sql);
		if (!empty($q)) {
			$sql = preg_replace("/$q($qe|[^$q])*$q/", '', $sql);	
		}	

		return $sql;
	}

	// }}}
	// {{{ public function bind_column()

	/**
	 * 绑定字段 
	 * 
	 * @param string $column 
	 * @param mixed $param 
	 * @param integer $type 
	 * @access public
	 * @return boolean
	 */
	public function bind_column($column, &$param, $type = null)
	{
		$this->_bind_column[$column] =& $param;
		return true;	
	}

	// }}}
	// {{{ public function bind_param()

	/**
	 * 绑定参数 
	 * 
	 * @param integer| string $parameter 
	 * @param mixed $variable 
	 * @param mixed $type 
	 * @param mixed $length 
	 * @param mixed $options 
	 * @access public
	 * @return boolean
	 */
	public function bind_param($parameter, &$variable, $type = null, $length = null, $options = null)
	{
		if (!is_int($parameter) && !is_string($parameter)) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception('Invalid bind-variable position');	
		}

		$position = null;
		if (($intval = (int) $parameter) > 0 && $this->__adapter->supports_parameters('positional')) {
			if ($intval >= 1 || $intval <= count($this->__sql_param)) {
				$position = $intval;	
			}
		} else if ($this->__adapter->supports_parameters('named')) {
			if ($parameter[0] != ':') {
				$parameter = ':' . $parameter;	
			}	
			if (in_array($parameter, $this->__sql_param) !== false) {
				$position = $parameter;	
			}
		}

		if ($position === null) {
			require_once PATH_SWAN_LIB . 'db/sw_db_statement_exception.class.php';
			throw new sw_db_statement_exception("Invalid bind-variable position '$parameter'");		
		}

		$this->__bind_param[$position] =& $variable;
		return $this->_bind_param($position, $variable, $type, $length, $options);
	}

	// }}}
	// }}} end functions
}
