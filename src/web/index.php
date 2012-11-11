<?php

require_once 'core.php';
require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_http.class.php';
require_once PATH_SWAN_LIB . 'controller/response/sw_controller_response_http.class.php';
require_once PATH_SWAN_LIB . 'controller/sw_controller_front.class.php';

$front = new sw_controller_front();
$front->set_request(new sw_controller_request_http());
$front->set_response(new sw_controller_response_http());
$router = $front->get_router();
$request = $front->get_request();
$response = $front->get_response();
$arr = array(
	'user' => array(
		'test1' => true,
		'test2.do' => true,
	),
);
$router->set_route_map($arr);
$router->route($request);
fb::info($router->assemble($request->get_params()));
$response->set_body("<h1>测试</h1>");
$response->send_response();
fb::info($request);
fb::info($test);
fb::info($_SERVER);
