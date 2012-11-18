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
* url控制器
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

function UrlControl() {
	ModuleBase.call(this);
	var __this = this;

	// {{{ members

	/**
	 * URL的网络协议 
	 *
	 * type {String}
	 */
	this.urlProtocol = "";

	/**
	 * URL的主机名 
	 *
	 * type {String}
	 */
	this.urlHostname = "";

	/**
	 * URL的端口 
	 *
	 * type {String}
	 */
	this.urlPort = "";

	/**
	 * URL的路劲 
	 *
	 * type {String}
	 */
	this.urlPath = "";

	/**
	 * URL的锚点 
	 *
	 * type {String}
	 */
	this.urlAnchor = "";

	/**
	 * URL的参数 
	 *
	 * type {Array}
	 */
	this.urlParams = {};

	/**
	 * URL的参数格式化
	 *
	 * type {Array}
	 */
	this.paramsFormat = {};

	/**
	 * URL的参数数据KEY 
	 *
	 * type {String}
	 */
	this.paramsKeyData = "_data";

	// }}}
	// {{{ functions
	// function setUrl()

	/**
	 * 初始化url  
	 * 
	 * @params {String} url
	 * @return {Void}
	 */
	this.setUrl = function (url)
	{
		__this._parseUrl(url);
	}

	// }}}
	// {{{ function setParamsFormat()
	
	/**
	 * 格式化参数  
	 * 
	 * @params {Mixed} params
	 * @return {Boolean}
	 */
	this.setParamsFormat = function (params)
	{
		if ("object" !== typeof(params)) {
			return false;	
		}

		__this.paramsFormat = params;
		return true;
	}

	// }}}
	// {{{ function _parseUrl()

	/**
	 * 解析URL  
	 * 
	 * @params {String} url
	 * @return {Void}
	 */
	this._parseUrl = function (url)
	{
		url = url + "";
		var _preUrl, _strParam, _protocol, _hostname, _port, _urlPath, _params;

		//第一部分是基本url，第二部分是get参数，第三部分是锚点
		var _patternUrl = /^([^?#]*)\??([^#]*)(#?.*)$/;
		var _arrMatches = url.match(_patternUrl);

		_preUrl   = ("undefined" === typeof(_arrMatches[1])) ? "" : _arrMatches[1];
		_strParam = ("undefined" === typeof(_arrMatches[2])) ? "" : _arrMatches[2];
		_anchor   = ("undefined" === typeof(_arrMatches[3])) ? "" : _arrMatches[3];

		var _patternPreUrl = /^((\w+):\/\/(([\w.-]+)|(\[.+\]))(:(\d+))?)?(.*)$/;
		var _arrPreUrl = _preUrl.match(_patternPreUrl);
		//协议
		var _protocol = ("undefined" === typeof(_arrPreUrl[2])) ? "" : _arrPreUrl[2];
		//主机名
		var _hostname = ("undefined" === typeof(_arrPreUrl[3])) ? "" : _arrPreUrl[3];
		//端口
		var _port = ("undefined" === typeof(_arrPreUrl[7])) ? "" : _arrPreUrl[7];
		//路径
		var _urlPath = ("undefined" === typeof(_arrPreUrl[8])) ? "" : _arrPreUrl[8];
		
		_params = {};

		if ("" !== _strParam) {
			var _arrTmp = _strParam.split("&");
			for (var i = 0; i < _arrTmp.length; i++) {
				if ("" === _arrTmp[i]) {
					continue;	
				}

				var _key, _val;
				var _posEqual = _arrTmp[i].indexOf("=");
				if (-1 === _posEqual) {
					_key = _arrTmp[i];
					_val = "";
				} else {
					_key = _arrTmp[i].substr(0, _posEqual);
					_val = _arrTmp[i].substr(_posEqual + 1);	
				}

				if ("" === _key) {
					continue;	
				}

				_params[_key] = _val;
			}	
		}

		__this.urlProtocol = _protocol;
		__this.urlHostname = _hostname;
		__this.urlPort     = _port;
		__this.urlPath     = _urlPath;
		__this.urlAnchor   = _anchor;
		__this.urlParams   = _params;
	}

	// }}}
	// {{{ function getPath()

	/**
	 * 获取url的路径  
	 * 
	 * @return {String}
	 */
	this.getPath = function ()
	{
		return __this.urlPath;	
	}

	// }}}
	// {{{ function getAnchor()

	/**
	 * 获取锚点  
	 * 
	 * @return {String}
	 */
	this.getAnchor = function ()
	{
		return __this.urlAnchor;	
	}

	// }}}
	// {{{ function modAnchor()

	/**
	 * 修改锚点  
	 * 
	 * @param {String} anchor
	 * @return {Void}
	 */
	this.modAnchor = function (anchor)
	{
		return __this.urlAnchor	= anchor;
	}

	// }}}
	// {{{ function addParam()

	/**
	 * 添加参数  
	 * 
	 * @param {String} key 参数名称
	 * @param {String} value 参数值
	 * @return {Boolean}
	 */
	this.addParam = function (key, value)
	{
		key = key + "";
		value = value + "";
		if ("undefined" !== typeof(this.urlParams[key])) {
			return false;	
		}

		this.urlParams[key] = value;
		return true;
	}

	// }}}
	// {{{ function addReplaceParam()

	/**
	 * 强制(替换)添加参数  
	 * 
	 * @param {String} key 参数名称
	 * @param {String} value 参数值
	 * @return {Boolean}
	 */
	this.addReplaceParam = function (key, value)
	{
		__this.urlParams[key] = value;
		return true;	
	}

	// }}}
	// {{{ function replaceParam()

	/**
	 * 替换参数  
	 * 
	 * @param {String} key 参数名称
	 * @param {String} value 参数值
	 * @return {Boolean}
	 */
	this.replaceParam = function (key, value)
	{
		key = key + "";
		value = value + "";
		if ("undefined" === typeof(this.urlParams[key])) {
			return false;	
		}

		this.urlParams[key] = value;
		return true;
	}

	// }}}
	// {{{ function getParam()

	/**
	 * 获取参数
	 * 
	 * @param {String} key 参数名称
	 * @return {Boolean | String}
	 */
	this.getParam = function (key)
	{
		if ("undefined" === typeof(__this.urlParams[key])) {
			return false;	
		}

		return __this.urlParams[key];
	}

	// }}}
	// {{{ function delParam()

	/**
	 * 删除参数
	 * 
	 * @param {String} key 参数名称
	 * @return {Boolean}
	 */
	this.delParam = function (key)
	{
		if ("undefined" === typeof(__this.urlParams[key])) {
			return false;	
		}	

		delete(__this.urlParams[key]);
		return true;
	}

	// }}}
	// {{{ function addParamData()

	/**
	 * 添加参数  
	 * 
	 * @param {String} value 参数值
	 * @return {Boolean}
	 */
	this.addParamData = function (value)
	{
		return __this.addParam(__this.paramsKeyData, value);
	}

	// }}}
	// {{{ function repalceParamData()

	/**
	 * 替换参数  
	 * 
	 * @param {String} value 参数值
	 * @return {Boolean}
	 */
	this.repalceParamData = function (value)
	{
		return __this.replaceParam(__this.paramsKeyData, value);	
	}

	// }}}
	// {{{ function addReplaceParamData()

	/**
	 * 强制(替换)添加参数  
	 * 
	 * @param {String} value 参数值
	 * @return {Boolean}
	 */
	this.addReplaceParamData = function (value)
	{
		return __this.addReplaceParam(__this.paramsKeyData, value);	
	}

	// }}}
	// {{{ function getParamData()

	/**
	 * 获取参数
	 * 
	 * @return {Boolean | String}
	 */
	this.getParamData = function ()
	{
		return __this.getParam(__this.paramsKeyData);	
	}

	// }}}
	// {{{ function delParamData()

	/**
	 * 删除参数
	 * 
	 * @return {Boolean}
	 */
	this.delParamData = function ()
	{
		return 	__this.delParam(__this.paramsKeyData);
	}

	// }}}
	// {{{ function getUrl()

	/**
	 * 获取URL地址
	 * 
	 * @return {String}
	 */
	this.getUrl = function ()
	{
		var _url = '';
		_url = ("" === __this.urlProtocol) ? "" : (__this.urlProtocol + '://');
		_url += __this.urlHostname;
		_url += ("" === __this.urlPort) ? "" : (':' + __this.urlPort);
		_url += ("" === __this.getQs()) ? "" : ('?' + __this.getQs());
		_url += __this.urlAnchor;
		
		return _url;
	}

	// }}}
	// {{{ function getFormatUrl()

	/**
	 * 获取格式化后URL地址
	 * 
	 * @return {String}
	 */
	this.getFormatUrl = function ()
	{
		var _url = '';
		_url = ("" === __this.urlProtocol) ? "" : (__this.urlProtocol + '://');
		_url += __this.urlHostname;
		_url += ("" === __this.urlPort) ? "" : (':' + __this.urlPort);
		_url += ("" === __this.getFormatQs()) ? "" : ('?' + __this.getFormatQs());
		_url += __this.urlAnchor;
		
		return _url;
	}

	// }}}
	// {{{ function getQs()

	/**
	 * 获取GET参数字符串
	 * 
	 * @param {Array|Object|String} params 需要排除的参数
	 * @return {String}
	 */
	this.getQs = function (params)
	{
		switch (typeof(params)) {
			case "undefined":
				params = [];
				break;
			case "object":
				break;
			default:
				params = [params + ""];
				break;	
		}

		var _paramLen = params.length;
		_paramLen = ("undefined" === typeof(_paramLen)) ? 0 : _paramLen;

		var _tmpParams = {};
		for (var _key in __this.urlParams) {
			_tmpParams[_key] = __this.urlParams[_key];
		}

		//排除需要排除的参数
		if (0 < _paramLen) {
			for (var i = 0; i < _paramLen; i++) {
				delete _tmpParams[params[i]];	
			}	
		}

		var _queryArr = [];
		var _key = 0;
		for (var i in _tmpParams) {
			_queryArr[_key] = i + "=" + _tmpParams[i];
			_key++;
		}

		return _queryArr.join('&');
	}

	// }}}
	// {{{ function getFormatQs()

	/**
	 * 获取格式化GET参数字符串
	 * 
	 * 具体的格式化方法是加入must和may的参数
	 * 在__this.paramsFormat.must和__this.paramsFormat.may对象中定义
	 * 
	 * @param {Array|Object|String} params 需要排除的参数
	 * @return {String}
	 */
	this.getFormatUrl = function (params)
	{
		switch (typeof(params)) {
			case "undefined":
				params = [];
				break;
			case "object":
				break;
			default:
				params = [params + ""];
				break;	
		}

		var _paramLen = params.length;
		_paramLen = ("undefined" === typeof(_paramLen)) ? 0 : _paramLen;

		var _tmpParams = {};
		for (var _key in __this.urlParams) {
			_tmpParams[_key] = __this.urlParams[_key];
		}

		//排除需要排除的参数
		if (0 < _paramLen) {
			for (var i = 0; i < _paramLen; i++) {
				delete _tmpParams[params[i]];	
			}	
		}

		var _queryArr = [];
		var _key = 0;
		var _mustParams = __this.paramsFormat.must;
		if ("object" == typeof(_mustParams)) {
			for (var i in _mustParams) {
				if ("undefined" !== typeof(_tmpParams[i])) {
					_queryArr[_key] = i + "=" + _tmpParams[i];
					delete _tmpParams[i];	
				} else {
					_queryArr[_key] = i + "=" + _mustParams[i];
				}
				_key++;
			}
		}

		var _mayParams = __this.paramsFormat.may;
		if ("object" == typeof(_mayParams)) {
			for (var i in _mayParams) {
				if ("undefined" !== typeof(_tmpParams[i])) {
					_queryArr[_key] = i + "=" + _tmpParams[i];
					delete _tmpParams[i];	
				} else {
					_queryArr[_key] = i + "=" + _mayParams[i];
				}
				_key++;
			}
		}
		return _queryArr.join('&');
	}

	// }}}
	// }}}
}
