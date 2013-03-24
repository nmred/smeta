<?php
require_once 'core.php';

use lib\db\adapter\sw_mysql as sw_mysql;

$db = new sw_mysql();
P($db);
$db->set_profiler();
P($db);
P($db->get_profiler());
