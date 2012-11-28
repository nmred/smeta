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
* 管理设备列表页
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

function DeviceList() {
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
			$("#help_notice").click(function () {
				if ('none' == sW.g("tips").style.display) {
					sW.g("tips").style.display = '';
					sW.g("help_notice").className = "icons_gray icons_head";	
				} else {
					sW.g("tips").style.display = 'none';
					sW.g("help_notice").className = "icons_gray icons_help";	
				}
			});	
		});	

	}
	
	// }}}
	// {{{ function handle()
	
	this.handle = function (mode)
	{
		if ('' == $.trim(sW.g("device_name").value)) {
			fMessage.show("设备名不能为空.", "failure");	
			return false;
		}
		
		if ('' == $.trim(sW.g("device_host").value)) {
			fMessage.show("设备主机名不能为空.", "failure");	
			return false;
		}
			
		var _formData = $("#form1").serialize();	
		var _url,_data;
		_url = gUrlPrefix + '?q=device_manage.do';
		if ('add' == mode) {	
			_data = _formData + "&action=add_device_do";
		} else {
			_data = _formData + "&action=modify_device_do";
		}

		$.ajax ({
			type: "post",
			url : _url,
			data: _data,
			dataType: "json",
			error: sW.ajaxError,
			success: function (dataRes) {
				// 检查是否登录
				if (false === __this.checkLogin(dataRes)) {
					return false;
				}	

				if (1 == dataRes.res) {
					fMessage.show(dataRes.message, "failure");	
				} else {
					fMessage.show(dataRes.message, "success", 2);	
				}
			}
		});
	}

	// }}}
	// }}}
}
