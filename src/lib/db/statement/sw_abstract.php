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
 
namespace lib\db\statement;
use lib\db\sw_db;
use lib\db\profiler\sw_profiler;
use lib\db\select\sw_select;
use lib\db\sw_db_expr;
use lib\db\statement\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_abstract 
+------------------------------------------------------------------------------
* 
* @package lib
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_abstract
{
	// {{{ members

	/**
	 * 数据库操作适配器 
	 * 
	 * @var lib\db\adapter\sw_abstract
	 * @access protected
	 */
	protected $__adapter = null;

	/**
	 * 查询日志对象 
	 * 
	 * @var lib\db\profiler\sw_profiler_query
	 * @access protected
	 */
	protected $__query_id = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($adapter, $sql)
	{
		$this->__adapter = $adapter;
		if ($sql instanceof sw_select) {
			$sql = $sql->assemble();		
		}
		$this->_prepare($sql);

		$this->__query_id = $this->__adapter->get_profiler()->query_start($sql);
	}

	// }}}
	// {{{ protected function _prepare()

	protected function _prepare($sql:)
	{
		try {
			$this->__stmt = $this->__adapter->get_connection()->prepare($sql);	
		}	
	}

	// }}}
	// }}}
}
