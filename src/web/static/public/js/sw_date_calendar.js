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
* 日期时间控件
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

swCalendar = function ()
{
	var __this = this;


	// {{{ members

	/**
	 * date控件的类原型  
	 */
	var dateObject = dateClass.prototype;

	/**
	 * 常量  
	 */
	var dateClass.SEL_SINGLE = 1,
		dateClass.SEL_MULTIPLE = 2,
		dateClass.SEL_WEEK = 3,
		dateClass.SEL_NONE = 0;
	
	/**
	 * 将日期转化为20130101的数字
	 *
	 * @param {String|Date} str
	 * @return {Int}  
	 */
	var dateClass.dateToInt = __this._dateToInt;

	/**
	 * 创建一个日期对象
	 *
	 * @param {String} str 形如：20130101
	 * @param {Int} hour
	 * @param {Int} minute
	 * @param {Int} second
	 * @return {Int}  
	 */
	var dateClass.intToDate = __this._intToDate;

	/**
	 * 多语言包  
	 */
	this.__lang = 'cn';


	// {{{ lang 语言包

	/**
	 * 多语言包  
	 */
	this.__packLang = {
		// {{{ 英文

		cn : {
			 fdow: 1,                // 决定一个星期的第一天是星期几，根据地区不一样， 0 == 星期日 1 == 星期一
			 goToday: "Go Today",
			 today: "Today",         // 出现在控件的下方
			 wk: "wk",
			 weekend: "0,6",         // 0 = Sunday, 1 = Monday, etc.
			 AM: "am",
			 PM: "pm",
			 mn : [ "January",
				 "February",
				 "March",
				 "April",
				 "May",
				 "June",
				 "July",
				 "August",
				 "September",
				 "October",
				 "November",
				 "December" ],
			 smn : [ "Jan",
				 "Feb",
				 "Mar",
				 "Apr",
				 "May",
				 "Jun",
				 "Jul",
				 "Aug",
				 "Sep",
				 "Oct",
				 "Nov",
				 "Dec" ],
			 dn : [ "Sunday",
				 "Monday",
				 "Tuesday",
				 "Wednesday",
				 "Thursday",
				 "Friday",
				 "Saturday",
				 "Sunday" ],
			 sdn : [ "Su",
				 "Mo",
				 "Tu",
				 "We",
				 "Th",
				 "Fr",
				 "Sa",
				 "Su" ]
		},

		// }}}
		// {{{ 中文

		cn : {
			fdow: 1,                // first day of week for this locale; 0 = Sunday, 1 = Monday, etc.
			goToday: "今天",
			today: "今天",         // appears in bottom bar
			wk: "周",
			weekend: "0,6",         // 0 = Sunday, 1 = Monday, etc.
			AM: "AM",
			PM: "PM",
			mn : [ "一月",
				"二月",
				"三月",
				"四月",
				"五月",
				"六月",
				"七月",
				"八月",
				"九月",
				"十月",
				"十一月",
				"十二月"],
			smn : [ "一月",
				"二月",
				"三月",
				"四月",
				"五月",
				"六月",
				"七月",
				"八月",
				"九月",
				"十月",
				"十一月",
				"十二月"],
			dn : [ "星期日",
				"星期一",
				"星期二",
				"星期三",
				"星期四",
				"星期五",
				"星期六",
				"星期日" ],
			sdn : [ "日",
				"一",
				"二",
				"三",
				"四",
				"五",
				"六",
				"日" ]

		}

		// }}}
	};
	//}}}
	// }}}
	// {{{ functions
	// {{{ function dateClass()

	/**
	 * 日期时间控件
	 *
	 * @param {Object} params
	 * @return {Object}  
	 */
	this.dateClass = function(params)
	{
		var __that = this;

		/**
		 * date控件参数
		 */
		this.__args = {};

		/**
		 * hander
		 */
		this.__handlers = {};

		// {{{ initParam
		var _initParam = {
			animation: true,
			acount: null,
			bottomBar: true,
			date: true,
			fdow: __this._lang('fdow'),
			min: null,
			max: null,
			reverseWheel: false,
			selection: [],
			selectionType: __this.SEL_SINGLE,
			weekNumbers: false,
			align: "Bl/ / /T/r",
			inputField: null,
			trigger: null,
			dateFormat: "%Y-%m-%d",
			fixed: false,
			opacity: (!!document.all) ? 1 : 3,
			titleFormat: "%b %Y",
			showTime: false,
			timePos: "right",
			time: true,
			minuteStep: 5,
			noScroll: false,
			disabled: null,
			checkRange: false,
			dateInfo: null,
			onChange: null,
			onSelect: null,
			onTimeChange: null,
			onFocus: null,
			onBlur: null,
		};	
		
		// }}}	
			
		params = params || {};
		this.__args = sW.merge(_initParam, params);

		//测试时取的默认日期，生产环境中将替换成服务器时间
		var date = new Date();

		__that.__args.min = __this._formatDate(__this.__args.min); 
		__that.__args.max = __this._formatDate(__this.__args.max); 

		if (true === __this.__args.date) {
			__this.__args.date = date;	
		}

		if (true === __this.__args.time) {
			__this.__args.time = date.getHours() * 100 + Math.floor(date.getMinutes() / __this.__args.minuteStep) * __this.__args.minuteStep;	
		}

		this.date = __this._formatDate(__this.__args.date);
		this.time = __this.__args.time;
		this.fdow = __this.__args.fdow;	
	}

	// }}}
	// {{{ function setup()

	/**
	 * 日期时间控件启动函数
	 *
	 * @param {Object} params
	 * @return {Object}  
	 */
	dateClass.setup = function(params)
	{
		return new dateClass(params);
	}

	// }}}

	// {{{ function _lang()

	/**
	 * 多语言翻译
	 *
	 * @param {String} msgid
	 * @return {String}  
	 */
	this._lang = function(msgid)
	{
		if (__this.__packLang[__this.__lang].hasOwnProperty(msgid)) {
			return __this.__packLang[__this.__lang][msgid];	
		}

		return msgid;
	}

	// }}}
	// {{{ function _setLang()

	/**
	 * 设置语言
	 *
	 * @param {Date} oDate
	 * @param {String} str
	 * @return {String}  
	 */
	this._setLang = function (lang)
	{
		__this.__lang = lang;
	}

	// }}}
	// {{{ function _dateToInt()

	/**
	 * 将日期转化为20130101的数字
	 *
	 * @param {String|Date} str
	 * @return {Int}  
	 */
	this._dateToInt = function (str)
	{
		if (str instanceof Date) {
			return 1e4 * str.getFullYear() + 100 * (str.getMonth() + 1) + str.getDate();	
		} 

		if ("string" ==  typeof(str)) {
			return parseInt(str, 10);	
		} 
		
		return str;
	}

	// }}}
	// {{{ function _intToDate()

	/**
	 * 创建一个日期对象
	 *
	 * @param {String} str 形如：20130101
	 * @param {Int} hour
	 * @param {Int} minute
	 * @param {Int} second
	 * @return {Int}  
	 */
	this._intToDate = function (str, hour, minute, second)
	{
		if (!(str instanceof Date)) {
			str = parseInt(str, 10);
			var _year = Math.floor(str / 1e4);
			var tmp = str % 1e4;
			var _month = Math.floor(tmp / 100);
			var _date = str % 100;
			return new Date(_year, (_month - 1), _date, hour, minute, second);	
		} 

		return str;
	}

	// }}}
	// {{{ function _printDate()

	/**
	 * 打印日期
	 *
	 * @param {Date} oDate
	 * @param {String} str
	 * @return {String}  
	 */
	this._printDate = function (oDate, str)
	{
		if (!(oDate instanceof Date)) {
			return false;	
		} 

		var _month   = oDate.getMonth(),
			_date    = oDate.getDate(),
			_year    = oDate.getFullYear(),
			_weeks   = __this._getWeeks(oDate),
			_day     = oDate.getDay(),
			_hour    = oDate.getHours(),
			_isHour  = _hour >= 12,
			_lHour   = _isHour ? _hour -12 : _hour,
			_dates   = __this._getDays(oDate),
			_minute  = oDate.getMinutes(),
			_seconds = oDate.getSeconds(),
			_parent  = /%./g;

		var _replace = {
			"%a" : __this._lang("sdn")[_day], //星期（简写）	
			"%A" : __this._lang("dn")[_day], //星期	
			"%b" : __this._lang("smn")[_month], //月份（简写）
			"%B" : __this._lang("mn")[_month], //月份
			"%C" : 1 + Math.floor(_year / 100), //世纪
			"%d" : _date < 10 ? "0" + _date : _date, //日
			"%e" : _date,	//日，没有前导零
			"%H" : _hour < 10 ? "0" + _hour : _hour, //24制小时，有前导零
			"%I" : _lHour < 10 ? "0" + _lHour : _lHour, //12制小时， 有前导零
			"%k" : _hour,	// 24制小时
			"%l" : _lHour, // 12制小时
			"%j" : _dates < 10 ? "00" + _dates : (_dates < 100 ? "0" + _dates : _dates), //该日是一年中的第几天
			"%m" : _month < 9 ? "0" + (_month + 1) : (_month + 1),	//数字月份,有前导零
			"%o" : _month + 1, //数字月份没有前导零
			"%M" : _minute < 10 ? "0" + _minute : _minute, //分
			"%n" : "\n",
			"%t" : "\t",
			"%%" : "%",
			"%U" : _weeks < 10 ? "0" + _weeks : _weeks, //该日是本年的第几周
			"%W" : _weeks < 10 ? "0" + _weeks : _weeks,
			"%V" : _weeks < 10 ? "0" + _weeks : _weeks,
			"%u" : _day + 1,
			"%v" : _day,
			"%y" : ("" + _year).substr(2, 2),
			"%Y" : _year
		};

		return str.replace(_parent, function (data) {
			return _replace.hasOwnProperty(data) ? _replace[data] : data;	
		})
	}

	// }}}
	// {{{ function _getWeeks()

	/**
	 * 获取在一年中的第几周
	 *
	 * @param {Date} oDate
	 * @return {Int}  
	 */
	this._getWeeks = function (oDate)
	{
		oDate = new Date(oDate.getFullYear(), oDate.getMonth(), oDate.getDate(), 12, 0, 0);
		var day = oDate.getDay();
		oDate.setDate(oDate.getDate() - (day + 6) % 7 + 3);
		var value = oDate.valueOf();

		//从1月4号起计算
		oDate.setMonth(0);
		oDate.setDate(4);
		return Math.round((value - oDate.valueOf()) / 6048e5) + 1;
	}

	// }}}
	// {{{ function _getDays()

	/**
	 * 获取在一年中的第几天
	 *
	 * @param {Date} oDate
	 * @return {Int}  
	 */
	this._getDays = function (oDate)
	{
		oDate = new Date(oDate.getFullYear(), oDate.getMonth(), oDate.getDate(), 12, 0, 0);
		var baseDate = new Date(oDate.getFullYear(), 0, 1, 12, 0, 0);
		return Math.floor((oDate.valueOf() - baseDate.valueOf()) / 864e5);
	}

	// }}}
	// {{{ function _formatDate()

	/**
	 * 格式化日期
	 *
	 * @param {String} sDate
	 * @return {Date}  
	 */
	this._formatDate = function(sDate)
	{
		if (sDate) {
			if ("number" == typeof(sDate)) {
				return __this._intToDate(sDate);	
			}
			
			if (!(sDate instanceof Date)) {
				var dateArr = sDate.split(/-/);
				return new Date(parseInt(dateArr[0], 10), parseInt(dateArr[1], 10) - 1, parseInt(dateArr[2], 10), 12, 0, 0, 0);	
			}
		}

		return sDate;
	}

	// }}}
	//html
	// {{{ function _redawTable()

	/**
	 * 绘制整体的tableHTML
	 *
	 * @return {Void}  
	 */
	this._redawTable = function ()
	{
		var _html = [];

		_html.push('<table class="DynarchCalendar-topCont"><tr><td>');
		_html.push('<div class="DynarchCalendar">');
		if (document.all) {
			_html.push('<a class="DynarchCalendar-focusLink" href="#"></a>');
		} else {
			_html.push('<button class="DynarchCalendar-focusLink"></button>');
		}

		//top
		_html.push('<div class="DynarchCalendar-topBar">');

		_html.push('<div dyc-type="nav" dyc-btn="-Y" dyc-cls="hover-navBtn,pressed-navBtn" class="DynarchCalendar-navBtn DynarchCalendar-prevYear"><div></div></div>');
		_html.push('<div dyc-type="nav" dyc-btn="+Y" dyc-cls="hover-navBtn,pressed-navBtn" class="DynarchCalendar-navBtn DynarchCalendar-nextYear"><div></div></div>');
		_html.push('<div dyc-type="nav" dyc-btn="-M" dyc-cls="hover-navBtn,pressed-navBtn" class="DynarchCalendar-navBtn DynarchCalendar-prevMonth"><div></div></div>');
		_html.push('<div dyc-type="nav" dyc-btn="+M" dyc-cls="hover-navBtn,pressed-navBtn" class="DynarchCalendar-navBtn DynarchCalendar-nextMonth"><div></div></div>');

		_html.push('<table class="DynarchCalendar-titleCont"><tr><td>');
		_html.push('<div dyc-type="title" dyc-btn="menu" dyc-cls="hover-title,pressed-title" class="DynarchCalendar-title">');
		_html.push(__this._selectHTML());
		_html.push('</div>');

		_html.push('<div class="DynarchCalendar-dayNames">');
		_html.push(__this._weekHTML());
		_html.push('</div>');
		_html.push('</div>');
		
		//body
		_html.push('<div class="DynarchCalendar-body"></div');

		if (__this.__args.bottomBar || __this.__args.showTime) { //bottom
			_html.push('<div class="DynarchCalendar-bottomBar">');
			_html.push(__this._redawBottom());
			_html.push('</div>');
		}

		// none 点击年的时候出来的选择界面
		_html.push('<div class="DynarchCalendar-menu" style="display: none">')
		_html.push(__this._selectYearMonthHTML());
		_html.push('</div>');

		return _html.join('');
	}

	// }}}
	// {{{ function _redawBottom()

	/**
	 * 绘制整体的table bottom
	 *
	 * @return {Void}  
	 */
	this._redawBottom = function ()
	{
		var _html = [];
		function _createTime() {
			if (__this.__args.showTime) {
				_html.push('<td>');
				_html.push(__this._timeHTML());
				_html.push('</td>');	
			}
		}

		_html.push('<table style="width:100%"><tr>');
		if ('left' == __this.__args.timePos) {
			_createTime();	
		}

		if (__this.__args.bottomBar) {
			_html.push('<td><table><tr><td>');
			_html.push('<div dyc-btn="today" dyc-cls="hover-bottomBar-today,pressed-bottomBar-today" dyc-type="bottomBar-today" class="DynarchCalendar-bottomBar-today">');
			_html.push(L('today'));
			_html.push('</div></td></tr></table></td>');	
		}

		if ('right' == __this.__args.timePos) {
			_createTime();	
		}

		_html.push('</tr></table>');
		return _html.join('');
	}

	// }}}
	// {{{ function _weekHTML()

	/**
	 * 生成绘制星期显示的HTML
	 *
	 * @return {Void}  
	 */
	this._weekHTML = function ()
	{
		var _html = [];

		_html.push('<table><tr>');
		var i = 0;
		if (__this.__args.weekNumbers) {
			_html.push('<td><div class="DynarchCalendar-weekNumber">');
			_html.push(__this._lang('wk'));
			_html.push('</div></td>');	
		}

		while(i < 7) {
			var _key = (i++ + __this.__args.fdow) % 7;
			_html.push('<td><div ');
			if (L('weekend').indexOf(_key) >= 0) {
				_html.push(' class="DynarchCalendar-weekend">');
			} else {
				_html.push('>');	
			}

			_html.push(L('sdn')[_key]);
			_html.push('</div></td>');
		}

		_html.push('</tr></table>');

		return _html.join('');
	}

	// }}}
	// {{{ function _timeHTML()

	/**
	 * 生成绘制时间显示的HTML
	 *
	 * @return {Void}  
	 */
	this._timeHTML = function ()
	{
		var _html = [];

		_html.push('<table class="DynarchCalendar-time"><tr>');
		_html.push('<td rowspan="2"><div dyc-type="time-hour" dyc-cls="hover-time,pressed-time" class="DynarchCalendar-time-hour"></div></td>');
		_html.push('<td dyc-type="time-hour+" dyc-cls="hover-time,pressed-time" class="DynarchCalendar-time-up"></td>');
		_html.push('<td rowspan="2" class="DynarchCalendar-time-sep"></td>');
		_html.push('<td rowspan="2"><div dyc-type="time-min" dyc-cls="hover-time,pressed-time" class="DynarchCalendar-time-minute"></div></td>');
		_html.push('<td dyc-type="time-min+" dyc-cls="hover-time,pressed-time" class="DynarchCalendar-time-up"></td>');
		if (12 == __this.__args.showTime) {
			_html.push('<td rowspan="2" class="DynarchCalendar-time-sep"></td><td rowspan="2"><div class="DynarchCalendar-time-am" dyc-type="time-am" dyc-cls="hover-time,pressed-time"></div></td>')	
		}
		_html.push('</tr><tr><td dyc-type="time-hour-" dyc-cls="hover-time,pressed-time" class="DynarchCalendar-time-down"></td>');
		_html.push('<td dyc-type="time-min-" dyc-cls="hover-time,pressed-time" class="DynarchCalendar-time-down"></td>');
		_html.push('</tr></table>');

		return _html.join('');
	}

	// }}}
	// {{{ function _selectYearMonthHTML()

	/**
	 * 生成绘制选择年月显示的HTML
	 *
	 * @return {Void}  
	 */
	this._selectYearMonthHTML = function ()
	{
		var _html = [];

		_html.push('<table height="100%"><tr><td>');
		_html.push('<table style="margin-top: 1.5em">');
		_html.push('<tr><td colspan="3"><input dyc-btn="year" class="DynarchCalendar-menu-year" size="6" ');
		_html.push(' value="' + __this.__args.date.getFullYear() + '"/></td></tr>');
		_html.push('<tr><td><div dyc-type="menubtn" dyc-cls="hover-navBtn,pressed-navBtn" dyc-btn="today">');
		_html.push(__this._lang('goToday'));
		_html.push('</div></td></tr>');
		_html.push('</table>');
		_html.push('<p class="DynarchCalendar-menu-sep">&nbsp;</p>');

		_html.push('<table class="DynarchCalendar-menu-mtable">');
		
		var i = 0, month = __this._lang('smn'), j;
		while (i < 12) {
			_html.push('<tr>');
			for (j = 4; --j > 0;) {
				_html.push('<td><div dyc-type="menubtn" dyc-cls="hover-navBtn,pressed-navBtn" dyc-btn="m' + i + '" class="DynarchCalendar-menu-month">');
				_html.push(month[i++]);
				_html.push('</div></td>');
			}
			_html.push('</tr>');
		}
		_html.push('</table></td></tr></table>');
		return _html.join('');
	}

	// }}}
	// {{{ function _selectHTML()

	/**
	 * 生成绘制年或日可选择显示的HTML
	 *
	 * @return {Void}  
	 */
	this._selectHTML = function ()
	{
		var _html = [];

		_html.push('<div unselectable="on">');
		_html.push(__this._printDate(__this.__args.date, __this.__args.titleFormat));
		_html.push('</div>');
		return _html.join('');
	}

	// }}}
	// }}}
	return __this.dateClass;	
} ()
