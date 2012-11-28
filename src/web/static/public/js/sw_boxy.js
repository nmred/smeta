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
* 小部件
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/


// {{{ class

// {{{ Message()

/**
 * 绘制Message  
 * 
 * @return {Void}
 */
function Message()
{
	var __this = this;

	// {{{ members
	
	/**
	 * Message 的id 
	 *  
	 */
	this.__messageId = 'message_id';
		
	/**
	 * timeout 默认是3s 
	 *  
	 */
	this.__timeout = 3;

	/**
	 * 计时器 
	 *  
	 */
	this.__timeFeed;

	// }}}
	// {{{ functions
	// {{{ function show()

	this.show = function (message, status, timeout, id) {
		if ("undefined" == typeof(message)) {
			return;	
		}

		var _status = ("undefined" == typeof(status)) ? 'success' : status;
		var _id = ("undefined" == typeof(id)) ? __this.__messageId : id;
		var _html = [];

		_html.push('<div id="' + _id + '" class="alert alert-' + _status + '" onclick="fMessage.hide()">');
		_html.push('<p>' + message +'</p></div>');

		if (null != sW.g(_id)) {
			__this.hide(null, _id);	
		}

		$(document.body).append(_html.join(''));

		//定时器
		if ("undefined" !== typeof(timeout) && 0 !== timeout) {
			//首先要清除掉上一次的定时器
			if ("undefined" !== typeof this.__timeFeed) {
				clearInterval(this.__timeFeed);
			}
			
			timeout = -1 === timeout ? this.__timeout : timeout;
			timeout = timeout * 1000;
			this.__timeFeed = setInterval(function() { __this.hide(__this.__timeFeed); }, timeout);	
		}
	}

	// }}}
	// {{{ function hide()

	this.hide = function (timeId, id){
		if ("undefined" !== typeof iId) {
			clearInterval(iId);
		}
		var _id = ("undefined" == typeof(id)) ? __this.__messageId : id;
		if (null != sW.g(_id)) {
			sW.remove(sW.g(_id));
		}
	}
	// }}}
	// }}}	
}

// }}}
// }}}
// {{{ function
// {{{ swBoxy
function swBoxy (argObject)
{
	var __this = this;

	//  {{{ members

	/**
	 * box的标题 
	 *  
	 */
	this.__title = '';

	/**
	 * box的宽度
	 *  
	 */
	this.__width = '200px';

	/**
	 * box的top
	 *  
	 */
	this.__top = '0px';

	/**
	 * box的left
	 *  
	 */
	this.__left = '0px';

	/**
	 * box的内容
	 *  
	 */
	this.__html = '';

	/**
	 * box的箭头指向 left|right|top|bottom
	 *  
	 */
	this.__direction = '';

	/**
	 * box的ID，用于一个页面显示多个
	 *  
	 */
	this.__boxId = 'sw_boxy';

	// }}}
	//  {{{ functions
	// {{{ function init()
	
	/**
	 * 初始化参数
	 * 
	 * @return {Void}
	 */
	this.init = function ()
	{
		if ("undefined" != typeof(argObject.title)) {
			__this.__title = argObject.title;			
		}

		if ("undefined" != typeof(argObject.width)) {
			__this.__width = argObject.width;			
		}

		if ("undefined" != typeof(argObject.left)) {
			__this.__left = argObject.left;			
		}

		if ("undefined" != typeof(argObject.top)) {
			__this.__top = argObject.top;			
		}

		if ("undefined" != typeof(argObject.html)) {
			__this.__html = argObject.html;			
		}

		var direction = ['left', 'right', 'top', 'bottom'];
		for (var _key in direction) {
			if (("undefined" != typeof(argObject.direction)) && direction[_key] == argObject.direction) {
				__this.__direction = argObject.direction;			
			}
		}

		if ("undefined" != typeof(argObject.boxid)) {
			__this.__boxId = argObject.boxid;			
		}
	}

	// }}}
	// {{{ function _drawBoxy()
	
	/**
	 * 绘制boxy  
	 * 
	 * @param {Mixed} 需要输出的信息
	 * @return {Void}
	 */
	this._drawBoxy = function ()
	{
		var _html = [];

		_html.push('<div id="' + __this.__boxId + '" class="popover fade in ');

		//绘制箭头
		if ('' != __this.__direction) {
			_html.push(' ' + __this.__direction + '"');
			_html.push(' style="width:' + __this.__width + ';top:' + __this.__top + ';left:' + __this.__left + ';display:block;">');
			_html.push('<div class="arrow"></div>');	
		} else {
			_html.push(' ' + __this.__direction + '"');
			_html.push(' style="width:' + __this.__width + ';top:' + __this.__top + ';left:' + __this.__left + ';display:block;">');
		}
		
		_html.push('<div class="popover-inner">');

		//绘制标题
		if ('' != __this.__title) {
			_html.push('<p class="popover-title">' + __this.__title + '</p>');
		}

		//绘制内容
		_html.push('<div class="popover-content">' + __this.__html + '</div></div></div>');

		var _body = document.body;
		$(_body).append(_html.join(''));	
	}

	// }}}
	// }}}

	__this.init();

	if (null != sW.g(__this.__boxId)) {
		sW.remove(sW.g(__this.__boxId));	
	}
	__this._drawBoxy();
};

// }}}

// {{{ bindFormBoxy
function fBindBoxy (argObject)
{
	var __this = this;

	//  {{{ members

	/**
	 * box的宽度
	 *  
	 */
	this.__width = '200px';

	// }}}
	//  {{{ functions
	// {{{ function _bind()
	
	/**
	 * 绑定动作
	 * 
	 * @return {Object}
	 */
	this._bind = function ()
	{
		//绑定给定表单的弹出
		if ("undefined" == typeof(argObject.data)) {
			return;	
		}

		if ("undefined" != typeof(argObject.width)) {
			__this.__width = argObject.width;		
		}

		for (var _id in argObject.data) {
			if (null != sW.g(_id)) {
				// {{{ click
				$("#" + _id).click(function() {
					//删除所有的弹出框
					__this._remove();

					//获取相对显示元素的id ,增加显示位置的别名
					if ("undefined" != typeof(argObject.alias[this.id])) {
						var _id = argObject.alias[this.id];
					} else {
						var _id = this.id;	
					}
					var _html = argObject.data[this.id];

					var _position = sW.getPosition(sW.g(_id));	
					var _top = _position.top + (_position.height / 2);
					var _left = _position.right;
					
					//判断是否超出了给定的宽度
					var _containerWidth = sW.getPosition(sW.g("container")).width;
					if ((_left + parseInt(__this.__width)) > _containerWidth) {
						var _direction = 'top';
						_top = _position.top + _position.height;
						_left = (_position.right / 2);
					} else {
						var _direction = 'left';	
					}

					swBoxy({
						boxid: "fbox_" + _id,
						width: __this.__width,
						top: _top + 'px',
						left: _left + 'px',
						direction: _direction,
						html: _html,	
					});			

					// 对高度进行校正
					var _boxHeight = sW.getPosition(sW.g('fbox_' + _id)).height;
					sW.g('fbox_' + _id).style.top = (_top - (_boxHeight / 2)) + 'px';
					
					//为弹出框绑定事件
					$("#" + 'fbox_' + _id).bind("click", function(){
						__this._remove();	
					});
				});
				// }}}
				// {{{ blur
				$("#" + _id).blur(function() {
					__this._remove();
				});
				// }}}
			}
		}
	}

	// }}}
	// {{{ function _remove()
	
	/**
	 * 删除所有的弹出框
	 * 
	 * @return {Void}
	 */
	this._remove = function ()
	{
		//删除所有的弹出框
		if ("undefined" == typeof(argObject.data)) {
			return;	
		}

		for (var _id in argObject.data) {
			if (null != sW.g('fbox_' + _id)) {
				sW.remove(sW.g('fbox_' + _id));
			}
		}

		//alias
		if ("undefined" == typeof(argObject.alias)) {
			return;	
		}

		for (var _id in argObject.alias) {
			if (null != sW.g('fbox_' + argObject.alias[_id])) {
				sW.remove(sW.g('fbox_' + argObject.alias[_id]));
			}
		}
	}

	// }}}
	// }}}

	__this._bind();
};

// }}}
// }}}
// {{{ object

var fMessage = new Message();
// }}}
