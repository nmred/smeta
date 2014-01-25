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
 
namespace lib\swdata\action;
use swan\controller\sw_action;

/**
+------------------------------------------------------------------------------
* sw_abatract 
+------------------------------------------------------------------------------
* 
* @uses sw
* @uses _action
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_abstract extends sw_action
{
	// {{{ functions
	// {{{ public function render_json()
	
	/**
	 * 格式化返回的格式 
	 * 
	 * @param array $data 
	 * @param string $code 
	 * @param string $msg 
	 * @access public
	 * @return void
	 */
	public function render_json($data, $code, $msg = null)
	{
		$result = array(
			'code' => $code,
			'msg'  => $msg,
			'data' => $data,
		);	

		return $this->json_stdout($result);
	}

	// }}}	
	// {{{ protected function _begin_transaction()
	
	/**
	 * 开启事务 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _begin_transaction()
	{
		$db = \swan\db\sw_db::singleton();	
		$db->begin_transaction();
	}

	// }}}	
	// {{{ protected function _commit()
	
	/**
	 * 提交事务 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _commit()
	{
		$db = \swan\db\sw_db::singleton();	
		$db->commit();
	}

	// }}}	
	// {{{ protected function _rollback()
	
	/**
	 * 回滚事务 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _rollback()
	{
		$db = \swan\db\sw_db::singleton();	
		$db->rollback();
	}

	// }}}	
	// }}}
}
