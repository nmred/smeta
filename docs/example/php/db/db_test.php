<?php
require_once 'core.php';
require_once PATH_SWAN_LIB . 'sw_db.class.php';
$__db = sw_db::factory();

$select = $__db->select();

$select->distinct(true);
$select->from('aaa');
P($select);
