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
* HTTP请求类
+------------------------------------------------------------------------------
* 
* @uses sw_controller_request_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_request_http extends sw_controller_request_abstract
{
	// {{{ consts

	/**
	 * http请求方式的描述  
	 */
	const SCHEME_HTTP = 'http';		

	/**
	 * https请求方式的描述  
	 */
	const SCHEME_HTTPS = 'https';
	
	// }}}	
	// {{{ members

	/**
	 * 允许的参数来源 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__param_sources = array('_GET', '_POST');

	/**
	 * REQUEST_URI 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__request_uri;

	/**
	 * 请求URL的基地址 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__base_url = null;

	/**
	 * 请求路劲的基地址 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__base_path = null;

	/**
	 * PATH_INFO 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__path_info = '';

	/**
	 * 设置的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__params = array();

	/**
	 * 存放原始的POST数据 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__raw_body;

	// }}}
}
