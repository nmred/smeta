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
    define('PATH_SWAN_TPL', PATH_SWAN_BASE . '/TPL/');
    define('PATH_SWAN_INC', PATH_SWAN_BASE . '/inc/');
        define('PATH_SWAN_LOCALE', PATH_SWAN_INC . '/locale/');
// }}}
// {{{ 参数配置

// 软件名称
define('SWAN_SOFTNAME', 'swan');

// 软件版本号
define('SWAN_VERSION', '0.1');

// 默认时区设置
define('SWAN_TIMEZONE_DEFAULT', 'Asia/Chongqing');

// 默认语言
define('SWAN_LANG_DEFAULT', 'zh_CN');

// 系统字符集
define('SWAN_CHARSET', 'UTF-8');

// 多语言支持的domain
define('SWAN_GETTEXT_DOMAIN', 'swan_translater');

// }}}
// {{{ 系统初始化
require_once PATH_SWAN_LIB . 'sw_language.class.php';
require_once PATH_SWAN_LIB . 'sw_config.class.php';

sw_language::set_gettext();

// }}}
