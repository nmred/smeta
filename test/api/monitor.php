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

// 添加 monitor
$url = '127.0.0.1:9080/dev/?/monitor.add';
$rev = call($url, 'POST', array('name' => 'ss21222' . time(), 'display_name' => 'dsadakljl', 'steps' => 22));
$rev = json_decode($rev, true);
var_dump($rev);

// 删除 monitor
//$url = '127.0.0.1:9080/dev/?/monitor.del';
//$rev = call($url, 'POST', array('mid' => '1'));
//$rev = json_decode($rev, true);
//var_dump($rev);

// 修改 monitor
$url = '127.0.0.1:9080/dev/?/monitor.mod';
$rev = call($url, 'POST', array('mid' => '2', 'display_name' => '43333333333', 'steps' => '333'));
$rev = json_decode($rev, true);
var_dump($rev);

// 获取 monitor
$url = '127.0.0.1:9080/dev/?/monitor.json';
$rev = call($url, 'POST', array('page' => '1', 'page_count' => 20));
$rev = json_decode($rev, true);
var_dump($rev);
