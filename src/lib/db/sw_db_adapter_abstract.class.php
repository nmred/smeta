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
 
require_once PATH_SWAN_LIB . 'sw_db.class.php';
/**
+------------------------------------------------------------------------------
* sw_db_adapter_abstract 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_db_adapter_abstract
{
	// {{{ members

	/**
	 * 用户提供的配置 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__config = array();

	/**
	 * fetch的方式 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__fetch_mode = PDO::FETCH_ASSOC;

	/**
	 * 查询分析器，类型是sw_db_profiler或其子类 
	 * 
	 * @var sw_db_profiler
	 * @access protected
	 */
	protected $__profiler;

	/**
	 * 默认的pdo返回的statement对象类名 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_stmt_class = 'sw_db_statement';

	/**
	 * 默认查询分析器对象的类名 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_profiler_class = 'sw_db_profiler';

	/**
	 * 数据库连接对象 
	 * 
	 * @var object|resource|null
	 * @access protected
	 */
	protected $__connection = null;

	/**
	 * 查询时指定列名得方式
	 * Options
	 * PDO::CASE_NATURAL
	 * PDO::CASE_LOWER 
	 * PDO::CASE_UPPER 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $__case_folding = PDO::CASE_NATURAL;

	/**
	 * 指定是否自动为SQL标识符添加引号 
	 * 如果指定true，所有的SQL语句将自动添加引号在标识符上，如果false则需要调用quote_identifier()
	 * 方法
	 *
	 * @var bool
	 * @access protected
	 */
	protected $__auto_quote_indentifiers = true;

	/**
	 * DB的数字类型
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__numeric_data_types = array(
		sw_db::INI_TYPE    => sw_db::INI_TYPE,
		sw_db::BIGINT_TYPE => sw_db::BIGINT_TYPE,
		sw_db::FLOAT_TYPE  => sw_db::FLOAT_TYPE,
	);	

	/**
	 * 是否允许对象序列化 
	 * 
	 * @var bool
	 * @access protected
	 */
	protected $__allow_serialization = true;

	/**
	 * 当反序列化后是否允许自动重新连接数据库 
	 * 
	 * @var bool
	 * @access protected
	 */
	protected $__auto_reconnect_on_unserialize = false;

	// }}}	
	// {{{ functions
	// {{{ protected function _check_required_options()

	/**
	 * 检查必要的参数 
	 * 
	 * @param array $config 
	 * @access protected
	 * @throws sw_db_adapter_exception
	 */
	protected function _check_required_options(array $config)
	{
		if (!array_key_exists('dbname', $config)) {
			require_once PATH_SWAN_LIB 'db/sw_db_adapter_exception.class.php';
			throw new sw_db_adapter_exception('Configuration array must have a key for `dbname` tha  t names the database instance');
		}		

		if (!array_key_exists('password', $config)) {
			require_once PATH_SWAN_LIB 'db/sw_db_adapter_exception.class.php';
			throw new sw_db_adapter_exception('Configuration array must have a key for `password` tha  t names the database instance');
		}		

		if (!array_key_exists('username', $config)) {
			require_once PATH_SWAN_LIB 'db/sw_db_adapter_exception.class.php';
			throw new sw_db_adapter_exception('Configuration array must have a key for `username` tha  t names the database instance');
		}		
	}

	// }}}
	// {{{ public function get_connection()

	/**
	 * 获取数据库底层连接对象或资源 
	 * 
	 * @access public
	 * @return object|resource|null
	 */
	public function get_connection()
	{
		$this->_connect();
		return $this->__connection;
	}

	// }}}
	// {{{ public function get_config()

	/**
	 * 获取配置参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_config()
	{
		return $this->__config;	
	}

	// }}}
	// }}}
}
