<?php

require_once 'core.php';
require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_http.class.php';

$request = new sw_controller_request_http();
$test = $request->get_request_uri();
fb::info($test);
fb::info($_SERVER);
