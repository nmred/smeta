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
	var __this = this;
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
		__this.__thisName = thisName;	

		//这里可以放一些每个页面都要执行的逻辑

		//检查该页面是否在子框架内
		__this.fCheckLocation();

		//ajax请求状态设置
		$(top.g("ajax_loading")).ajaxSuccess(function () {
			this.style.display = "none";
		})
		.ajaxStart(function () {
			this.style.display = "";	
		})
		.ajaxError(function () {
			this.style.display = "none";	
		});
	}

	// }}}
	// {{{ function checkLogin()
	
	/**
	 * 检查是否登录
	 *
	 * @param {Object} data
	 * @return {Boolean}  
	 */
	this.checkLogin = function (data) 
	{
		return true;	
	}
	 
	// }}}
	// {{{ function fJumpTo()
	
	/**
	 * 跳转在子框架中
	 *
	 * @return {Boolean}  
	 */
	this.fJumpTo = function (qs, param, timeout) 
	{
		if ("undefined" == typeof(timeout)) {
			var timeout = 0;	
		}

		setTimeout(function () { top.gBase.jumpHref(qs, param);}, timeout);
	}
	 
	// }}}
	// {{{ function fCheckLocation()

	
	/**
	 * 检查是否在子框架内
	 *
	 * @return {Boolean}  
	 */
	this.fCheckLocation = function ()
	{
		var urlPrefix = top.location.pathname;
		var _search = top.location.search.substr(1);
		var _portion = _search.split("&");
		var _count = _portion.length;
		var _qValue = "";
		
		for (var i = 0; i < _count; i++) {
			var _tmp = _portion[i].split("=");
			if ("q" === _tmp[0]) {
				_qValue = decodeURIComponent(_tmp[1]);	
				break;
			}
		}	

		//处理直接访问不在子框架中的页面
		if (_qValue !== "base") {
			top.location.href = urlPrefix + "?q=base&furl=" + encodeURIComponent(_search);	
		}
	}

	// }}}
	// }}}
}

