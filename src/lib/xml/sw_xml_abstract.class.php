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
 
/**
+------------------------------------------------------------------------------
* 处理XML的基类 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

abstract class sw_xml_abstract 
{
    // {{{ members 
    
    /**
     * 允许设置的属性 
     * 
     * @var array
     * @access protected
     */
    protected $__allowed_property = array(
                        'filename' => true,
                    );

    /**
     * 将要处理的xml文件，或将要生成的xml文件 
     * 
     * @var string
     * @access protected
     */
    protected $__filename = '';

    // }}}
    // {{{ functions 
    // {{{ public function __call()

    /**
     * 重载set_xxx()/get_xxx()系列方法 
     * 
     * @param string $name 
     * @param array $args 
     * @access public
     * @return mixed
     * @throw sw_xml_exception
     */
    public function __call($name, $args)
    {
        list($type, $attribute) = explode('_', $name, 2) + array('', '');
        $property = '__' . $attribute;

        if ('get' == $type) {
            if (isset($this->$property)) {
                return $this->$property;
            } else {
                return null;
            }
        } else if ('set' == $type) {
            if (true == array_key_exists($attribute, $this->__allowed_property)) {
                if (true == $this->__allowed_property[$attribute]) {
                    $this->$property = $args[0];
                } else {
                    require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
                    throw new sw_xml_exception("attribute #%s# don't allow set", '000100010001', $property);
                }
            } else {
                    require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
                    throw new sw_xml_exception("attribute #%s# don't exists", '000100010002', $property); 
            }
        } else {
            require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
            throw new sw_xml_exception("#%s# method undefined", '000100010003', $name);
        }
    }

    // }}}   
    // {{{ public function create_simplexml()
    
    /**
     * 利用xml文件创建simplexml对象
     * 调用必须通过set_filename()设置xml文件
     * 
     * @access public
     * @return array
     * @throw sw_xml_exception
     */
    public  function create_simplexml()
    {
        if (!isset($this->__filename)) {
            require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
            throw new sw_xml_exception("not set an XML file", '000100010004');
        }
        
        if ('.xml' != substr($this->__filename, strrpos($this->__filename, '.'))) {
            require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
            throw new sw_xml_exception("filetype not is xml", '000100010005');
        }
        
        $fp = fopen($this->__filename, 'r');
        $data = '';
        while(!feof($fp)) {
           $data .= fread($fp, 1024); 
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($data);
        if (!$xml) {
            $err_msg = '';
            foreach (libxml_get_errors() as $error) {
                $err_msg .= $error->message . "\n";
            }
            require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
            throw new sw_xml_exception($err_msg, '000100010006');
        }
        return $xml;
    }

    // }}}   
    // }}}
}
