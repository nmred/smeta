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
* 共用函数库
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
	// }}}
};

// {{{ Object

var sW = new sw();

// }}}

// {{{ functions

//DE()的别名
var D = sW.DE;

// }}}
