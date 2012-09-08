<?php

class my_thread extends Thread
{
	public function run()
	{
		echo "当前运行的线程数：",$this->getCount();
		echo "当前运行的线程PEAK：",$this->getPeak();
		$time_s = time();
		// 线程干活
		while (2 >= time() - $time_s) {
			base64_encode('long long long long long long string');
		}
	}
}

$array_threads = array();
// 线程数
$tn = 8;

// 创建线程
for ($i = 0; $i < $tn; $i++) {
	echo "create thread $i" . PHP_EOL;
	$array_threads[$i] = new my_thread();
}


// 让线程运行起来
for ($i = 0; $i < $tn; $i++) {
	echo "thread $i start" . PHP_EOL;
	$array_threads[$i]->start();
}
