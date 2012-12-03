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
* 管理设备页
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

include('public', 'sw_boxy.js');

function DeviceManage() {
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
			fBindBoxy({
				data: {
					// {{{
					'device_name': '设备显示名称',	
					'device_host': '设备的主机名例如 localhost',
					'device_port': '设备的端口号默认是  SNMP 的默认端口：161',
					'version_one': 'SNMP 协议的版本 : VERSION_1',
					'version_two': 'SNMP 协议的版本 : VERSION_2',
					'version_three': 'SNMP 协议的版本 : VERSION_3',
					'method_exec': '通过 SNMP 获取数据的方式:EXEC 方式即直接是系统调用',
					'method_ext':'通过 SNMP 获取数据的方式:EXT 方式( PHP 的内置扩展，需要有扩展支持)',
					'protocol_net' : 'SNMP 的通讯协议 : NET 方式',
					'protocol_udp' : 'SNMP 的通讯协议 : UDP 方式',
					'timeout': 'SNMP 连接超时时间，单位是微秒，默认是 5s',
					'retries': 'SNMP 连接失败后重试次数，默认 3 次',
					'security_name' : '认证的用户名',
					'no_auth' : '认证的安全等级: noAuthNoPriv',
					'auth_no_priv': '认证的安全等级: authNoPriv',
					'auth_priv': '认证的安全等级: authPriv',
					'auth_md5': '认证的协议算法: MD5',
					'auth_sha': '认证的协议算法: SHA',
					'auth_passphrase' : '认证的公钥',
					'priv_des': '密钥的协议算法: DES',
					'priv_aes': '密钥的协议算法: AES',
					'priv_passphrase': '认证密钥',
					'snmp_community': 'SNMP 的共同体，只有 V1 和 V2 版本有效，默认 public',
					// }}}
				},
				alias: {
					// {{{
					'device_host': 'host_port',	
					'device_port': 'host_port',	
					'version_one': 'snmp_version',
					'version_two': 'snmp_version',
					'version_three': 'snmp_version',
					'method_exec': 'snmp_method',
					'method_ext': 'snmp_method',
					'protocol_net': 'snmp_protocol',
					'protocol_udp': 'snmp_protocol',
					'timeout': 'timeout_alias',
					'retries': 'retries_alias',
					'no_auth' : 'security_level',
					'auth_no_priv': 'security_level',
					'auth_priv': 'security_level',
					'auth_md5': 'auth_protocol',
					'auth_sha': 'auth_protocol',
					'priv_des': 'priv_protocol',
					'priv_aes': 'priv_protocol',
					// }}}
				}
			});

			$("#version_one").click(function () {
				__this._clearInput();
				sW.g("version_auth").style.display = "none";	
				sW.g("version_community").style.display = "";	
			});

			$("#version_two").click(function () {
				__this._clearInput();
				sW.g("version_auth").style.display = "none";	
				sW.g("version_community").style.display = "";	
			});

			$("#version_three").click(function () {
				__this._clearInput();
				sW.g("version_auth").style.display = "";	
				sW.g("version_community").style.display = "none";	
			});

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
		var _url;
		_url = gUrlPrefix + '?q=device_manage.do';
		if ('add' == mode) {	
			_url  += "&action=add_device_do";
		} else {
			_url  += "&action=modify_do";
		}

		$.ajax ({
			type: "post",
			url : _url,
			data: _formData,
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
	// {{{ function _clearInput()

	/**
	 * 当切换版本的时候清空表单值
	 *
	 * @return {Void}
	 */
	this._clearInput = function ()
	{
		g("security_name").value = '';
		g("no_auth").checked = true;
		g("auth_md5").checked = true;
		g("auth_passphrase").value = '';
		g("priv_des").checked = true;
		g("priv_passphrase").value = '';
		g("snmp_community").value = "";	
	}

	// }}}
	// }}}
}
