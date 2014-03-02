<?php
require_once 'core.php';

use \lib\rrd_store\sw_update;

$data = array(
	1 => 3,
	2 => 3,
	3 => 3,
	4 => 3,
	5 => 3,
	6 => 3,
);
$time = time();
$rev = sw_update::update('2_4', $data, $time);
sleep(2);
$data = array(
	8 => 2,
);
$rev = sw_update::update('2_4', $data, $time);

