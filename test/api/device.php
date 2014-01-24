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

// 添加 device
$url = '127.0.0.1:9080/user/?/device.add';
$rev = call($url, 'POST', array('name' => 'ss21222'));
$rev = json_decode($rev, true);
var_dump($rev);
