<?php

//测试程序

require_once './core.php';

require_once PATH_SWAN_LIB . 'sw_xml.class.php';

$xml = sw_xml::factory('xml2array');


try {
    $xml->set_filename('exception_info.xml');
    print_r($xml->xml2array());
} catch(sw_xml_exception $e) {
    echo $e->getMessage();
}

