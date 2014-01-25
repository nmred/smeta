<?php
require_once 'core.php';
use \lib\log\sw_log;
$options = \lib\log\sw_log::get_logsvr_config();
$options = array_merge($options, array('log_id' => 2));
$writer = sw_log::writer_factory('logsvr', $options);
$message = sw_log::message_factory('phpd');
$message->message = 'swdata';
$log = new \lib\log\sw_log();
$log->add_writer($writer);

$config = array(
		"enable" => 1,
		"proc_num" => 2,
		"debug" => 0,
		"listen_host" => "0.0.0.0",
		"listen_port" => 9080,
		"timeout" => 30,
		"max_body" => 1048576,
		"max_header" => 8192,
);
$process = new \lib\process\sw_swdata();
$process->set_log($log);
$process->set_message($message);
$process->set_proc_config($config);
$process->run();
