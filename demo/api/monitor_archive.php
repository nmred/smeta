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
//$url = '127.0.0.1:9080/dev/?/monitor_archive.add';
//$rev = call($url, 'POST', array('mid' => 1, 'cf_type' => 2, 'xff' => 0.5, 'title' => 'test', 'steps' => 300, 'rows' => 20));
//$rev = json_decode($rev, true);
//var_dump($rev);

// 删除 monitor
$url = '127.0.0.1:9080/dev/?/monitor_archive.del';
$rev = call($url, 'POST', array('mid' => '1', 'arid' => '6'));
$rev = json_decode($rev, true);
var_dump($rev);

// 修改 monitor 属性
//$url = '127.0.0.1:9080/dev/?/monitor_archive.mod';
//$rev = call($url, 'POST', array('arid' => 6, 'mid' => 1, 'cf_type' => 3, 'xff' => 0.5, 'title' => 'test', 'steps' => 300, 'rows' => 40));
//$rev = json_decode($rev, true);
//var_dump($rev);

//// 获取 monitor
$url = '127.0.0.1:9080/dev/?/monitor_archive.json';
$rev = call($url, 'POST', array('mid' => 1, 'page' => '1', 'page_count' => 20));
$rev = json_decode($rev, true);
var_dump($rev);
