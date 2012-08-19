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
class sw_db_adapter_mysql extends sw_db_adapter_abstract
{
	// {{{ members

	/**
	 * __pdo_type 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__pdo_type = 'mysql';

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
		'INT'              => sw_db::INT_TYPE,
		'INTEGER'          => sw_db::INT_TYPE,
		'MEDIUMINT'        => sw_db::INT_TYPE,
		'SMALLINT'         => sw_db::INT_TYPE,
		'TINYINT'          => sw_db::INT_TYPE,
		'BIGINT'           => sw_db::BIGINT_TYPE,
		'SERIAL'           => sw_db::BIGINT_TYPE,
		'DEC'              => sw_db::FLOAT_TYPE,
		'DECIMAL'          => sw_db::FLOAT_TYPE,
		'DOUBLE'           => sw_db::FLOAT_TYPE,
		'DOUBLE_PRECISION' => sw_db::FLOAT_TYPE,
		'FIXED'            => sw_db::FLOAT_TYPE,
		'FLOAT'            => sw_db::FLOAT_TYPE,
	);

	// }}}	
	// {{{ functions
 	// {{{ protected function _dsn()

	/**
	 * 重写dsn方法 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _dsn()
	{
		$dsn = parent::_dsn();
		if (isset($this->__config['charset'])) {
			$dsn .= ';charset=' . $this->__config['charset'];
		}
		return $dsn;
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

		if (!empty($this->__config['charset'])) {
			$init_command = "SET NAMES '" . $this->__config['charset'] . "'";
			$this->__config['driver_options'][1002] = $init_command; // 1002 = PDO::MYSQL_ATTR_INIT_COMMAND	
		}

		parent::_connect();
	}
	 
	// }}}
	// {{{ public function get_quote_identifier_symbol()

	/**
	 * 重写方法get_quote_indentifier_symbol 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_quote_identifier_symbol()
	{
		return "`";	
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
		return $this->fetch_col('SHOW TABLES');	
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
		if ($schema_name) {
			$sql = 'DESCRIBE ' . $this->quote_indentifier("$schema_name.$table_name", true);	
		} else {
			$sql = 'DESCRIBE ' . $this->quote_indentifier($table_name, true);	
		}
		$stmt = $this->query($sql);

		$result = $stmt->fetch_all(PDO::FETCH_NUM);
		$field   = 0;
		$type    = 1;
		$null    = 2;
		$key     = 3;
		$default = 4;
		$extra   = 5;

		$desc = array();
		$i = 1;
		$p = 1;
		foreach ($result as $row) {
			list($length, $scale, $precision, $unsigned, $primary, $primary_position, $identity) 
			  = array(null, null, null, null, false, null, false);
			if (preg_match('/unsigned/', $row[$type])) {
				$unsigned = true;	
			}	
			if (preg_match('/^((?:var)char)\((\d+)\)/', $row[$type], $matches)) {
				$row[$type] = $matches[1];
				$length = $matches[2];	
			} else if (preg_match('/^decimal\((\d+),(\d+)\)/', $row[$type], $matches)) {
				$row[$type] = 'decimal';
				$precision = $matches[1];
				$scale = $matches[2];	
			} else if (preg_match('/^float\((\d+),(\d+)\)/', $row[$type], $matches)) {
				$row[$type] = 'float';
				$precision = $matches[1];
				$scale = $matches[2];	
			} else if (preg_match('/^((?:big|medium|small|tiny)?int)\((\d+)\)/', $row[$type], $matches)) {
				$row[$type] = $matches[1];	
			}
			if (strtoupper($row[$key]) == 'PRI') {
				$primary = true;
				$primary_position = $p;
				if ($row[$extra] == 'auto_increment') {
					$identity = true;	
				} else {
					$identity = false;	
				}
				++$p;
			}
			$desc[$this->fold_case($row[$field])] = array(
				'SCHEMA_NAME'      => null,
				'TABLE_NAME'       => $this->fold_case($table_name),
				'COLUMN_NAME'      => $this->fold_case($row[$field]),
				'COLUMN_POSITION'  => $i,
				'DATA_TYPE'        => $row[$type],
				'DEFAULT'          => $row[$default],
				'NULLABLE'         => (bool) ($row[$null] == 'YES'),
				'LENGTH'           => $length,
				'SCALE'            => $scale,
				'PRECISION'        => $precision,
				'UNSIGNED'         => $unsigned,
				'PRIMARY'          => $primary,
				'PRIMARY_POSITION' => $primary_position,
				'IDENTITY'         => $identity,
			);
			++$i;
		}
		return $desc;
	}

	// }}}
	// }}} end functions
}
