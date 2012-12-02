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
* ajax分页
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

function Page()
{
	var __this = this;

	// {{{ members

	this.__count = 0;

	this.__rowsCount = 20;

	this.__url = '';

	this.__currentPage = 1;

	this.__displayNum = 10;

	this.__appendDom = window;

	// }}}
	// {{{ functions
	// {{{ function display()

	this.display = function (args)
	{
		if ("undefined" !== args[])	
	}

	// }}}
	// {{{ function _drawHTML()

	this._drawHTML = function ()
	{
		var _html = [];
		var _prefixUrl = __this.__url + '&page=';
		_html.push('<div class="pagination">');
		_html.push('<a class="prev_page" href="');
		_html.push(_prefixUrl + __this._getPrevPage() + '&rows_count=' + __this.__rowsCount);
		_html.push('">« 上一页</a>');

		for (var i = 1; i <= Math.min(__this._getTotal(), __this.__displayNum); i++) {
			_html.push('<a href="' + _prefixUrl + i + '&rows_count=' + __this.__rowsCount + '"');
			if (i == __this.__currentPage) {
				_html.push(' class="current" ');	
			}
			_html.push('>' + i + '</a>');	
		}	

		if (__this._getTotal() > __this.__displayNum) {
			_html.push('<span>....</span>');	
		}

		_html.push('<a class="next_page" href="');
		_html.push(_prefixUrl + __this._getNextPage() + '&rows_count=' + __this.__rowsCount);
		_html.push('">下一页 »</a></div>');
		
		if ("undefined" != typeof(__this.__appendDom)) {
			$(__this.__appendDom).append(_html.join(''));	
		}
	}

	// }}}
	// {{{ function _getNextPage()

	this._getNextPage = function ()
	{
		return Math.max(1, min(__this.__currentPage + 1, __this._getTotal()));	
	}

	// }}}
	// {{{ function _getPrevPage()

	this._getPrevPage = function ()
	{
		return Math.max(1, __this.__currentPage - 1);	
	}

	// }}}
	// {{{ function _getTotal()

	this._getTotal = function ()
	{
		return Math.ceil(__this.__count / __this.__rowsCount);		
	}

	// }}}
	// }}}	
}

