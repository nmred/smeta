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
 
require_once PATH_SWAN_LIB . 'db/sw_db_adapter_abstract.class.php';
/**
+------------------------------------------------------------------------------
* PDO的mysql驱动类
+------------------------------------------------------------------------------
* 
* @uses sw_db_adapter_mysql
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_db_adapter_sqlite extends sw_db_adapter_abstract
{
	// {{{ members

	/**
	 * __pdo_type 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__pdo_type = 'sqlite';

	/**
	 * __numeric_data_types 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__numeric_data_types = array (
		sw_db::INT_TYPE    => sw_db::INT_TYPE,
		sw_db::BIGINT_TYPE => sw_db::BIGINT_TYPE,
		sw_db::FLOAT_TYPE  => sw_db::FLOAT_TYPE,
		'INTEGER'          => sw_db::FLOAT_TYPE,
		'REAL'             => sw_db::BIGINT_TYPE,
	);

	// }}}	
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param array $config 
	 * @access public
	 * @return void
	 */
	public function __construct(array $config = array())
	{
		if (isset($config['sqlite2']) && $config['sqlite2']) {
			$this->__pdo_type = 'sqlite2';	
		}	
		$this->__config['username'] = null;
		$this->__config['password'] = null;

		return parent::__construct($config);
	}

	// }}}
	// {{{ protected function _check_required_options()

	/**
	 * 重写检查参数的方法 
	 * 
	 * @param array $config 
	 * @access protected
	 * @return void
	 * @throws sw_db_adapter_exception
	 */
	protected function _check_required_options(array $config)
	{
		if (!array_key_exists('dbname', $config)) {
			require_once PATH_SWAN_LIB . 'db/sw_db_adapter_exception.class.php';
			throw new sw_db_adapter_exception("Configuration array must have a key for 'dbname' that names the database instance");	
		}
	}

	// }}}
 	// {{{ protected function _dsn()

	/**
	 * 重写dsn方法 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _dsn()
	{
		return $this->__pdo_type . ':' . $this->__config['dbname'];
	}

	// }}}
	// {{{ protected function _connect()

	/**
	 * 创建PDO对象和连接数据库 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _connect()
	{
		if ($this->__connection) {
			return;	
		}

		parent::_connect();

		$retval = $this->__connection->exec('PRAGMA full_column_names=0');
		if ($retval === false) {
			$error = $this->__connection->errorInfo();
			require_once PATH_SWAN_LIB . 'db/sw_db_adapter_exception.class.php';
			throw new sw_db_adapter_exception($error[2]);	
		}

		$retval = $this->__connection->exec('PRAGMA short_column_names=1');
		if ($retval === false) {
			$error = $this->__connection->errorInfo();
			require_once PATH_SWAN_LIB . 'db/sw_db_adapter_exception.class.php';
			throw new sw_db_adapter_exception($error[2]);	
		}
	}
	 
	// }}}
	// {{{ public function list_tables()

	/**
	 * 获取数据库中的所有的数据表 
	 * 
	 * @access public
	 * @return array
	 */
	public function list_tables()
	{
		$sql = "SELECT name FROM sqlite_master WHERE type='table' "
			 . "UNION ALL SELECT name FROM sqlite_temp_master "
			 . "WHERE type='table' ORDER BY name";
		return $this->fetch_col($sql);	
	}

	// }}}
	// {{{ public function describe_table()

	/**
	 * describe_table 
	 * 
	 * @param string $table_name 
	 * @param string $schema_name 
	 * @access public
	 * @return array
	 */
	public function describe_table($table_name, $schema_name = null)
	{
		$sql = 'PRAGMA ';

		if ($schema_name) {
			$sql = $this->quote_indentifier($schema_name) . '.';	
		} 

		$sql = 'table_info(' . $this->quote_indentifier($table_name) . ')';	
		$stmt = $this->query($sql);

		$result = $stmt->fetch_all(PDO::FETCH_NUM);
		$cid        = 0;
		$name       = 1;
		$type       = 2;
		$notnull    = 3;
		$dflt_value = 4;
		$pk         = 5;

		$desc = array();
		$p = 1;
		foreach ($result as $key => $row) {
			list($length, $scale, $precision, $primary, $primary_position, $identity) 
			  = array(null, null, null, false, null, false);
			if (preg_match('/unsigned/', $row[$type])) {
				$unsigned = true;	
			}	
			if (preg_match('/^((?:var)char)\((\d+)\)/', $row[$type], $matches)) {
				$row[$type] = $matches[1];
				$length = $matches[2];	
			} else if (preg_match('/^decimal\((\d+),(\d+)\)/', $row[$type], $matches)) {
				$row[$type] = 'DECIMAL';
				$precision = $matches[1];
				$scale = $matches[2];	
			}

			if ((bool) $row[$pk]) {
				$primary = true;
				$primary_position = $p;
				$identity = (bool) ($row[$type] == 'INTEGER');	
				++$p;
			}
			$desc[$this->fold_case($row[$name])] = array(
				'SCHEMA_NAME'      => $this->fold_case($schema_name),
				'TABLE_NAME'       => $this->fold_case($table_name),
				'COLUMN_NAME'      => $this->fold_case($row[$name]),
				'COLUMN_POSITION'  => $row[$cid] + 1,
				'DATA_TYPE'        => $row[$type],
				'DEFAULT'          => $row[$dflt_value],
				'NULLABLE'         => ! (bool) $row[$notnull],
				'LENGTH'           => $length,
				'SCALE'            => $scale,
				'PRECISION'        => $precision,
				'UNSIGNED'         => null,
				'PRIMARY'          => $primary,
				'PRIMARY_POSITION' => $primary_position,
				'IDENTITY'         => $identity,
			);
		}
		return $desc;
	}

	// }}}
	// }}} end functions
}
