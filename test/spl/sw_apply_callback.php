<?php

/**
 * 回调函数 
 * 
 * @param Iterator $it 
 * @access public
 * @return bool
 */
function addCaps(Iterator $it)
{
	echo ucfirst($it->current()) . "\n";
	return true;
}

$array = array('dingo', 'wombat', 'wallaby');

try {
	$it = new ArrayIterator($array);	
	//调用迭代器的回调函数
	iterator_apply($it, 'addCaps', array($it));
} catch (Exception $e) {
	echo $e->getMessage();	
}

