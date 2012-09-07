<?php

//重点是演示ArrayObject中的offsetUnset()方法

$array = array('koala', 'kangaroo', 'wombat', 'wallaby', 'emu', 'kiwi', 'kookaburra', 'platypeus');

$array_obj = new ArrayObject($array);

$array_obj->offsetUnset(5);
for ($iterator = $array_obj->getIterator(); $iterator->valid(); $iterator->next()) {
	echo $iterator->key(),"=>" , $iterator->current() ,"\n";	
}
