<?php
require_once 'core.php';

use \lib\rrd_graph\sw_graph;

$time = time();
$rev = sw_graph::graph('2_4', 1, $time);
var_dump($rev);

