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

include('public', 'sw_page.js');
include('public', 'sw_boxy.js');

function DeviceList() {
	ModuleBase.call(this);
	var __this = this;

	// {{{ members
	
	this.__detailList = {};

	// }}}
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

			__this._dislayList();
		});	

	}
	
	// }}}
	// {{{ function getDetail()

	this.getDetail = function (deviceId)
	{
		var _html = [];
		var _url = gUrlPrefix + '?q=device_list.do&action=get_detail';
		$.ajax ({
			type: "post",
			url : _url,
			data: 'device_id=' + deviceId,
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
					var _data = dataRes.data;
					var _baseInfoHTML = [];
					__this.__detailList = {base: '基本信息'};
					
					_baseInfoHTML.push('<div id="base"><table class="listtab">');
					_baseInfoHTML.push('<tr><th width="20%">设备名</th><td>' + _data['base']['device_name'] + '</td>');
					_baseInfoHTML.push('<th width="20%">设备显示名</th><td>' + _data['base']['device_display_name'] + '</td></tr>');
					_baseInfoHTML.push('<tr><th>主机名</th><td>' + _data['base']['host'] + '</td>');
					_baseInfoHTML.push('<th>端口</th><td>' + _data['base']['port'] + '</td></tr>');
					_baseInfoHTML.push('<tr><th>获取数据方式</th><td>' + _data['base']['method'] + '</td>');
					_baseInfoHTML.push('<th>SNMP 通信协议</th><td>' + _data['base']['protocol'] + '</td></tr>');
					_baseInfoHTML.push('<tr><th>SNMP 重试次数</th><td>' + _data['base']['snmp_retries'] + '</td>');
					_baseInfoHTML.push('<th>SNMP 超时时间</th><td>' + _data['base']['snmp_timeout'] + '</td></tr>');
					_baseInfoHTML.push('<tr><th>SNMP 版本</th>');
					if ("undefined" != typeof(_data['base']['snmp_community'])) {
						_baseInfoHTML.push('<td>' + _data['base']['version'] + '</td>');
						_baseInfoHTML.push('<th>共同体名</th><td colspan="3">' + _data['base']['snmp_community'] + '</td></tr>');
					} else {
						_baseInfoHTML.push('<td colspan="3">' + _data['base']['version'] + '</td></tr>');
					}
					_baseInfoHTML.push('</table></div>');

					var _authHTML = [];
					if ("undefined" != typeof _data['auth']) {
						_authHTML.push('<div id="auth" style="display:none;"><table class="listtab">');
						_authHTML.push('<tr><th width="20%">用户名</th><td>' + _data['auth']['security_name'] + '</td>');
						_authHTML.push('<th width="20%">安全等级</th><td>' + _data['auth']['security_level'] + '</td></tr>');
						_authHTML.push('<tr><th>公钥协议</th><td>' + _data['auth']['auth_protocol'] + '</td>');
						_authHTML.push('<th>认证公钥</th><td>' + _data['auth']['auth_passphrase'] + '</td></tr>');
						_authHTML.push('<tr><th>密钥协议</th><td>' + _data['auth']['priv_protocol'] + '</td>');
						_authHTML.push('<th>认证密钥</th><td>' + _data['auth']['priv_passphrase'] + '</td></tr>');
						_authHTML.push('</table></div>');
						__this.__detailList['auth'] = '认证信息';
					}

					_html.push('<tr id="detail_info"><td colspan="6">');
					_html.push('<div  class="preview_list"><dl><dt>');
					_html.push('<div style="float:right;margin:10px;">');
					_html.push('<i class="icon-close icons_gray_white" style="cursor: pointer;" onclick="' + __this.__thisName + '.closeDetail()"></i>');
					_html.push('</div>');
					for(var _key in __this.__detailList) {
						_html.push('<div class="ltitle" id="div_' + _key + '" onclick="' + __this.__thisName + '.displayInfo(\'' + _key + '\')">' + __this.__detailList[_key]);
						_html.push('<div class="arrow" id="' + _key + '_arrow" style="display:none;"></div>');
						_html.push('</div>');
					}
					_html.push('</dt><dd>');
					_html.push(_baseInfoHTML.join('') + _authHTML.join(''));
					_html.push('</dd></dl></div></td></tr>');	

					if (null !== g("detail_info")) {
						sW.remove(g("detail_info"));
					}
					$("#tr_" + deviceId).after(_html.join(''));
					g("base_arrow").style.display = '';
					g("div_base").className = 'ltitle current';
				}
			}
		});
		
	}

	// }}}
	// {{{ function displayInfo()

	this.displayInfo = function (labelName)
	{
		for (var _key in __this.__detailList) {
			g(_key).style.display = 'none';			
			g('div_' + _key).className = 'ltitle';
			g(_key + '_arrow').style.display = 'none';
		}

		g(labelName).style.display = '';			
		g('div_' + labelName).className = 'ltitle current';
		g(labelName + '_arrow').style.display = '';
	}

	// }}}
	// {{{ function closeDetail()
	
	this.closeDetail = function ()
	{
		if (null !== g("detail_info")) {
			sW.remove(g("detail_info"));	
		}	
	}
	 
	// }}}
	// {{{ function selectAll()

	this.selectAll = function (chkObj)
	{
		var _objChks = g("list_info").getElementsByTagName('input');
		if (chkObj.checked) {
			for (var _key in _objChks) {
				if ('checkbox' == _objChks[_key].type) {
					_objChks[_key].checked = true;	
				}
			}
		} else {
			for (var _key in _objChks) {
				if ('checkbox' == _objChks[_key].type) {
					_objChks[_key].checked = false;	
				}
			}
		}
	}

	// }}}
	// {{{ function batchDelete()

	this.batchDelete = function ()
	{
		var _objChks = g("list_info").getElementsByTagName('input');
		var _check = [];
		for (var _key in _objChks) {
			if ('checkbox' == _objChks[_key].type && true == _objChks[_key].checked) {
				_check.push(_objChks[_key].value);	
			}
		}

		if (0 == _check.length) {
			fMessage.show('至少选择一个进行批量删除', 'failure');	
			return false;
		}

		__this.deleteDevice(_check.join(','));
	}

	// }}}
	// {{{ function deleteDevice()

	this.deleteDevice = function (deviceId)
	{
		var _url = gUrlPrefix + '?q=device_list.do&action=batch_delete';
		$.ajax ({
			type: "post",
			url : _url,
			data: 'device_id=' + deviceId,
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
					fMessage.show(dataRes.message, 'success', 3);
					__this.fJumpTo('device_list');
				}
			}
		});
	}

	// }}}
	// {{{ function _dislayList()

	this._dislayList = function ()
	{
		Pager.display({
			name:'Pager',
			count: gTotal,
			rowsCount: 10,
			currentPage: gPage,
			url: gUrlPrefix + '?q=device_list&method=ajax',
			appendDom: 'pager',
			getData: function (args) {
				var _html = [];
				var _data = args.data;
				for (var _key in _data) {
					_html.push('<tr id="tr_' + _data[_key]['device_id'] + '"><td style="text-align:center;">');
					_html.push('<input type="checkbox" id="chk_' + _data[_key]['device_id'] + '" value="' + _data[_key]['device_id'] + '" /></td>');
					_html.push('<td>' + _data[_key]['device_display_name'] + '</td>');	
					_html.push('<td>' + _data[_key]['host'] + '</td>');	
					_html.push('<td>' + _data[_key]['version'] + '</td>');	
					_html.push('<td>' + _data[_key]['protocol'] + '</td>');	
					_html.push('<td style="text-align:center;">');	
					_html.push('<a href="javascript:void(0);" onclick="' + __this.__thisName + '.getDetail(' + _data[_key]['device_id'] + ')" class="btn" >');
					_html.push('<i class="icon-zoom-in icons_gray_white" style="margin-right:3px;"></i>详细</a>');
					_html.push('<a class="btn" href="' + gUrlPrefix + '?q=device_manage.do&action=modify&device_id=' + _data[_key]['device_id'] + '">');
					_html.push('<i class="icons-edit icons_gray_white" style="margin-right:3px;"></i>修改</a>');
					_html.push('<a class="btn" href="javascript:void(0);" onclick="' + __this.__thisName + '.deleteDevice(' + _data[_key]['device_id'] + ')">');
					_html.push('<i class="icons-trash icons_gray_white" style="margin-right:3px;"></i>删除</a>');
					_html.push('</td></tr>');
				}
				
				sW.empty(g("list_info"));
				g("list_info").innerHTML = _html.join('');
			}
		});
	}

	// }}}
	// }}}
}
