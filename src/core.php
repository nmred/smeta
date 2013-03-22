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
		define('PATH_SWAN_RRDPNG', PATH_SWAN_WEB . 'rrdpng/');
    define('PATH_SWAN_TMP', PATH_SWAN_BASE . '/tmp/');
		define('PATH_SWAN_COMPILE', PATH_SWAN_TMP . 'compile/');
		define('PATH_SWAN_CACHE', PATH_SWAN_TMP . 'tmp/');
    define('PATH_SWAN_INC', PATH_SWAN_BASE . '/inc/');
        define('PATH_SWAN_LOCALE', PATH_SWAN_INC . '/locale/');
        define('PATH_SWAN_CONF', PATH_SWAN_INC . '/conf/'); // 系统配置文件， 由 etc 下的 ini自动生成
    define('PATH_SWAN_RUN', PATH_SWAN_BASE . '/run/'); //系统运行过程中产生的文件，一般是pid文件
    define('PATH_SWAN_DATA', PATH_SWAN_BASE . '/data/'); // 存放数据目录
		define('PATH_SWAN_RRA', PATH_SWAN_DATA . 'rrd/'); //rrdtool数据库文件
define('PATH_SNMP_BIN', '/usr/bin/');
// }}}
// {{{ 参数配置

// {{{ 软件详细信息

// 软件名称
define('SWAN_SOFTNAME', 'swansoft');

// 软件版本号
define('SWAN_VERSION', '0.2.0');

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

//RRD相关
define('RRD_NL', "\\\n");

// }}}
// {{{ 系统初始化
//require_once PATH_SWAN_LIB . 'sw_language.class.php';
//require_once PATH_SWAN_LIB . 'sw_config.class.php';
//require_once PATH_SWAN_LIB . 'sw_db.class.php';
require_once PATH_SWAN_BASE . '/global.func.php';

//require_once PATH_SWAN_LIB . 'sw_member.class.php';
//require_once PATH_SWAN_LIB . 'sw_sequence.class.php';
//初始化时区
date_default_timezone_set(SWAN_TIMEZONE_DEFAULT);
//sw_language::set_gettext();
//fb
//require_once PATH_SWAN_LIB . 'firephp/FirePHPCore/fb.php';

// }}}
// }}}
// {{{ 数据库表定义

define('SWAN_TBN_SEQUENCE_GLOBAL', 'sequence_global');
define('SWAN_TBN_SEQUENCE_DEVICE', 'sequence_device');
define('SWAN_TBN_SEQUENCE_PROJECT', 'sequence_project');
define('SWAN_TBN_DEVICE_KEY', 'device_key');
define('SWAN_TBN_DEVICE_BASIC', 'device_basic');
define('SWAN_TBN_DEVICE_SNMP', 'device_snmp');
define('SWAN_TBN_PROJECT_KEY', 'project_key');
define('SWAN_TBN_PROJECT_BASIC', 'project_key');
define('SWAN_TBN_PROJECT_DATA_SOURCE', 'project_data_source');
define('SWAN_TBN_PROJECT_ARCHIVE', 'project_archive');

// }}}
// {{{ autoload 管理

function sw_lib_autoloader($class_name) {
	$parts = array();
	if (false !== strpos($class_name, '\\')) {
		$parts = explode('\\', $class_name);
	}

	if (!isset($parts[0]) || 'lib' !== $parts[0]) {
		trigger_error("$class_name is not std lib, so disable autoload.", E_USER_ERROR);	
	}

	array_shift($parts);
	$last_part = array_pop($parts);
	$class_path = PATH_SWAN_LIB . implode($parts, '/') . '/' . $last_part . '.class.php';

	if (!file_exists($class_path)) {
		trigger_error("load `$class_name` is faild, `$class_path` is not exists.", E_USER_ERROR);	
	}

	require_once $class_path;
}

spl_autoload_register('sw_lib_autoloader');

// }}}
