<?php
require_once 'core.php';

use \lib\rrd_graph\sw_graph;

$time = time();
$rev = sw_graph::graph('2_4', 1, array('time_grid' => sw_graph::T_60_MIN));
var_dump($rev);

