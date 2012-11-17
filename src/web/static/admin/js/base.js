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
	
	/**
	 * 初始化  
	 */
	this.init = function()
	{
		$(document).ready(function() {
			console.info("debug");
			$("#sidebar").mouseover(function() {
				$("#sidebar").css('overflow', 'auto');	
			});						
			$("#sidebar").mouseout(function() {
				$("#sidebar").css('overflow', 'hidden');	
			});						
		});	
	}

	// }}}
}
