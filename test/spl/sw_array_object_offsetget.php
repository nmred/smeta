<?php

//重点是演示ArrayObject中的offsetGet()方法

$array = array('koala', 'kangaroo', 'wombat', 'wallaby', 'emu', 'kiwi', 'kookaburra', 'platypeus');

$array_obj = new ArrayObject($array);
$value = $array_obj->offsetGet(4);
echo $value;
