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
			console.info(gMenu);
			$("#sidebar").mouseover(function() {
				$("#sidebar").css('overflow', 'auto');	
			});						
			$("#sidebar").mouseout(function() {
				$("#sidebar").css('overflow', 'hidden');	
			});						

			__this.drawMenu();
		});	
	}
	
	// }}}
	// {{{ function drawMenu()

	this.drawMenu = function ()
	{
		var _html = [];

		for (var _key in gMenu) {
			//绘制一级菜单
			_html.push('</hr><h3>' + gMenu[_key]['text'] + '</h3><ul class="toggle">');
			for (var _vkey in gMenu[_key]['sub_categories']) {
				_html.push('<li class="icn_new_article"><a href="javascript:void(0)"');
				_html.push(' onclick="' + __this.__thisName +'.jumpHref(\'' + gMenu[_key]['sub_categories'][_vkey]['q']+ '\')">');
				_html.push(gMenu[_key]['sub_categories'][_vkey]['text']);
				_html.push('</a></li>');
			}
			_html.push('</ul>');
		}	

		$("#menu").html(_html.join(''));
	}

	// }}}
	// {{{ function jumpHref()
	
	this.jumpHref = function (qs, param)
	{
		var _url = gUrlPrefix + '?q=' + qs;
		
		console.info(_url);
		if ("undefined" !== typeof(param)) {
			_url += param;	
		}

		document.getElementById("mainframe").src = _url;
		
		return false;	
	}

	// }}}
	// }}}
}
