<?php
require_once 'core.php';
use lib\db\sw_db_expr;

$expr = new lib\db\sw_db_expr('a = a + 1');

P($expr);
echo $expr;
