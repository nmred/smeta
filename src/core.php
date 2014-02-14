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
	define('PATH_SWAN_SF', PATH_SWAN_BASE . '/sf/');
	define('PATH_SWAN_LIB', PATH_SWAN_BASE . '/lib/');
    define('PATH_SWAN_SHELL', PATH_SWAN_BASE . '/shell/');
    define('PATH_SWAN_ETC', PATH_SWAN_BASE . '/etc/');
		define('PATH_INI_PHPD', PATH_SWAN_ETC . 'sw_phpd.ini');
		define('PATH_INI_SWDATA', PATH_SWAN_ETC . 'sw_swdata.ini');
    define('PATH_SWAN_TPL', PATH_SWAN_BASE . '/tpl/');
    define('PATH_SWAN_WEB', PATH_SWAN_BASE . '/web/');
		define('PATH_SWAN_RRDPNG', PATH_SWAN_WEB . 'rrdpng/');
    define('PATH_SWAN_TMP', PATH_SWAN_BASE . '/tmp/');
		define('PATH_SWAN_COMPILE', PATH_SWAN_TMP . 'compile/');
		define('PATH_SWAN_CACHE', PATH_SWAN_TMP . 'tmp/');
    define('PATH_SWAN_INC', PATH_SWAN_BASE . '/inc/');
        define('PATH_SWAN_LOCALE', PATH_SWAN_INC . '/locale/');
        define('PATH_SWAN_CONF', PATH_SWAN_INC . '/conf/'); // 系统配置文件， 由 etc 下的 ini自动生成
    define('PATH_SWAN_RUN', PATH_SWAN_BASE . '/run/'); //系统运行过程中产生的文件，一般是pid文件
    define('PATH_SWAN_DATA', PATH_SWAN_BASE . '/data/'); // 存放数据目录
// }}}
// {{{ 参数配置

// {{{ 软件详细信息

// 软件名称
define('SWAN_SOFTNAME', 'swansoft');

// 软件版本号
define('SWAN_VERSION', '0.2.1');

// 软件发行号
define('SWANBR_RELEASE', 'beta');

//软件宣言 ------一切为了方便
define('SWANBR_SLOGAN', 'Everything in order to facilitate');

//版权声明
define('SWANBR_COPYRIGHT', '© 2012-2014 swanlinux');

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

//RRD相关
define('RRD_NL', "\\\n");

define('SWAN_EXEC_UID', 'swan');
define('SWAN_EXEC_GID', 'swan');

// }}}
// {{{ 系统初始化

//初始化时区
date_default_timezone_set(SWAN_TIMEZONE_DEFAULT);

// }}}
// }}}
// {{{  框架初始化
// 引入 sf 框架

require_once PATH_SWAN_SF . 'swanphp.php';

$autoloader = \swan\loader\sw_auto::get_instance(array(
	'namespaces' => array(
		'lib' => PATH_SWAN_BASE,
	),
));

$autoloader->register();

// 初始化配置

\swan\config\sw_config::set_config(PATH_SWAN_CONF . '/config.php');

// }}}
// {{{ 数据库常量

define('SWAN_TBN_SEQUENCE_GLOBAL', 'sequence_global');
define('SWAN_TBN_SEQUENCE_DEVICE', 'sequence_device');
define('SWAN_TBN_SEQUENCE_MONITOR', 'sequence_monitor');
define('SWAN_TBN_DEVICE_KEY', 'device_key');
define('SWAN_TBN_DEVICE_BASIC', 'device_basic');
define('SWAN_TBN_DEVICE_MONITOR', 'monitor_params');
define('SWAN_TBN_MONITOR_BASIC', 'monitor_basic');
define('SWAN_TBN_MONITOR_ATTRIBUTE', 'monitor_attribute');
define('SWAN_TBN_MONITOR_METRIC', 'monitor_metric');

// }}}
