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
* base页面
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

function Base() {
	ModuleBase.call(this);
	var __this = this;

	// {{{ functions
	// {{{ function init()
		
	/**
	 * 初始化  
	 */
	this.init = function()
	{
		$(document).ready(function() {
			//设置左侧的滚动条
			$("#sidebar").mouseover(function() {
				$("#sidebar").css('overflow-y', 'auto');	
				$("#sidebar").css('overflow-x', 'hidden');	
			});						
			$("#sidebar").mouseout(function() {
				$("#sidebar").css('overflow', 'hidden');	
			});						
			
			//设置右侧iframe宽度
			var _contentWidth = parseInt($("#main").css("width")) - 270;
			$("#div_iframe").css("width", _contentWidth + 'px');

			__this._drawMenu();
		});	
	}
	
	// }}}
	// {{{ function _drawMenu()

	/**
	 *  绘制菜单
	 *  
	 * @return {Void}
	 */
	this._drawMenu = function ()
	{
		var _html = [];

		_html.push('<ul class="nav">');
		for (var _key in gMenu) {
			//绘制一级菜单
			_html.push('<li style="font-weight:700;"><a href="javascript:void(0)"');
			_html.push(' class="headitem ' + gMenu[_key]['icons'] + '"');
			_html.push(' onclick="' + __this.__thisName + '.changeMenu(\'' + _key + '\')">');
			_html.push(gMenu[_key]['text'] + '</a>');
			_html.push('<ul id="' + _key + '" class="opened closed" style="display:none">');
			for (var _vkey in gMenu[_key]['sub_categories']) {
				_html.push('<li><a href="javascript:void(0);"');
				_html.push(' onclick="' + __this.__thisName + '.jumpHref(\'' + gMenu[_key]['sub_categories'][_vkey]['q']+ '\')">');
				_html.push(gMenu[_key]['sub_categories'][_vkey]['text']);
				_html.push('</a></li>');
			}
			_html.push('</ul></li>');
		}	
		_html.push('</ul>');

		$("#sidebar").html(_html.join(''));
	}

	// }}}
	// {{{ function jumpHref()
	
	/**
	 *  跳转，只限于父框架内
	 *  
	 * @param {String} qs 跳转的模块，param参数 &sdsd=dsd&dsd
	 * @return {Void}
	 */
	this.jumpHref = function (qs, param)
	{
		var _url = gUrlPrefix + '?q=' + qs;
		
		if ("undefined" !== typeof(param)) {
			_url += param;	
		}

		document.getElementById("mainframe").src = _url;
		
		return false;	
	}

	// }}}
	// {{{ function changeMenu()

	/**
	 *  折叠菜单
	 *  
	 * @param {String}  id
	 * @return {Void}
	 */
	this.changeMenu = function (menuId)
	{
		var menuObj = sW.g(menuId);
		if ('none' == menuObj.style.display) {
			menuObj.style.display = '';	
		} else {
			menuObj.style.display = 'none';	
		}
	}

	// }}}
	// }}}
}
