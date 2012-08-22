<?php

require_once 'core.php';
require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_http.class.php';
require_once PATH_SWAN_LIB . 'controller/response/sw_controller_response_http.class.php';
require_once PATH_SWAN_LIB . 'controller/router/sw_controller_router_router.class.php';

$request = new sw_controller_request_http();
$response = new sw_controller_response_http();
$router = new sw_controller_router_router();
$arr = array(
	'user' => array(
		'test1' => true,
		'test2.do' => true,
	);
);
$router->set_route_map($arr);
$test = $request->get_request_uri();
$response->set_body("<h1>测试</h1>");
$response->send_response();
fb::info($request);
fb::info($test);
fb::info($_SERVER);
