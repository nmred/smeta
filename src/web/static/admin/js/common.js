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
* 管理端基本库 :各个模块操作的基类
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

function ModuleBase()
{
	// {{{ members

	/**
	 * this 对象在外部的名字
	 *
	 * @type {String}  
	 */
	this.__thisName = 'this';
	
	// }}}
	// {{{ functions
	// {{{ function setThisName()
		
	/**
	 * 设置this对象在外部的名字
	 *
	 * @param {String} thisName
	 * @return {Void}  
	 */
	this.setThisName = function (thisName)
	{
		this.__thisName = thisName;	
	}

	// }}}
	// {{{ TODO 
	// }}}
}
