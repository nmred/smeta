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
* 系统异常处理类
+------------------------------------------------------------------------------
* 继承于 PHP 的 Exception, 并做了一些扩展.
* @uses Exception
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_exception extends Exception
{
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct 
     * 
     * @param string $message 
     * @param int $code 
     * @access public
     * @return void
     */
    public function __construct($message=null, $code=0, $replace = '')
    {
        //替换变量
        if (is_array($replace)) {
            $message = trim(gettext($message));
            $str_count = substr_count($message, '#%s#');
            if (0 != $str_count) {
                $msg_arr = explode('#%s#', $message);
            }
            $message = '';
            foreach ($replace as $key => $value) {
                if (isset($msg_arr[$key]) && $str_count > $key ) {
                    $message .= $msg_arr[$key] . $value;
                }
            }
        } else {
            $message = str_replace('#%s#', $replace, gettext($message));
        }
        parent::__construct($message, $code);    
    }

    // }}}
    // {{{ public function get_message()

    /**
     * catch 异常如果调用则返回带有错误码的提示信息 
     * 
     * @access public
     * @return string
     */
    public function get_message()
    {
        $message = '[' . $this->code . ']';
        $message .= $this->message;
        return $message;
    }
    // }}}
    // }}}
}
