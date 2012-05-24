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

require_once PATH_SWAN_LIB . 'xml/sw_xml_build.class.php';
require_once PATH_SWAN_LIB . 'xml/sw_xml_abstract.class.php';

/**
+------------------------------------------------------------------------------
* 输出一个XML文件
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_xml_output_xml extends sw_xml_abstract
{
    // {{{ functions
    // {{{ public function output_xml()

    /**
     * 生成一个XML 
     * 
     * @param mixed $input 
     * @param array $options 
     * @access public
     * @return boolean 成功 true | 失败 false
     * @throw sw_xml_exception
     */
    public function output_xml($input, $options = array())
    {
        if (!is_array($options)) {
            $options = (array)$options;
        }
        $default = array(
                    'return' => 'simplexml',
                    'format' => 'tags',
                );
        $options = array_merge($default, $options);

        require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
        try {
            $simplexml = sw_xml_build::build($input, array_merge($options,array('return' => 'simplexml')));     
        } catch (sw_xml_exception $e) {
            throw new sw_xml_exception($e, '000100020001');
        }
        
        if ('' == $this->__filename || !is_string($this->__filename)) {
            throw new sw_xml_exception('Invalid path or filename', '000100010013'); 
        }
        if (file_put_contents($this->__filename, $simplexml->asXML()))
        {
            return true;
        }
        return false;
    }

    // }}}
    // }}}
}
