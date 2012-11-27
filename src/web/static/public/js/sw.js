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
	// {{{ function D()
	
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
	// }}}
};

// {{{ Object

var sW = new sw();

// }}}

// {{{ functions

//DE()的别名
var D = sW.DE;

// }}}
