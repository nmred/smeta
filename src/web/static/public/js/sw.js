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
* JS主框架,任何页面都必须加载本JS
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

function sw (){
	var __this = this;

	// {{{ members

	/**
	 * 是否开启DEBUG功能  
	 *  
	 */
	this.__isDebug = true;

	/**
	 * 允许自动加载的模块 
	 *  
	 */
	this.__allowIncludeModule = {public:true,admin:true,user:true};

	// }}}
	// {{{ functions
	// {{{ function g()

	/**
	 * 通过id获取结点  
	 * 
	 * @param {String} id ID属性
	 * @param {Object} dom dom结点
	 * @return {DOM}
	 */
	this.g = function (id, dom)
	{
		var obj;
		if ("undefined" !== typeof(dom)) {
			obj = dom.getElementById(id);
		} else {
			obj = document.getElementById(id);	
		}
		return 	obj;
	}

	// }}}
	// {{{ function DE()
	
	/**
	 * 调试专用  
	 * 
	 * @param {Mixed} 需要输出的信息
	 * @return {Void}
	 */
	this.DE = function (debug)
	{
		if (true === __this.__isDebug) {
			console.info(debug);
		}
	}

	// }}}
	// {{{ function getPosition()
	
	/**
	 * 获取元素的绝对位置
	 * 
	 * @return {Object} [left, top]
	 */
	this.getPosition = function (ele)
	{
		if (null === ele || "undefined" == typeof(ele)) {
			return false;	
		}
		
		var _top = ele.offsetTop;
		var _left = ele.offsetLeft;
		var _width = ele.offsetWidth;
		var _height = ele.offsetHeight;

		while(ele = ele.offsetParent) {
			_top  += ele.offsetTop;
			_left += ele.offsetLeft;		
		}

		return {top:_top, left:_left, width:_width, height:_height, bottom:(_top + _height), right: (_left + _width)};
	}

	// }}}
	// {{{ function remove()
	
	/**
	 * 删除一个dom元素 
	 * 
	 * @param {Dom} 需要删除的元素
	 * @return {Void}
	 */
	this.remove = function (ele)
	{
		if (null === ele || "undefined" == typeof(ele)) {
			return false;	
		}
		
		ele.parentNode.removeChild(ele);
	}

	// }}}
	// {{{ function empty()
	
	/**
	 * 清除一个dom元素 
	 * 
	 * @param {Dom} 需要清除的元素
	 * @return {Void}
	 */
	this.empty = function (ele)
	{
		if (null === ele || "undefined" == typeof(ele)) {
			return false;	
		}
		
		ele.innerHTML = '';
	}

	// }}}
	// {{{ function ajaxError 

    /**
     * ajax error
     *
     * @returns {Void}
     */
    this.ajaxError = function() {
        fMessage.show('the system error,please try agian later', 'failure');
    };

	// }}}
	// {{{ function include()

	/**
	 * 动态加载js文件
	 * 
	 * @param {String} 加载的模块名
	 * @param {String} 加载的文件名
	 * @return {Void}
	 */
	this.include = function (module, filename)
	{	
		if ("undefined" == typeof(__this.__allowIncludeModule[module])) {
			return false;	
		}

		//判断是否已经加载
		if ("undefined" != typeof(gIncludes[module][filename])) {
			return false;	
		}

		var _html = [];
		var info = document.getElementById('js_sw_base').src.split('/');
		var version = info.pop().split('v=')[1];
		info.pop();
		info.pop();
		var _prefixPath = info.join('/') + '/';	
		
		_html.push('<script type="text/javascript" src="');
		_html.push(_prefixPath + module + '/js/');
		_html.push(filename + '?v=' + version);
		_html.push('" ></script>');
		$('head').append(_html.join(''));	

		//添加MAP表
		gIncludes[module][filename] = true;
	}
	
	// }}}	
	// }}}
};
// {{{ Vars

/**
 * 动态加载文件管理MAP表
 * 
 */
var gIncludes = {public:{},admin:{},user:{}};

// }}}
// {{{ Object

var sW = new sw();

// }}}
// {{{ Functions


/**
 * DE的别名 用sW.DE()也可以调用调试工具 
 */
var D = sW.DE;

/**
 * sW.include()的别名
 */
var include = sW.include;

/**
 * sW.g()的别名
 */
var g = sW.g;

// }}}
