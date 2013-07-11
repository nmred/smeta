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

error_reporting( E_ALL | E_STRICT );
require_once dirname(__DIR__) . '/src/core.php';
require_once PATH_SWAN_LIB . 'loader/sw_standard_auto_loader.class.php';

/**
+------------------------------------------------------------------------------
* 测试引导脚本 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
require_once PATH_SWAN_LIB . 'loader/sw_standard_auto_loader.class.php';
$autoloader = new lib\loader\sw_standard_auto_loader(array(
    'namespaces' => array(
        'lib' => PATH_SWAN_SF,
		'swan_test' => './',
		'mock' => dirname(__FILE__),
    ),
));

$autoloader->register();
