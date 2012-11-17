<?php
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
* 核心处理程序 全局变量
+------------------------------------------------------------------------------
*  
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

// {{{  绝对路劲
define('PATH_SWAN_BASE', realpath(dirname(__FILE__)));
    define('PATH_SWAN_LIB', PATH_SWAN_BASE . '/lib/');
    define('PATH_SWAN_HELP', PATH_SWAN_BASE . '/help/');
    define('PATH_SWAN_SHELL', PATH_SWAN_BASE . '/shell/');
    define('PATH_SWAN_TPL', PATH_SWAN_BASE . '/tpl/');
    define('PATH_SWAN_WEB', PATH_SWAN_BASE . '/web/');
    define('PATH_SWAN_TMP', PATH_SWAN_BASE . '/tmp/');
		define('PATH_SWAN_COMPILE', PATH_SWAN_TMP . 'compile/');
		define('PATH_SWAN_CACHE', PATH_SWAN_TMP . 'tmp/');
    define('PATH_SWAN_INC', PATH_SWAN_BASE . '/inc/');
        define('PATH_SWAN_LOCALE', PATH_SWAN_INC . '/locale/');

define('PATH_SNMP_BIN', '/usr/bin/');
// }}}
// {{{ 参数配置

// {{{ 软件详细信息

// 软件名称
define('SWAN_SOFTNAME', 'swansoft');

// 软件版本号
define('SWAN_VERSION', '0.11');

// 软件发行号
define('SWANBR_RELEASE', 'beta');

//软件宣言 ------一切为了方便
define('SWANBR_SLOGAN', 'Everything in order to facilitate');

//版权声明
define('SWANBR_COPYRIGHT', '© 2011-2012 swanlinux');

//许可协议 
define('SWANBR_LICENSED_URL', 'BSD');

// 官方网址
define('SWANBR_WEB_DOMAIN', 'http://www.swanlinux.net');

// 作者
define('SWANBR_AUTHOR', 'swanteam <nmred_2008@126.com>');

// }}}
// {{{ 参数设置

// 默认时区设置
define('SWAN_TIMEZONE_DEFAULT', 'Asia/Chongqing');

// 默认语言
define('SWAN_LANG_DEFAULT', 'zh_CN');

// 系统字符集
define('SWAN_CHARSET', 'UTF-8');

// 多语言支持的domain
define('SWAN_GETTEXT_DOMAIN', 'swan_translater');

//是否开启模板缓存
define('SW_CACHE_START', false);

//缓存过期时间
define('SW_CACHE_TIME', 0);

//模板定界符
define('SW_LEFT_DELIMITER', '<!--{{');
define('SW_RIGHT_DELIMITER', '}}-->');

// }}}
// {{{ 系统初始化
require_once PATH_SWAN_LIB . 'sw_language.class.php';
require_once PATH_SWAN_LIB . 'sw_config.class.php';
require_once PATH_SWAN_LIB . 'sw_config.class.php';
require_once PATH_SWAN_BASE . '/global.func.php';

//初始化时区
date_default_timezone_set(SWAN_TIMEZONE_DEFAULT);
sw_language::set_gettext();
//fb
require_once PATH_SWAN_LIB . 'firephp/FirePHPCore/fb.php';

// }}}
