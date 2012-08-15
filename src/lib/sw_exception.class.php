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
	// {{{ members

	/**
	 * 前一个异常链 
	 * 
	 * @var null | Exception
	 * @access private
	 */
	private $__previous = null;

	// }}}
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
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
		if (version_compare(PHP_VERSION, '5.3.0', '<')) {
			parent::__construct($message, (int) $code);
			$this->__previous = $previous;	
		} else {
			parent::__construct($message, (int) $code, $previous);	
		}
    }

    // }}}
	// {{{ public function __call()

	/**
	 * 重写，如果PHP<5.3.0,提供get_previous()方法 
	 * 
	 * @param string $method 
	 * @param array $args 
	 * @access public
	 * @return mixed
	 */
	public function __call($method, array $args)
	{
		if ('get_previous' == strtolower($method)) {
			return $this->_get_previous();	
		}
		return null;
	}

	// }}}
	// {{{ public function __toString()

	/**
	 * __toString 
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		if (version_compare(PHP_VERSION, '5.3.0', '<')) {
			if (null != ($e = $this->get_previous())) {
				return $e->__toString()
					   . "\n\nNext "
					   . parent::__toString();	
			}	
		}
		return parent::__toString();
	}

	// }}}
	// {{{ protected function _get_previous()

	/**
	 * 获取前一个异常错误
	 * 
	 * @access protected
	 * @return Exception | null
	 */
	protected function _get_previous()
	{
		return $this->__previous;		
	}

	// }}}
    // }}}
}
