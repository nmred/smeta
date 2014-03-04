#!/usr/local/swan/smeta/opt/bin/php 
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
	require_once PATH_SWAN_LIB . 'snmp/sw_snmp_version_one.class.php';

	$snmp = new sw_snmp_version_one();
	$snmp->set_object_id('.1.3.6.1.4.1.2021.11.9.0')
		 ->set_host('192.168.56.131')
		 ->set_timeout(5)
		 ->set_community('public');
	$string = '/usr/local/swan/smeta/opt/rrdtool/bin/rrdtool update /usr/local/swan/smeta/opt/rrdtool/bin/cpu.rrd ';
	$string .= ' --template cpu ' . time() . ':' . trim($snmp->get_next());
//	echo $string . "\n";
	exec($string, $rev);
	echo $snmp->get_next() . "\n";
	echo $snmp->get();
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
			sleep(1);
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

