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

// {{{
/**
+------------------------------------------------------------------------------
* 初始化一个simplexml或DOM对象 由xml字符串、文件路劲、URL或数组
+------------------------------------------------------------------------------
* ##@## Usage
* 由字符串创建XML对象
*
* $xml = sw_xml_build('<example>text</example>');
* 
* 由字符串创建XML对象，输出DOM
* 
* $xml = sw_xml_build('<example>text</example>', array('return' => 'domdocument'));
*
* 由XML文件创建对象
*
* $xml = sw_xml_build('/path/to/an/xml/file.xml');
*
* 由URL地址创建XML对象
*
* $xml = sw_xml_build('http://example.com/example.xml');
*
* 由数组创建XML对象
*
* $value = array (
*   'tags' => array(
*       array (
*           'id' => '1',
*           'name' => 'default',
*       ),
*       array (
*           'id' => '2',
*           'name' => 'enhancement',
*       ),
*   )        
* )
* $xml = sw_xml_build($value);
* 当利用数组创建XML时需要注意根数组必须是一个
* 
* ##@## Options
* 
* -- `return` 可以是'simplexml'返回SimpleXMLElement对象，或'domdocument' 返回DOMDocment 
* -- `format` 只在通过数组创建XML对象有效，'tags' 全部是以标签创建，'attribute'是支持属性定义的
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
// }}} 
class sw_xml_build
{
    // {{{ functions
    // {{{ public static function build() 

    /**
     * 创建XML对象 
     * 
     * @param mixed $input 
     * @param array $options 
     * @static
     * @access public
     * @return SimpleXMLElement|DOMDocument SimpleXMLElement or DOMDocument
     * @throw sw_xml_exception
     */
    public static function build($input, $options = array())
    {
        if (!is_array($options)) {
            $options = array('return' => (string)$options);
        }
        $defaults = array (
                    'return' => 'simplexml',        
                );
        $options = array_merge($defaults, $options);
        
        if (is_array($input) || is_object($input)) {
            return self::fromArray((array)$input, $options);
        } else if (strpos($input, '<') !== false) {
            if ($options['return'] === 'simplexml' || $options['return'] === 'simplexmlelement') {
                return new SimpleXMLElement($input, LIBXML_NOCDATA);
            }
            $dom = new DOMDocument();
            $dom->loadXML($input);
            return $dom;
        } else if (file_exists($input) || strpos($input, 'http://') === 0 || strpos($input, 'https://') === 0) {
            if ($options['return'] === 'simplexml' || $options['return'] === 'simplexmlelement') {
                return new SimpleXMLElement($input, LIBXML_NOCDATA, true);
            }
            $dom = new DOMDocument();
            $dom->load($input);
            return $dom;
        } else if (!is_string($input)) {
            require_once PATH_SWAN_LIB . 'xml/sw_exception.class.php';
            throw new sw_xml_exception('Invalid input.', '000100010009');
        } 
        require_once PATH_SWAN_LIB . 'xml/sw_exception.class.php';
        throw new sw_xml_exception('XML cannot be read.', '000100010010');
    }

    // }}}
    // {{{ public static function fromArray()

    /**
     * 通过数组创建XML 
     * 
     * @param array $input 
     * @param array $options 
     * @static
     * @access public
     * @return SimpleXMLElement|DOMDocument SimpleXMLElement or DOMDocument
     * @throw sw_swan_exception
     */
    public static function fromArray($input, $options = array())
    {
        if (!is_array($input) || count($input) !== 1) {
            require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
            throw new sw_xml_exception('Invalid input.', '00010010011');
        }
        $key = key($input);
        if (is_integer($key)) {
            require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
            throw new sw_xml_exception('The key of input must be alphanumeric', '00010010012'); 
        }

        if (!is_array($options)) {
            $options = array('format' => (string)$options);
        }
        $defaults = array(
                    'format'    => 'tags',
                    'version'   => '1.0',
                    'encoding'  => SWAN_CHARSET,
                    'return'    => 'simplexml',
                );
        $options = array_merge($defaults, $options);

        $dom = new DOMDocument($options['version'], $options['encoding']);
        self::_fromArray($dom, $dom, $input, $options['format']);
        $options['return'] = strtolower($options['return']);
        if ($options['return'] === 'simplexml' || $options['return'] === 'simplexmlelement') {
            return new SimpleXMLElement($dom->saveXML());
        }
        return $dom;
    }

    // }}}
    // {{{ protected function _fromArray()
    
    /**
     * 递归完成由数组创建XML 
     * 
     * @param DOMDocument $dom 
     * @param DOMElement $node
     * @param array $data 
     * @param string $format 
     * @access protected
     * @return void
     */
    protected function _fromArray($dom, $node, &$data, $format)
    {
        if (empty($data) || !is_array($data)) {
            return;
        }
        foreach ($data as $key => $value) {
            if (is_string($key)) {
                if (!is_array($value)) {
                    if (is_bool($value)) {
                        $value = (int)$value;
                    } else if ($value === null) {
                        $value = '';
                    }
                    $isNamespace = strpos($key, 'xmlns:');
                    if ($isNamespace !== false) {
                        $node->setAttributeNS('http://www.w3.org/2000/xmlns/', $key, $value);
                        continue;
                    }
                    if ($key[0] !== '@' && $format === 'tags') {
                        $child = null;
                        if (!is_numeric($value)) {
                            $child = $dom->createElement($key, '');
                            $child->appendChild(new DOMText($value));
                        } else {
                            $child = $dom->createElement($key, $value);
                        }
                        $node->appendChild($child);
                    } else {
                        if ('@' === $key[0]) {
                            $key = substr($key, 1);
                        }
                        $attribute = $dom->createAttribute($key);
                        $attribute->appendChild($dom->createTextNode($value));
                        $node->appendChild($attribute);
                    }
                } else {
                    if ($key[0] === '@') {
                        require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
                        throw new sw_xml_exception('Invalid array', '000100010012');
                    }
                    if (is_numeric(implode('', array_keys($value)))) { // List
                        foreach ($value as $item) {
                            $itemData = compact('dom', 'node', 'key', 'format');
                            $itemData['value'] = $item;
                            self::_createChild($itemData);
                        }
                    } else {
                        self::_createChild(compact('dom', 'node', 'key', 'value', 'format'));
                    }
                }
            } else {
                require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
                throw new sw_xml_exception('Invalid array', '000100010012');
            }
        }
    }

    // }}}
    // {{{ protected function __createChild()
    
    /**
     * 创建子节点 
     * 
     * @param array $data
     * @access protected
     * @return void
     */
    protected function _createChild($data)
    {
        extract($data);
        $childNS = $childValue = null;
        if (is_array($value)) {
            if (isset($value['@'])) {
                $childValue = (string)$value['@'];
                unset($value['@']);
            }
            if (isset($value['xmlns:'])) {
                $childNS = $value['xmlns:'];
                unset($value['xmlns:']);
            }
        } else if (!empty($value) || $value === 0) {
            $childValue = (string)$value;
        }

        if ($childValue) {
            $child = $dom->createElement($key, $childValue);
        } else {
            $child = $dom->createElement($key);
        }
        if ($childNS) {
            $child->setAttribute('xmlns', $childNS);
        }

        self::_fromArray($dom, $child, $value, $format);
        $node->appendChild($child);
    }

    // }}}
    // }}}
}
