<?php

//重点是演示ArrayObject中的count()方法

$array = array('koala', 'kangaroo', 'wombat', 'wallaby', 'emu', 'kiwi', 'kookaburra', 'platypeus');

$array_obj = new ArrayObject($array);

echo $array_obj->count();
