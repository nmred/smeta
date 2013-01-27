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
	// {{{ function trim()

	/**
	 * 去除两边空格
	 * 
	 * @param {String} 字符串
	 * @return {String} text
	 */
	this.trim = function (text)
	{	

		text = (null == text) ? '' 
			   :
			   text.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
		
		return text;
	}
	
	// }}}	
	// {{{ function show()

	/**
	 * 显示一个元素
	 * 
	 * @param {String|ele} 一个元素或一个元素的ID
	 * @return {Void}
	 */
	this.show = function (ele)
	{	
		if ("undefined" == typeof(ele)) {
			return false;	
		}

		if ("string" == typeof(ele)) {
			ele = __this.g(ele);	
		}

		if (null !== ele) {
			ele.style.display = "";
		}

		return true;
	}
	
	// }}}	
	// {{{ function hide()

	/**
	 * 隐藏一个元素
	 * 
	 * @param {String|ele} 一个元素或一个元素的ID
	 * @return {Void}
	 */
	this.hide = function (ele)
	{	
		if ("undefined" == typeof(ele)) {
			return false;	
		}

		if ("string" == typeof(ele)) {
			ele = __this.g(ele);	
		}

		if (null !== ele) {
			ele.style.display = "none";
		}

		return true;
	}
	
	// }}}	
	// {{{ function opacity()

	/**
	 * 一个元素透明度操作
	 * 
	 * @param {String|ele} 一个元素或一个元素的ID
	 * @param {String|null} 透明度设置，没有设置返回当前的透明度值
	 * @return {Void}
	 */
	this.opacity = function (ele, opacity)
	{	
		var isIe = !!document.all;

		if ("string" == typeof(ele)) {
			ele = __this.g(ele);	
		}
		
		if ("" === opacity) {
			isIe ? ele.style.filter = "" : ele.style.opacity = "";	
		} else if (null != opacity) {
			isIe ? ele.style.filter = "alpha(opacity=" + opacity * 100 + ")" : ele.style.opacity = opacity;	
		}
		
		if (isIe) {
			/alpha\(opacity=([0-9.]+)\)/.test(ele.style.filter);
			opacity = parseFloat(RegExp.$1) / 100;	
		} else {
			opacity = parseFloat(ele.style.opacity);
		}

		return opacity;
	}
	
	// }}}	
	// {{{ function recursionElement()

	/**
	 * 递归的操作一个元素及其子节点
	 * 
	 * @param {String|ele} 一个元素或一个元素的ID
	 * @param {fn} 处理函数 当该函数返回true则停止继续执行
	 * @return {Void}
	 */
	this.recursionElement = function (ele, fn)
	{	
		if ("string" == typeof(ele)) {
			ele = __this.g(ele);	
		}

		if (!fn(ele)) {
			for (var c = ele.firstChild; c; c = c.nextSibling) {
				if (1 == c.nodeType) {
					__this.recursionElement(c, fn);	
				}
			}	
		}
	}
	
	// }}}	
	// {{{ function isArray()

	/**
	 * 判断是否是数组
	 * 
	 * @param {Mixed} arr 
	 * @return {Void}
	 */
	this.isArray = function (arr)
	{	
		if (Function.isArray) {
			return isArray(arr);	
		}

		return ("object" === typeof(arr)) && ("[object Array]" === Object.prototype.toString.call(arr));
	}
	
	// }}}	
	// {{{ function isArrayLike()

	/**
	 * 判断是否是类数组
	 * 
	 * @param {Mixed} arr 
	 * @return {Void}
	 */
	this.isArrayLike = function (arr)
	{	
		if (arr
			&& typeof(arr) == "object"
			&& isFinite(arr.length)
			&& arr.length >= 0
			&& arr.length == Math.floor(arr.length)
			&& arr.length < 4294967296) {
			return true;	
		}

		return false;
	}
	
	// }}}	
	// {{{ function slice()

	/**
	 * 截取数组
	 * 
	 * @param {Array} arr 待截取的数组
	 * @param {Int} start
	 * @return {Array}
	 */
	this.slice = function (arr, start)
	{	
		if (start == null) {
			start = 0;	
		}

		var desArr = [],i,j;

		try { 
			desArr = Array.prototype.slice.call(arr, start);
		} catch (e) { //伪数组
			desArr = Array(arr.length - start);
			for (i = start, j = 0; i < arr.length; ++i, ++j) {
				desArr[j] = arr[i];	
			}
		}

		return desArr;
	}
	
	// }}}	
	// {{{ function merge()

	/**
	 * 合并对象或数组，如果是对象第二个中有key在第一中存在则覆盖
	 * 
	 * @param {Array|Object} arr1
	 * @param {Array|Object} arr2
	 * @return {Array|Object}
	 */
	this.merge = function (arr1, arr2)
	{	
		if ((__this.isArray(arr1) || __this.isArrayLike(arr1))
			&& (__this.isArray(arr2) || __this.isArrayLike(arr2))) {
			var tmp = [];
			for (var i = 0, c = arr1.length; i < c; i++) {
				tmp.push(arr1[i]);	
			}

			for (var i = 0, c = arr2.length; i < c; i++) {
				tmp.push(arr2[i]);
			}
		} else {	
			var tmp = {};
			for (var i in arr1) {
				tmp[i] = arr1[i];
			}

			for (var i in arr2) {
				tmp[i] = arr2[i];
			}
		}

		return tmp;
	}
	
	// }}}	
	// {{{ function callObjFn()

	/**
	 * 返回一个对象的某个方法
	 * 
	 * @param {fn} fn
	 * @param {Object|ele} ele 
	 * @return {fn}
	 */
	this.callObjFn = function (fn, ele)
	{	
		var args = __this.slice(arguments, 2);

		if ("undefined" == typeof(ele)) {
			return function () {
				return fn.apply(this, args.concat(__this.slice(arguments)));	
			}
		} else {
			return function () {
				return fn.apply(ele, args.concat(__this.slice(arguments)));	
			}
		}
	}
	
	// }}}	
	// {{{ function createElement()

	/**
	 * 创建一个结点
	 * 
	 * @param {String} tagName
	 * @param {Object} params  
	 * @return {ele}
	 */
	this.createElement = function (tagName, param)
	{	
		var ele = null;
		if (!!document.createElementNS) {
			ele = document.createElementNS("http://www.w3.org/1999/xhtml", tagName);	
		} else {
			ele = document.createElement(tagName);	
		}

		if (null != ele && "undefined" == typeof(params)) {
			for (var _key in param) {
				ele.setAttribute(_key, param[_key]);			
			}
		}

		return ele;
	}
	
	// }}}	
	// {{{ function addClass()

	/**
	 * 为一个节点添加class样式
	 * 
	 * @param {String|ele} ele
	 * @param {string} classes 
	 * @return {ele}
	 */
	this.addClass = function (ele, classes)
	{	
		if ("undefined" == typeof(ele)) {
			return false;	
		}

		if ("string" == typeof(ele)) {
			ele = __this.g(ele);	
		}

		var classArr = __this.trim(ele.className).split(/\x20/);
		var addClass = __this.trim(classes).split(/\x20/);

		for (var i = 0, c = addClass.length; i < c; i++) {
			if (-1 == classArr.indexOf(addClass[i])) {
				classArr.push(addClass[i]);	
			}
		}

		ele.className = classArr.join(' ');

		return this;
	}
	
	// }}}	
	// {{{ function removeClass()

	/**
	 * 为一个节点移除class样式
	 * 
	 * @param {String|ele} ele
	 * @param {string} classes 
	 * @return {ele}
	 */
	this.removeClass = function (ele, classes)
	{	
		if ("undefined" == typeof(ele)) {
			return false;	
		}

		if ("string" == typeof(ele)) {
			ele = __this.g(ele);	
		}

		var classArr = __this.trim(ele.className).split(/\x20/);
		var removeClass = __this.trim(classes).split(/\x20/);
		var tmp = [];

		for (var i = 0, c = classArr.length; i < c; i++) {
			if (-1 == removeClass.indexOf(classArr[i])) {
				tmp.push(classArr[i]);	
			}
		}

		ele.className = tmp.join(' ');

		return this;
	}
	
	// }}}	
	// {{{ function stopBubble()

	/**
	 * 阻止冒泡
	 * 
	 * @param {event} event
	 * @return {Void}
	 */
	this.stopBubble = function (event)
	{	
		var event = event || window.event;

		if (window.event) {
			event.cancelBubble = true;
			event.returnValue = false;
		} else {
			event.preventDefault();
			event.stopPropagation();	
		}
	}
	
	// }}}	
	// {{{ function bind()

	/**
	 * 绑定事件
	 * 
	 * @param {String|ele} ele
	 * @param {event} ev
	 * @param {fn} fn
	 * @param {Boolean} useCapture
	 * @return {Void}
	 */
	this.bind = function (ele, ev, fn, useCapture)
	{	
		var bindHander;
		if ("undefined" == typeof(ele)) {
			return false;	
		}

		if ("undefined" == typeof(useCapture)) {
			useCapture = false;	
		}

		if ("string" == typeof(ele)) {
			ele = __this.g(ele);	
		}

		//缓存bind绑定的函数
		gBindFn.push({'dom': ele, 'ev': ev, 'fn': fn});

		if (ele.addEventListener) {
			ele.addEventListener(ev, fn, useCapture);	
		} else {
			ele.attachEvent("on" + ev, fn);	
		}
	}
	
	// }}}	
	// {{{ function unbind()

	/**
	 * 去除绑定事件
	 * 
	 * @param {String|ele} ele
	 * @param {event} ev
	 * @param {fn} fn
	 * @param {Boolean} useCapture
	 * @return {Void}
	 */
	this.unbind = function (ele, ev, fn, useCapture)
	{	
		if ("undefined" == typeof(ele)) {
			return false;	
		}

		if ("undefined" == typeof(useCapture)) {
			useCapture = false;	
		}

		if ("string" == typeof(ele)) {
			ele = __this.g(ele);	
		}

		for (var _key in gBindFn) {
			if (ele !== gBindFn[_key]['dom'] 
				|| ev !== gBindFn[_key]['ev']
				|| ("undefined" !== typeof(fn) && fn !== gBindFn[_key]['fn'])) {
				continue;	
			}

			if (ele.removeEventListener) {
				ele.removeEventListener(ev, gBindFn[_key]['fn'], useCapture);	
			} else {
				ele.detachEvent("on" + ev, gBindFn[_key]['fn']);	
			}
		}
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

/**
 * 绑定匿名函数名称
 * 
 */
var gBindFn = [];

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
