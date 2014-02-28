<?php
function call($url, $type = 'GET', $params = array())
{
	$params = http_build_query($params);
	if ('GET' == $type) {
		$url .= $params;
	}
	$ch = curl_init($url);	
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);

	if ('POST' == $type) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	}
	
	return curl_exec($ch);
}

// 添加 device monitor
//$url = '127.0.0.1:9080/user/?/device_monitor.add';
//$attr_data = array(
//	array(
//		'attr_id' => 1,
//		'value'   => 'dsdsd',
//	),
//	array(
//		'attr_id' => 2,
//		'value'   => 'd434343',
//	),
//);
//$attr_data = json_encode($attr_data);
//$rev = call($url, 'POST', array('mid' => 1, 'did' => 1, 'attr_data' => $attr_data, 'dm_name' => 'dsds'));
//$rev = json_decode($rev, true);
//var_dump($rev);

// 删除 device monitor
//$url = '127.0.0.1:9080/user/?/device_monitor.del';
//$rev = call($url, 'POST', array('dm_id' => 1, 'did' => 2));
//$rev = json_decode($rev, true);
//var_dump($rev);

// 修改 device
$attr_data = array(
	array(
		'attr_id' => 1,
		'value'   => 'test_1',
	),
	array(
		'attr_id' => 2,
		'value'   => 'test_2',
	),
);
$attr_data = json_encode($attr_data);
$url = '127.0.0.1:9080/user/?/device_monitor.mod';
$rev = call($url, 'POST', array('did' => 1, 'mid' => 1, 'attr_data' => $attr_data, 'dm_id' => 1));
$rev = json_decode($rev, true);
var_dump($rev);

// 获取 device
$url = '127.0.0.1:9080/user/?/device_monitor.json';
$rev = call($url, 'POST', array('did' => 1));
$rev = json_decode($rev, true);
var_dump($rev);

// 获取 device params
$url = '127.0.0.1:9080/user/?/device_monitor.info';
$rev = call($url, 'POST', array('did' => 1, 'mid' => 1));
$rev = json_decode($rev, true);
var_dump($rev);
