<?php
require_once 'core.php';
require_once PATH_SWAN_LIB . 'controller/sw_controller_front.class.php';
require_once PATH_SWAN_LIB . 'controller/plugin/sw_controller_plugin_test.class.php';

$front = sw_controller_front::get_instance();
$front->register_plugin(new sw_controller_plugin_test);
$front->add_controller_directory(PATH_SWAN_LIB . 'ui/action/user/', 'user');
$front->add_controller_directory(PATH_SWAN_LIB . 'ui/action/admin/', 'admin');
$front->add_controller_directory(PATH_SWAN_LIB . 'ui/action/datadesc/', 'datadesc');
$front->dispatch();
