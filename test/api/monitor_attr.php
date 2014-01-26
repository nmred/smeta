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
//$url = '127.0.0.1:9080/dev/?/monitor_attr.add';
//$rev = call($url, 'POST', array('name' => 'ss21222' . time(), 'form_type' => '1', 'mid' => 1, 'display_name' => 'eeee'));
//$rev = json_decode($rev, true);
//var_dump($rev);

// 删除 monitor
//$url = '127.0.0.1:9080/dev/?/monitor_attr.del';
//$rev = call($url, 'POST', array('mid' => '2', 'aid' => '1'));
//$rev = json_decode($rev, true);
//var_dump($rev);

//// 修改 monitor 属性
//$url = '127.0.0.1:9080/dev/?/monitor_attr.mod';
//$rev = call($url, 'POST', array('mid' => 1, 'aid' => '1', 'form_type' => 20, 'display_name' => 'hiiiiiiiiiiiiihh'));
//$rev = json_decode($rev, true);
//var_dump($rev);

//// 获取 monitor
$url = '127.0.0.1:9080/dev/?/monitor_attr.json';
$rev = call($url, 'POST', array('mid' => 1, 'page' => '1', 'page_count' => 20));
$rev = json_decode($rev, true);
var_dump($rev);
