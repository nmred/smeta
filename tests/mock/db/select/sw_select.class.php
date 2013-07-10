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
	// {{{ public function set_parts()

	/**
	 * 设置参数 
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function set_parts($name, $value)
	{
		if (isset($this->__parts[$name])) {
			$this->__parts[$name] = $value;	
		}
	}

	// }}}
	// {{{ public function init_parts()

	/**
	 * 初始化参数 
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function init_parts()
	{
		$this->__parts = self::$__parts_init;
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
	// {{{ public function mock_unique_correlation()

	/**
	 * mock_unique_correlation 
	 * 
	 * @param mixed $name 
	 * @access public
	 * @return void
	 */
	public function mock_unique_correlation($name)
	{
		return $this->_unique_correlation($name);	
	}

	// }}}
	// {{{ public function mock_join()

	/**
	 * mock_join 
	 * 
	 * @param mixed $type 
	 * @param mixed $name 
	 * @param mixed $cols 
	 * @param mixed $schema 
	 * @access public
	 * @return void
	 */
	public function mock_join($type, $name, $cols, $schema = null)
	{
		$this->_join($type, $name, $cols, $schema);	
	}

	// }}}
	// {{{ public function mock_where()

	/**
	 * mock_where 
	 * 
	 * @param mixed $condition 
	 * @param mixed $value 
	 * @param mixed $type 
	 * @param mixed $bool 
	 * @access public
	 * @return void
	 */
	public function mock_where($condition, $value = null, $type = null, $bool = true)
	{
		return $this->_where($condition, $value, $type, $bool);
	}

	// }}}
	// {{{ public function mock_get_quoted_schema()

	/**
	 * mock_get_quoted_schema 
	 * 
	 * @access public
	 * @return void
	 */
	public function mock_get_quoted_schema($schema = null)
	{
		return $this->_get_quoted_schema($schema);
	}

	// }}}
	// {{{ public function mock_get_quoted_table()

	/**
	 * mock_get_quoted_table
	 * 
	 * @access public
	 * @return void
	 */
	public function mock_get_quoted_table($table_name, $correlation_name = null)
	{
		return $this->_get_quoted_table($table_name, $correlation_name);
	}

	// }}}
	// {{{ public function mock_render_columns()

	/**
	 * mock_render_columns 
	 * 
	 * @access public
	 * @return void
	 */
	public function mock_render_columns($sql)
	{
		return $this->_render_columns($sql);
	}

	// }}}	
}
