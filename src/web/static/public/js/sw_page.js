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

	/**
	 * 实例化对象的名称
	 * 
	 * type {String}
	 */
	this.__thisName = '';

	/**
	 * 条目的总数
	 * 
	 * type {Int}
	 */
	this.__count = 0;

	/**
	 * 每页显示条数
	 * 
	 * type {Int}
	 */
	this.__rowsCount = 20;

	/**
	 * 请求url
	 * 
	 * type {String}
	 */
	this.__url = '';

	/**
	 * 当前页码数
	 * 
	 * type {Int}
	 */
	this.__currentPage = 1;

	/**
	 * 分页页码显示个数
	 * 
	 * type {Int}
	 */
	this.__displayNum = 10;

	/**
	 * 分页添加的结点
	 * 
	 * type {Dom}
	 */
	this.__appendDom = window;

	/**
	 * 回调函数
	 * 
	 * type {Function}
	 */
	this.__getData = null;

	/**
	 * ajax POST请求的data
	 * 
	 * type {String}
	 */
	this.__data = null;

	// }}}
	// {{{ functions
	// {{{ function display()

	/**
	 * 类的主入口程序
	 *
	 * @param {Object} args
	 * @return {Void}  
	 */
	this.display = function (args)
	{
		//设置对象的名称，非常重要主要用于翻译事件调用处理
		if ("undefined" !== typeof(args['name'])) {
			__this.__thisName = args['name'];
		}

		//条目总计个数
		if ("undefined" !== typeof(args['count'])) {
			__this.__count = args['count'];
		}

		//每页的条数
		if ("undefined" !== typeof(args['rowsCount'])) {
			__this.__rowsCount = args['rowsCount'];
		}

		//当前页码数
		if ("undefined" !== typeof(args['currentPage'])) {
			if (args['currentPage'] >  __this._getTotal()) {
				__this.__currentPage = 1;	
			} else {
				__this.__currentPage = args['currentPage'];
			}
		}

		//显示页码的个数
		if ("undefined" !== typeof(args['displayNum'])) {
			__this.__displayNum = args['displayNum'];
		}

		//请求的url
		if ("undefined" !== typeof(args['url'])) {
			__this.__url = args['url'];
		}

		//ajax POST 请求的数据
		if ("undefined" !== typeof(args['data'])) {
			__this.__data = args['data'];
		}

		// 请求返回数据
		if ("function" === typeof(args['getData'])) {
			__this.__getData = args['getData'];
		}

		//分页样式追加的dom
		if ("undefined" !== typeof(args['appendDom'])) {
			__this.__appendDom = args['appendDom'];
		}

		__this._drawHTML();
	}

	// }}}
	// {{{ function changePage()

	/**
	 * 改变页码时触发的动作
	 *
	 * @param {Int} currentPage
	 * @param {Int} rows
	 * @return {Void}  
	 */
	this.changePage = function (currentPage, rows)
	{
		__this.__currentPage = currentPage;
		sW.empty(g(__this.__appendDom));
		__this._drawHTML();
		$.ajax ({
            type: "post",
            url : __this.__url + '&page=' + currentPage + '&rows_count=' + rows,
            data: __this.__data,
            dataType: "json",
            error: sW.ajaxError,
            success: function (dataRes) {
				__this.__getData(dataRes);
            }
        });
	}

	// }}}
	// {{{ function _drawHTML()

	/**
	 * 绘制分页bar 的 HTML
	 *
	 * @return {Void}  
	 */
	this._drawHTML = function ()
	{
		var _html = [];
		_html.push('<div class="pagination">');
		
		//绘制 上一页html
		_html.push('<a class="prev_page" href="javascript:void(0);" ');
		if (__this.__currentPage > 1) {
			_html.push('onclick="' + __this.__thisName);
			_html.push('.changePage(' + __this._getPrevPage() + ', ' + __this.__rowsCount + ')" ');
		}
		_html.push('>« 上一页</a>');

		var _start = 1;
		var _end = Math.min(__this._getTotal(), __this.__displayNum);
		if (__this.__currentPage > __this.__displayNum) {
			_start = __this.__currentPage - __this.__displayNum	
			_end   = __this.__currentPage;
		}

		for (var i = _start; i <= _end; i++) {
			_html.push('<a href="javascript:void(0);" onclick="' + __this.__thisName);
			_html.push('.changePage(' + i + ', ' + __this.__rowsCount + ')" ');
			if (i == __this.__currentPage) {
				_html.push(' class="current" ');	
			}
			_html.push('>' + i + '</a>');	
		}	

		if ((__this._getTotal() > __this.__displayNum)
			&& __this._getTotal() != __this.__currentPage) {
			_html.push('<span>....</span>');	
		}

		_html.push('<a class="next_page" href="javascript:void(0);" ');
		if (__this.__currentPage < __this._getTotal()) {
			_html.push('onclick="' + __this.__thisName);
			_html.push('.changePage(' + __this._getNextPage() + ', ' + __this.__rowsCount + ')" ');
		}
		_html.push('>下一页 »</a></div>');
		
		if ("undefined" != typeof(__this.__appendDom)) {
			g(__this.__appendDom).innerHTML = _html.join('');	
		}
	}

	// }}}
	// {{{ function _getNextPage()

	/**
	 * 获取下一页的页码
	 *
	 * @return {Int}  
	 */
	this._getNextPage = function ()
	{
		return Math.max(1, Math.min(__this.__currentPage + 1, __this._getTotal()));	
	}

	// }}}
	// {{{ function _getPrevPage()

	/**
	 * 获取上一页的页码
	 *
	 * @return {Int}  
	 */
	this._getPrevPage = function ()
	{
		return Math.max(1, __this.__currentPage - 1);	
	}

	// }}}
	// {{{ function _getTotal()

	/**
	 * 分页的总页数
	 *
	 * @return {Int}  
	 */
	this._getTotal = function ()
	{
		return Math.ceil(__this.__count / __this.__rowsCount);		
	}

	// }}}
	// }}}	
}

// {{{ Object

/**
 * 实例化一个分页类，一般情况用这个就够了
 * 如果不能满足可以另外实例化，但是一定要实例化成全局的
 * 
 * Pager.display({
 * 	name: "Pager", //必需，和实例化的对象名一样
 * 	count: 300,	   //必需，条目的个数
 * 	url: "http://localhost/xxxx", //必需，ajax请求地址
 * 	appendDom: "pager", //必需，page 的 bar 加载的位置
 * 	getData: function(data) {}, //必需，分页回调返回的函数
 * 	
 * 	//非必需
 * 	data: a=1&b=3, // ajax请求的data
 * 	currentPage: 1 , //设置默认的当前页码
 * 	rowsCount: 10, //每页显示条数
 * 	displayNum: 10, // page bar 显示的页码个数
 * });
 * 
 */
var Pager = new Page();

// }}}
