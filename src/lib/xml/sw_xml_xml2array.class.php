<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +---------------------------------------------------------------------------
// | SWAN [ $_SWANBR_SLOGAN_$ ]
// +---------------------------------------------------------------------------
// | Copyright $_SWANBR_COPYRIGHT_$
// +---------------------------------------------------------------------------
// | Version  $_SWANBR_VERSION_$
// +---------------------------------------------------------------------------
// | Licensed ( $_SWANBR_LICENSED_URL_$ )
// +---------------------------------------------------------------------------
// | $_SWANBR_WEB_DOMAIN_$
// +---------------------------------------------------------------------------
 
require_once PATH_SWAN_LIB . 'xml/sw_xml_abstract.class.php';

/**
+------------------------------------------------------------------------------
* XML转化数组类
+------------------------------------------------------------------------------
* 
* @uses sw_xml_abstract
* @package sw_xml 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_xml_xml2array extends sw_xml_abstract
{
    // {{{ functions
    // {{{ public function xml2array()

    /**
     * 将xml文件转化为数组
     * 
     * @param DOMNode|SimpleXMLElement $obj 
     * @access public
     * @return array
     * @throw sw_xml_exception
     */
    public function xml2array($obj = null)
    {
        if (is_null($obj)) {
            $obj = self::create_simplexml();    
        }

        if ($obj instanceof DOMNode) {
            $obj = simplexml_import_dom($obj);
        }

        if (!($obj instanceof SimpleXMLElement)) {
            require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
            throw new sw_xml_exception("The input is not instance of SimpleXMLElement, DOMDocument or DOMNode.", '000100010007');
        }

        $result = array();
        $namespaces = array_merge(array('' => ''), $obj->getNamespaces(true));
        self::_recursive_xml2array($obj, $result, '', array_keys($namespaces));
        return $result;
    }

    // }}} 
    // {{{ protected static function recursive_xml2array()

    /**
     * 递归转化xml为数组 
     * 
     * @param SimpleXMLElement $xml 一个simplexml对象
     * @param array $parentData 通过引用返回的数组
     * @param string $ns 命名空间名
     * @param array $namespaces 命名空间名数组
     * @static
     * @access protected
     * @return void
     */
    protected static function _recursive_xml2array($xml, &$parentData, $ns, $namespaces) 
    {
        $data = array();

        foreach ($namespaces as $namespace) {
            foreach ($xml->attributes($namespace, true) as $key => $value) {
                if (!empty($namespace)) {
                    $key = $namespace . ':' . $key;
                }
                $data['@' . $key] = (string)$value;
            }

            foreach ($xml->children($namespace, true) as $child) {
                self::_recursive_xml2array($child, $data, $namespace, $namespaces);
            }
        }

        $asString = trim((string)$xml);
        if (empty($data)) {
            $data = $asString;
        } else if (!empty($asString)) {
            $data['@'] = $asString;
        }

        if (!empty($ns)) {
            $ns .= ':';
        }
        $name = $ns . $xml->getName();
        if (isset($parentData[$name])) {
            if (!is_array($parentData[$name]) || !isset($parentData[$name][0])) {
                $parentData[$name] = array($parentData[$name]);
            }

            $parentData[$name][] = $data;
        } else {
            $parentData[$name] =$data;
        }
    }

    // }}} 
    // }}}
}
