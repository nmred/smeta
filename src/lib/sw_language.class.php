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
* 多语言支持处理类
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_language
{
    // {{{ members

    /**
     * 语言名称 
     * 
     * @var string
     * @access protected
     */
    protected $__lang = 'zh_CN';

    // }}}
    // {{{ functions
    // {{{ public static function set_gettext()

    /**
     * 对gettext进行设置 
     * 
     * @static
     * @access public
     * @return void
     */
    public static function set_gettext()
    {
        $lang = SWAN_LANG_DEFAULT;
        $lang_char = $lang . SWAN_CHARSET;
        putenv("LANG=$lang");
        putenv("LANGUAGE=$lang");
        setlocale(LC_ALL, $lang);
        bindtextdomain(SWAN_GETTEXT_DOMAIN, PATH_SWAN_LOCALE);
        bind_textdomain_codeset(SWAN_GETTEXT_DOMAIN, SWAN_CHARSET);
        textdomain(SWAN_GETTEXT_DOMAIN);
    }

    // }}}
    // }}}
}
