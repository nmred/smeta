<?php

foreach (get_class_methods(new ArrayObject()) as $key => $method) {
	echo $key,"->",$method, "\n";	
}
