<?php
require_once 'core.php';

use \lib\rrd_store\sw_update;

$rev = sw_update::update('2_4_8', array('time' => time(), 'value' => 1));
