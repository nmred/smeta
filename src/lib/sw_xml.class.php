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
* XML类工厂
+------------------------------------------------------------------------------
* 
* @package sw_xml
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_xml
{
    // {{{ functions
    // {{{ public static function factory()

    /**
     * XML类工厂 
     * 
     * @param string $package 创建对象名 
     * @static
     * @access public
     * @return sw_xml_* 
     */
    public static function factory($package) 
    {
        $class_name = 'sw_xml_' . $package;
        if (!class_exists($class_name)) {
            require_once PATH_SWAN_LIB . 'xml/' . $class_name . '.class.php';
        }

        if (!class_exists($class_name)) {
            require_once PATH_SWAN_LIB . 'xml/sw_xml_exception.class.php';
            throw new sw_xml_exception('Can not load #%s# class.', '000100010008', $class_name);
        }
        
        return new $class_name();
    }

    // }}}
    // }}}
}
