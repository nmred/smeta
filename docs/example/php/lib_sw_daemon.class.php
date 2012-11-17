#!/usr/local/swan/opt/php/bin/php 
<?php
require_once 'core.php';
require_once PATH_SWAN_LIB . 'daemon/sw_daemon.class.php';
declare(ticks = 1);
$cmd = $_SERVER['argv'][1];
$daemon_conf = array(
	'pid_file_name' => 'mydaemon.pid',
	'pid_file_path' => PATH_SWAN_RUN . 'deamon',
	'verbose'     => true
);
function my_handler1()
{
	sleep(5);
	echo "This handler1 works./n";
}
function my_handler2()
{
	echo "This handler2 works./n";
	file_put_contents('a.txt', "assasaa\n" . time(), FILE_APPEND);
}
try {
	$daemon = new sw_daemon($daemon_conf);
	if ($cmd == 'start') {
		$daemon->add_signal_handler(SIGUSR1, 'my_handler1');
		$daemon->add_signal_handler(SIGUSR2, 'my_handler2');
		$daemon->start();
		for (;;) {
			echo "running./n";
			$daemon->send_signal(SIGUSR2);
			sleep(300);
		}
	} elseif ($cmd == 'stop') {
		$daemon->stop();
	} else {
		die("unknown action.");
	}
} catch (sw_daemon_exception $e) {
	echo $e->getMessage();
	echo "/n";
}
?>

