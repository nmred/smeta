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
require_once PATH_SWAN_LIB . 'db/sw_db_expr.class.php';
/**
+------------------------------------------------------------------------------
* 获取数据表唯一序列号的类
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_sequence
{
	// {{{ const

	/**
	 * 添加记录失败尝试的次数
	 *
	 * @const
	 */
	const MAX_TRY_NUM = 1;

	// }}}
	// {{{ members

	/**
	 * 增长个数
	 * 
	 * @var int
	 * @access protected
	 */
	protected static $__increment = 1;

	// }}}
	// {{{ public static function get_increment_num()

	/**
	 * 获取增长个数
	 * 
	 * @access public
	 * @return string
	 */
	public static function get_increment_num()
	{
		return self::$__increment;
	}

	// }}}
	// {{{ public static function get_next_global()

	/**
	 * 获取全局的自增长 ID 
	 * 
	 * @param string $table_name
	 * @param sw_db $db
	 * @param int $init_id
	 * @access public
	 * @return intger
	 */
	public static function get_next_global($table_name, $db = null, $init_id = 1)
	{
		if (!isset($db)) {
			$db = sw_db::singleton();	
		}
		
		try {
			$db->begin_transaction();
			$transaction = true;	
		} catch (sw_exception $e) {
			$transaction = false;	
		}

		try {
			$fields = array('sequence_id' => new sw_db_expr('sequence_id+' . self::get_increment_num()));	
			$where = $db->quote_into('table_name = ?', $table_name);
			$try_num = 1;
			do {
				$affected = $db->update(SWAN_TBN_SEQUENCE_GLOBAL, $fields, $where);
				if (1 === $affected) {
					break;	
				}	

				//更新失败，记录不存在则添加
				try {
					$affected = $db->insert(SWAN_TBN_SEQUENCE_GLOBAL, array('table_name' => $table_name, 'sequence_id' => $init_id)); 
				} catch (sw_exception $e){
				}
			} while ($affected !== 1 && $try_num < self::MAX_TRY_NUM);

			if (1 === $affected) {
				$id = $db->fetch_one($db->select()
										->from(SWAN_TBN_SEQUENCE_GLOBAL, array('sequence_id' => 'sequence_id'))
										->where('table_name= ?'), $table_name);	
			}

			if (1 !== $affected || false === $id) {
				require_once PATH_SWAN_LIB . 'sequence/sw_sequence_exception.class.php';
				throw new sw_sequence_exception('get global sequence faild.');	
			}

			if ($transaction) {
				$db->commit();	
			}

			return $id;
		} catch (sw_exception $e) {
			if ($transaction) {
				$db->rollback();	
			}
			throw new sw_exception($e);	
		}
	}

	// }}}
	// {{{ public static function get_next_device()

	/**
	 * 获取全局的自增长 ID 
	 * 
	 * @param intger $device_id
	 * @param string $table_name
	 * @param sw_db $db
	 * @param int $init_id
	 * @access public
	 * @return intger
	 */
	public static function get_next_device($device_id, $table_name, $db = null, $init_id = 1)
	{
		if (!isset($db)) {
			$db = sw_db::singleton();	
		}

		//判断是否是合法的 device_id
		try {
			$device_id = $db->fetch_one($db->select()
							  ->from(SWAN_TBN_DEVICE, array('device_id'))
							  ->where('device_id= ?'), $device_id);
		} catch (sw_exception $e) {
			throw new sw_sequence_exception('invalid device id, get sequence faild. ');	
		}

		if (false === $device_id) {
			throw new sw_sequence_exception('invalid device id, get sequence faild. ');	
		}
		
		try {
			$db->begin_transaction();
			$transaction = true;	
		} catch (sw_exception $e) {
			$transaction = false;	
		}

		try {
			$fields = array('sequence_id' => new sw_db_expr('sequence_id+' . self::get_increment_num()));	
			$where = $db->quote_into('table_name = ?', $table_name);
			$where .= $db->quote_into(' AND device_id = ?', $device_id);
			$try_num = 1;
			do {
				$affected = $db->update(SWAN_TBN_SEQUENCE_DEVICE, $fields, $where);
				if (1 === $affected) {
					break;	
				}	

				//更新失败，记录不存在则添加
				$attribute = array(
					'device_id' => $device_id,
					'table_name' => $table_name,
					'sequence_id' => $init_id,
				);
				try {
					$affected = $db->insert(SWAN_TBN_SEQUENCE_DEVICE, $attribute); 
				} catch (sw_exception $e){
				}
			} while ($affected !== 1 && $try_num < self::MAX_TRY_NUM);

			if (1 === $affected) {
				$id = $db->fetch_one($db->select()
										->from(SWAN_TBN_SEQUENCE_DEVICE, array('sequence_id' => 'sequence_id'))
										->where('table_name= ? AND device_id= ?'), array($table_name, $device_id));	
			}

			if (1 !== $affected || false === $id) {
				require_once PATH_SWAN_LIB . 'sequence/sw_sequence_exception.class.php';
				throw new sw_sequence_exception('get device sequence faild.');	
			}

			if ($transaction) {
				$db->commit();	
			}

			return $id;
		} catch (sw_exception $e) {
			if ($transaction) {
				$db->rollback();	
			}
			throw new sw_exception($e);	
		}
	}

	// }}}
	// {{{ public static function get_next_project()

	/**
	 * 获取全局的自增长 ID 
	 * 
	 * @param intger $project_id
	 * @param string $table_name
	 * @param sw_db $db
	 * @param int $init_id
	 * @access public
	 * @return intger
	 */
	public static function get_next_project($project_id, $table_name, $db = null, $init_id = 1)
	{
		if (!isset($db)) {
			$db = sw_db::singleton();	
		}

		//判断是否是合法的 device_id
		try {
			$project_id = $db->fetch_one($db->select()
							  ->from(SWAN_TBN_DEVICE_PROJECT, array('project_id'))
							  ->where('device_id= ?'), $device_id);
		} catch (sw_exception $e) {
			throw new sw_sequence_exception('invalid device id, get sequence faild. ');	
		}

		if (false === $device_id) {
			throw new sw_sequence_exception('invalid device id, get sequence faild. ');	
		}
		
		try {
			$db->begin_transaction();
			$transaction = true;	
		} catch (sw_exception $e) {
			$transaction = false;	
		}

		try {
			$fields = array('sequence_id' => new sw_db_expr('sequence_id+' . self::get_increment_num()));	
			$where = $db->quote_into('table_name = ?', $table_name);
			$where .= $db->quote_into(' AND project_id = ?', $project_id);
			$try_num = 1;
			do {
				$affected = $db->update(SWAN_TBN_SEQUENCE_PROJECT, $fields, $where);
				if (1 === $affected) {
					break;	
				}	

				//更新失败，记录不存在则添加
				$attribute = array(
					'project_id' => $project_id,
					'table_name' => $table_name,
					'sequence_id' => $init_id,
				);
				try {
					$affected = $db->insert(SWAN_TBN_SEQUENCE_PROJECT, $attribute); 
				} catch (sw_exception $e){
				}
			} while ($affected !== 1 && $try_num < self::MAX_TRY_NUM);

			if (1 === $affected) {
				$id = $db->fetch_one($db->select()
										->from(SWAN_TBN_SEQUENCE_PROJECT, array('sequence_id' => 'sequence_id'))
										->where('table_name= ? AND project_id= ?'), array($table_name, $project_id));	
			}

			if (1 !== $affected || false === $id) {
				require_once PATH_SWAN_LIB . 'sequence/sw_sequence_exception.class.php';
				throw new sw_sequence_exception('get project sequence faild.');	
			}

			if ($transaction) {
				$db->commit();	
			}

			return $id;
		} catch (sw_exception $e) {
			if ($transaction) {
				$db->rollback();	
			}
			throw new sw_exception($e);	
		}
	}

	// }}}
}
