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
 
namespace mock\db\select;
use lib\db\select\sw_select as sw_mock_select;

/**
+------------------------------------------------------------------------------
* sw_mock_select 
+------------------------------------------------------------------------------
* 
* @uses sw_mock_select
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_select extends sw_mock_select
{
	// {{{ functions
	// {{{ public function get_parts()

	/**
	 * get_parts 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_parts()
	{
		return $this->__parts;	
	}

	// }}}
	// {{{ public function get_init_part()

	/**
	 * 获取初始化 select 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_init_part()
	{
		return self::$__parts_init;	
	}

	// }}}
	// {{{ public function mock_table_cols()

	/**
	 * mock_table_cols 
	 * 
	 * @param mixed $correlation_name 
	 * @param mixed $cols 
	 * @param mixed $after_correlation_name 
	 * @access public
	 * @return void
	 */
	public function mock_table_cols($correlation_name, $cols, $after_correlation_name = null)
	{
		$this->_table_cols($correlation_name, $cols, $after_correlation_name);	
	}

	// }}}
	// }}}	
}
