<?php

//重点是演示ArrayObject中的getArrayCopy()方法

$array = array('koala', 'kangaroo', 'wombat', 'wallaby', 'emu', 'kiwi', 'kookaburra', 'platypeus');

$array_obj = new ArrayObject($array);

$array_copy_obj = $array_obj->getArrayCopy();
print_r($array_copy_obj);
print_r($array_obj);
//for ($iterator = $array_copy_obj->getIterator(); $iterator->valid(); $iterator->next()) {
//	echo $iterator->key(),"=>" , $iterator->current() ,"\n";	
//}
