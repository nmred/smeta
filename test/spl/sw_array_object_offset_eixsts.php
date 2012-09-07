<?php

//重点是演示ArrayObject中的offsetExists()方法

$array = array('koala', 'kangaroo', 'wombat', 'wallaby', 'emu', 'kiwi', 'kookaburra', 'platypeus');

$array_obj = new ArrayObject($array);

if ($array_obj->offsetExists(3)) {
	echo "Offset Exists\n";	
} else {
	echo "Not Exists\n";	
}
