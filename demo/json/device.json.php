<?php
require 'core.php';

system('/usr/local/swan/app/sbin/sw_reinit_db');
$init = new \lib\init_data\sw_init_data();
$init->run();
