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
 
require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_abstract.class.php';
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

	/**
	 * 存放request参数key值的别名 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__aliases = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->set_request_uri();	
	}

	// }}}
	// {{{ public function __get()

	/**
	 * get魔术方法，按照 1.GET 2.POST 3.COOKIE 4.SERVER 5.ENV的顺序返回
	 * 
	 * @param string $key 
	 * @access public
	 * @return void
	 */
	public function __get($key)
	{
		switch(true) {
			case isset($this->__params[$key]) :	
				return $this->__params[$key];
			case isset($_GET[$key]) :
				return $_GET[$key];
			case isset($_POST[$key]) :
				return $_POST[$key];
			case isset($_COOKIE[$key]) :
				return $_COOKIE[$key];
			case ('REQUEST_URI' === $key) :
				return $this->get_request_uri();
			case ('PATH_INFO' === $key) :
				return $this->get_pathinfo();
			case isset($_SERVER[$key]) :
				return $_SERVER[$key];
			case isset ($_ENV[$key]) :
				return $_ENV[$key];
			default:
				return null;
		}
	}

	// }}}
	// {{{ public function get()

	/**
	 * __get的别名 
	 * 
	 * @param string $key 
	 * @access public
	 * @return mixed
	 */
	public function get($key)
	{
		return $this->__get($key);	
	}

	// }}}
	// {{{ public function __set()
	
	/**
	 * 如果使用此种方式设置参数将抛出异常，用set_param()设置 
	 * 
	 * @param string $key 
	 * @param mixed $value 
	 * @access public
	 * @throw sw_controller_exception
	 * @return void
	 */
	public function __set($key, $value)
	{
		require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
		throw new sw_controller_request_exception("Setting values in superglobals not allowed; please use set_param()");
	}

	// }}}
	// {{{ public function set()

	/**
	 * __set()的别名 
	 * 
	 * @access public
	 * @return void
	 */
	public function set()
	{
		return $this->__set($key, $value);
	}

	// }}}
	// {{{ public function __isset()

	/**
	 * 检测一个参数是否存在 
	 * 
	 * @param string $key 
	 * @access public
	 * @return boolean
	 */
	public function __isset($key)
	{
		switch(true) {
			case isset($this->__params[$key]) :
				return true;
			case isset($_GET[$key]) :
				return true;	
			case isset($_POST[$key]) :
				return true;
			case isset($_COOKIE[$key]) :
				return true;
			case isset($_SERVER[$key]) :
				return true;
			case isset($_ENV[$key]) :
				return true;
			default:
				return false;
		}
	}

	// }}}
	// {{{ public funciton has()

	/**
	 * __isset()的别名 
	 * 
	 * @access public
	 * @param  string $key
	 * @return boolean
	 */
	public function has($key)
	{
		return $this->__isset($key);	
	}

	// }}}
	// {{{ public function set_query()
	
	/**
	 * 设置GET的值 
	 * 
	 * @param string|array $spec 
	 * @param null|mixed $value 
	 * @access public
	 * @return sw_controller_request_http
	 */
	public function set_query($spec, $value = null)
	{
		if ((null === $value) && !is_array($spec)) {
			require_once PATH_SWAN_LIB . 'controller/sw_controller_exception.class.php';	
			throw new sw_controller_exception("Invalid value passed to set_query(); must be either array of values or key/value pair");
		}

		if ((null === $value) && is_array($spec)) {
			foreach ($spec as $key => $value) {
				$this->set_query($key, $value);	
			}
			return $this;
		}

		$_GET[(string) $spec] = $value;
		return $this;
	}

	// }}}
	// {{{ public function get_query()

	/**
	 * 获取GET的值 
	 * 
	 * @param string $key 
	 * @param mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_query($key = null, $default = null) {
		if (null === $key) {
			return $_GET;	
		}

		return (isset($_GET[$key])) ? $_GET[$key] : $default;
	}

	// }}}
	// {{{ public function set_post()
	
	/**
	 * 设置POST的值 
	 * 
	 * @param string|array $spec 
	 * @param null|mixed $value 
	 * @access public
	 * @return sw_controller_request_http
	 */
	public function set_post($spec, $value = null)
	{
		if ((null === $value) && !is_array($spec)) {
			require_once PATH_SWAN_LIB . 'controller/sw_controller_exception.class.php';	
			throw new sw_controller_exception("Invalid value passed to set_post(); must be either array of values or key/value pair");
		}

		if ((null === $value) && is_array($spec)) {
			foreach ($spec as $key => $value) {
				$this->set_post($key, $value);	
			}
			return $this;
		}

		$_POST[(string) $spec] = $value;
		return $this;
	}

	// }}}
	// {{{ public function get_post()

	/**
	 * 获取POST的值 
	 * 
	 * @param string $key 
	 * @param mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_post($key = null, $default = null) {
		if (null === $key) {
			return $_POST;	
		}

		return (isset($_POST[$key])) ? $_POST[$key] : $default;
	}

	// }}}
	// {{{ public function get_cookie()

	/**
	 * 获取COOKIE的值 
	 * 
	 * @param null|string $key 
	 * @param null|mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_cookie($key = null, $default = null)
	{
		if (null === $key) {
			return $_COOKIE;	
		}

		return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : $default;
	}

	// }}}
	// {{{ public function get_server()

	/**
	 * 获取SERVER的值 
	 * 
	 * @param null|string $key 
	 * @param null|mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_server($key = null, $default = null)
	{
		if (null === $key) {
			return $_SERVER;	
		}

		return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
	}

	// }}}
	// {{{ public function get_env()

	/**
	 * 获取ENV的值 
	 * 
	 * @param null|string $key 
	 * @param null|mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_env($key = null, $default = null)
	{
		if (null === $key) {
			return $_ENV;	
		}

		return (isset($_ENV[$key])) ? $_ENV[$key] : $default;
	}

	// }}}
	// {{{ public function set_request_uri()
	
	/**
	 * 设置request_uri 
	 * 
	 * @param null|string $request_uri 
	 * @access public
	 * @return sw_controller_request_http
	 */
	public function set_request_uri($request_uri = null)
	{
		if (null === $request_uri) {
			if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // check this first so IIS will catch
				$request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
			} elseif (
				// IIS7 with URL Rewrite: make sure we get the unencoded url (double slash problem)
				isset($_SERVER['IIS_WasUrlRewritten'])
				&& $_SERVER['IIS_WasUrlRewritten'] == '1'
				&& isset($_SERVER['UNENCODED_URL'])
				&& $_SERVER['UNENCODED_URL'] != '' 
				) {
				$request_uri = $_SERVER['UNENCODED_URL'];	
			} elseif (isset($_SERVER['REQUEST_URI'])) {
				$request_uri = $_SERVER['REQUEST_URI'];
				$scheme_and_http_post = $this->get_scheme() . '://' . $this->get_http_host();
				if (0 === strpos($request_uri, $scheme_and_http_post)) {
					$request_uri = substr($request_uri, strlen($scheme_and_http_post));	
				}
			} elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
				$request_uri = $_SERVER['ORIG_PATH_INFO'];	
				if (!empty($_SERVER['QUERY_STRING'])) {
					$request_uri .= '?' . $_SERVER['QUERY_STRING'];	
				}
			} else {
				return $this;	
			}
		} elseif (!is_string($request_uri)) {
			return $this;	
		} else {
			if (false !== ($pos == strpos($request_uri, '?'))) {
				$query = substr($request_uri, $pos + 1);
				parse_str($query, $vars);
				$this->set_query($vars);	
			}	
		}
		
		$this->__request_uri = $request_uri;
		return $this;
	}

	// }}}
	// {{{ public function get_request_uri()

	/**
	 * 获取request_uri 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_request_uri()
	{
		if (empty($this->__request_uri)) {
			$this->set_request_uri();	
		}

		return $this->__request_uri;
	}

	// }}}
	// {{{ public function get_scheme()

	/**
	 * 获取HTTP的协议 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_scheme()
	{
		return ($this->get_server('HTTPS') == 'on') ? self::SCHEME_HTTPS : self::SCHEME_HTTP;
	}

	// }}}
	// {{{ public function get_http_host()

	/**
	 * 获取http的主机名 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_http_host()
	{
		$host = $this->get_server('HTTP_HOST');
		if (!empty($host)) {
			return $host;	
		}

		$scheme = $this->get_scheme();
		$name   = $this->get_server('SERVER_NAME');
		$port   = $this->get_server('SERVER_PORT');
		
		if (null === $name) {
			return '';	
		} elseif (($scheme == self::SCHEME_HTTP && $port == 80) || ($scheme == self::SCHEME_HTTPS && $port == 443)) {
			return $name;	
		} else {
			return $name . ':' . $port;	
		}
	}

	// }}}
	// }}} function ends
}
