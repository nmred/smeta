<?php

require_once 'core.php';

use \swan\gearman\sw_client;

$gwc = new sw_client();
//$gwc->add_servers_by_config('gmw_update_rrd');
$gwc->add_servers('127.0.0.1:4730');
$gwc->doBackground('reverse', 'ssssqwqq');
