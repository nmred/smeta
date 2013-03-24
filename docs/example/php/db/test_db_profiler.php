<?php

require_once 'core.php';

use lib\db\profiler\sw_profiler as sw_profiler;

$profiler = new sw_profiler(true);
$query_id = $profiler->query_start('select * from a');
usleep(100);
//$rev = $profiler->query_end($query_id);

$query = $profiler->get_query_profile($query_id);
$query->bind_params(array('sss', '222'));
P($query->get_query());
P($query->get_query_type());
P($query->has_ended());
P($query->get_query_params());
P($query->get_elapsed_secs());
P($query->get_started_microtime());
P($profiler);
