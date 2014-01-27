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
//$rev = call($url, 'POST', array('aid' => '2', 'mid' => 1, 'did' => 3, 'value' => 'sssss'));
//$rev = json_decode($rev, true);
//var_dump($rev);

//// 获取 device
//$url = '127.0.0.1:9080/user/?/device_monitor.json';
//$rev = call($url, 'POST', array('did' => 3));
//$rev = json_decode($rev, true);
//var_dump($rev);

// 删除 device monitor
//$url = '127.0.0.1:9080/user/?/device_monitor.del';
//$rev = call($url, 'POST', array('vid' => 1));
//$rev = json_decode($rev, true);
//var_dump($rev);

// 修改 device
//$url = '127.0.0.1:9080/user/?/device_monitor.mod';
//$rev = call($url, 'POST', array('vid' => 3, 'value' => 'aaa'));
//$rev = json_decode($rev, true);
//var_dump($rev);
