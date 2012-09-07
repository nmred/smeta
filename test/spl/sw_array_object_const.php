
<?php                                                     

$a = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
$a['arr'] = 'array data';                             
$a->prop = 'prop data';                               
$b = new ArrayObject();                                   
$b['arr'] = 'array data';                             
$b->prop = 'prop data';                               

// ArrayObject Object                                     
// (                                                      
//      [prop] => prop data                               
// )                                                      
print_r($a);                                              

var_dump($a);
var_dump($b);
// ArrayObject Object                                     
// (                                                      
//      [arr] => array data                               
// )                                                      
print_r($b);                                              

?> 

