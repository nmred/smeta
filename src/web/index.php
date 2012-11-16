<?php
require_once 'core.php';
require_once PATH_SWAN_LIB . 'controller/sw_controller_front.class.php';

$front = sw_controller_front::get_instance();
$front->add_controller_directory(PATH_SWAN_LIB . 'ui/action/user/', 'user');
$front->add_controller_directory(PATH_SWAN_LIB . 'ui/action/admin/', 'admin');
$front->dispatch();
