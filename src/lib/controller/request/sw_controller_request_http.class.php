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
* controller请求类
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_request_http extends sw_controller_request_abstract
{
    // {{{ const

    /**
     * Scheme for http 
     */
    const SCHEME_HTTP = 'http';

    /**
     * Scheme for https 
     */
    const _HTTPS = 'https';

     // }}} end const
    // {{{ members 
    
    /**
     * 参数允许的设置的来源 
     * 
     * @var string
     * @access protected
     */
    protected $__param_sources = array ('_GET', '_POST', '_REQUEST');
    
    /**
     * 用来指定要访问的页面
     * 
     * @var string
     * @access protected
     */
    protected $__request_uri;

    /**
     * 访问的根url
     * 
     * @var string
     * @access protected
     */
    protected $__base_url = null;

    /**
     * 请求的根路径 
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
     * 相对路劲 
     * 
     * @var string
     * @access protected
     */
    protected $__relative_path = '';

    /**
     * 实例化参数 
     * 
     * @var array
     * @access protected
     */
    protected $__params = array();

    /**
     * 请求别名 
     * 
     * @var array
     * @access protected
     */
    protected $__aliases = array();

    // }}} end members 
    // {{{ functions
    // {{{ public function __construct()
    
    /**
     * 构造方法 当有$uri请求时设置请求地址 
     * 
     * @param string $uri 
     * @access public
     * @return void
     */
    public function __construct($uri = null)
    {
        if (null !== $uri) {
            $this->set_request_uri($uri);
        } else {
            $this->set_request_uri();
        }
    }

    // }}}
    // {{{ public function __get()

    /**
     * get魔术方法 返回超全局数组和公有变量
     * 优先级：1.GET 2.POST 3. COOKIE 4. SERVER 5. ENV 6. SESSION
     * 
     * @param string $key 
     * @access public
     * @return mixed
     */
    public function __get($key)
    {
        switch(true) {
            case isset($this->__params[$key]) :
                return $this->__params[$key];
                //此处省略break;
            case isset($_GET[$key]) :
                return $_GET[$key];
                //此处省略break;
            case isset($_POST[$key]) :
                return $_POST[$key];
                //此处省略break;
            case isset($_REQUEST[$key]) :
                return $_REQUEST[$key];
                //此处省略break;
            case isset($_SESSION[$key]) :
                return $_SESSION[$key];
                //此处省略break;
            case isset($_COOKIE[$key]) :
                return $_COOKIE[$key];
                //此处省略break;
            case ($key == 'REQUEST_URI') :
                return $this->get_request_uri();
                //此处省略break;
            case ($key == 'PATH_INFO') :
                return $this->get_path_info();
                //此处省略break;
            case isset($_SERVER[$key]) :
                return $_SERVER[$key];
                //此处省略break;
            case isset($_ENV[$key]) :
                return $_ENV[$key];
                //此处省略break;
            default:
                return null;
        }
    }

    // }}}
    // {{{ public function get()

    /**
     * 方法__get()的别名 
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
     * __set() 魔术方法 
     * 
     * @param string $key 
     * @param mixed $value 
     * @access public
     * @return void
     * @throw sw_controller_request_exception
     */
    public function __set($key, $value) 
    {
        require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
        throw new sw_controller_request_exception('Setting values in superglobals not allowed; pleaseuse set_param()', '000100020001')
    }

    // }}}
    // {{{ public function set()

    /**
     * __set()的别名 
     * 
     * @param string $key 
     * @param mixed $value 
     * @access public
     * @return void
     */
    public function set($key, $value)
    {
        return  $this->__set($key, $value);
    }

    // }}}
    // {{{ public function __isset()

    /**
     * 检查某个成员是否存在 
     * 
     * @param string $key 
     * @access public
     * @return boolean
     */
    public function __isset($key)
    {
        switch (true) {
            case isset($this->__params[$key]) :
                return true;
            case isset($_GET[$key]) :
                return true;
            case isset($_POST[$key]) :
                return true;
            case isset($_REQUEST[$key]) :
                return true;
            case isset($_SESSION[$key]) :
                return true;
            case isset($_COOKIE[$key]) :
                return true;
            case isset($_SERVER['$key']) :
                return true;
            case isset($_ENV[$key]) :
                return true;
            default:
                return false;
        }
    }

    // }}}
    // {{{ public function has()

    /**
     * __isset()的别名 
     * 
     * @param string $key 
     * @access public
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
     * @return object sw_controller_request_http 
     */
    public function set_query($spec, $value = null)
    {
        if ((null === $value) && !is_array($spec)) {
            require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
            throw new sw_controller_request_exception('Invalid value passed to set_query(); must be either array of values or key/value pair', '000100020002');
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
     * 从超全局变量GET中获取一个
     *
     * 如果$key不存没有设置返回$_GET全部，如果设置了不存在返回默认值
     *
     * @param string $key 
     * @param mixed|null $default 
     * @access public
     * @return mixed
     */
    public function get_query($key = null, $default = null)
    {
        if (null == $key) {
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
     * @return object sw_controller_request_http 
     */
    public function set_post($spec, $value = null)
    {
        if ((null === $value) && !is_array($spec)) {
            require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
            throw new sw_controller_request_exception('Invalid value passed to set_post(); must be either array of values or key/value pair', '000100020003');
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
     * 从超全局变量POST中获取一个
     *
     * 如果$key不存没有设置返回$_POST全部，如果设置了不存在返回默认值
     *
     * @param string $key 
     * @param mixed|null $default 
     * @access public
     * @return mixed
     */
    public function get_post($key = null, $default = null)
    {
        if (null == $key) {
            return $_POST;
        }

        return (isset($_POST[$key])) ? $_POST[$key] : $default;
    }

    // }}}
    // {{{ public function set_request()
    
    /**
     * 设置REQUEST的值 
     * 
     * @param string|array $spec 
     * @param null|mixed $value 
     * @access public
     * @return object sw_controller_request_http 
     */
    public function set_request($spec, $value = null)
    {
        if ((null === $value) && !is_array($spec)) {
            require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
            throw new sw_controller_request_exception('Invalid value passed to set_request(); must be either array of values or key/value pair', '000100020004');
        }
        if ((null === $value) && is_array($spec)) {
            foreach ($spec as $key => $value) {
                $this->set_request($key, $value);
            }
            return $this;
        }
        $_REQUEST[(string) $spec] = $value;
        return $this;
    }

    // }}} 
    // {{{ public function get_request()
    
    /**
     * 从超全局变量REQUEST中获取一个
     *
     * 如果$key不存没有设置返回$_REQUEST全部，如果设置了不存在返回默认值
     *
     * @param string $key 
     * @param mixed|null $default 
     * @access public
     * @return mixed
     */
    public function get_request($key = null, $default = null)
    {
        if (null == $key) {
            return $_REQUEST;
        }

        return (isset($_REQUEST[$key])) ? $_REQUEST[$key] : $default;
    }

    // }}} 
    // {{{ public function get_cookie()

    /**
     * 获取$_COOKIE值 
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
    // {{{ public function set_cookie()

    /**
     * 设置COOKIE 
     * 
     * @param string $key 
     * @param mixed|null $value 
     * @access public
     * @return object sw_controller_request_http
     * $throw sw_controller_request_exception
     */
    public function set_cookie($key, $value = null) 
    {
        if (null == $key) {
            require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
            throw new sw_controller_request_exception('Invalid value passed to set_cookie(); must be either array of values or key pair', '000100020005');
        }
        $_COOKIE[$key] = $value;
        return $this;
    }

    // }}} 
    // {{{ public function unset_cookie()

    /**
     * unset一个COOKIE 
     * 
     * @param string $key 
     * @access public
     * @return object sw_controller_request_http
     */
    public function unset_cookie($key)
    {
        if (null === $key) {
            require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
            throw new sw_controller_request_exception('Invalid value passed to unset_cookie(); must be emter the values or key pair', '000100020006'); 
        }

        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
        }

        return $this;
    }

    // }}}
    // {{{ public function get_session()

    /**
     * 获取$_SESSION值 
     * 
     * @param null|string $key 
     * @param null|mixed $default 
     * @access public
     * @return mixed
     */
    public function get_cookie($key = null, $default = null)
    {
        if (!isset($_SEESION)) {
            return null;
        }

        if (null === $key) {
            return $_SESSION;
        }    

        return (isset($_SESSION[$key])) ? $_SESSION[$key] : $default;
    }

    // }}}
    // {{{ public function set_session()

    /**
     * 设置SESSION
     * 
     * @param string $key 
     * @param mixed|null $value 
     * @access public
     * @return object sw_controller_request_http
     * $throw sw_controller_request_exception
     */
    public function set_session($key, $value = null) 
    {
        if (null == $key) {
            require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
            throw new sw_controller_request_exception('Invalid value passed to set_session(); must be either array of values or key pair', '000100020006');
        }
        $_SESSION[$key] = $value;
        return $this;
    }

    // }}} 
    // {{{ public function unset_session()

    /**
     * unset一个SEESION
     * 
     * @param string $key 
     * @access public
     * @return object sw_controller_request_http
     */
    public function unset_session($key)
    {
        if (null === $key) {
            require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
            throw new sw_controller_request_exception('Invalid value passed to unset_session(); must be emter the values or key pair', '000100020007'); 
        }

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }

        return $this;
    }

    // }}}
    // {{{ public function get_file()

    /**
     * 获取$_FILES值 
     * 
     * @param null|string $key 
     * @param null|mixed $default 
     * @access public
     * @return mixed
     */
    public function get_file($key = null, $default = null)
    {
        if (null === $key) {
            return $_FILES;
        }    

        return (isset($_FILES[$key])) ? $_FILES[$key] : $default;
    }

    // }}}
    // {{{ public function set_file()

    /**
     * 设置SESSION
     * 
     * @param string $key 
     * @param mixed|null $value 
     * @access public
     * @return object sw_controller_request_http
     * $throw sw_controller_request_exception
     */
    public function set_file($key, $value = null) 
    {
        if (null == $key) {
            require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
            throw new sw_controller_request_exception('Invalid value passed to set_file(); must be either array of values or key pair', '000100020008');
        }
        $_FILES[$key] = $value;
        return $this;
    }

    // }}} 
    // {{{ public function get_server()

    /**
     * 获取$_SERVER值 
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
     * 获取$_ENV值 
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
     * 设置REQUEST_URI 
     * 
     * @access public
     * @return void
     */
    public function set_request_uri()
    {
        if ($request_uri === null) {
            if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {  // check this first so IIS will catch
                $request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
            } else if (isset($_SERVER['REQUEST_URI'])) {
                $request_uri = $_SERVER['REQUEST_URI'];
                if (isset($_SERVER['HTTP_HOST']) && strstr($request_uri, $_SERVER['HTTP_HOST'])) {
                    $path_info    = parse_url($request_uri, PHP_URL_PATH);
                    $query_string = parse_url($request_uri, PHP_URL_QUERY);
                    $request_uri  = $path_info
                                . ((empty($query_string)) ? '' : '?' . $query_string);
                }
            } else if (isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0 , PHP as CGI
                $request_uri = $_SERVER['ORIG_PATH_INFO'];
                if (!empty($_SERVER['QUERY_STRING'])) {
                    $request_uri .= '?' . $_SERVER['QUERY_STRING'];
                }
            } else {
                return $this;
            }
        } else if (!is_string($request_uri)) {
            return $this;
        } else {
            // Set GET items, if available
            if (false != ($pos = strpos($request_uri, '?'))) {
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
	 * 获取REQUEST_URI如果没空，先设置成默认，然后再次获取 
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
	// {{{ public function set_base_url()

	/**
	 * 设置base_url 
	 * 
	 * @param string|null $base_url 
	 * @access public
	 * @return object sw_controller_request_http
	 */
	public function set_base_url($base_url = null)
	{
		if ((null !== $base_url) && !is_string($base_url)) {
			return $this;	
		}
		
		if (null === $base_url) {
			$filename = (isset($_SERVER['SCRIPT_FILENAME'])) ? basename($_SERVER['SCRIPT_FILENAME']) : '';
			if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $filename) {
				$base_url = $_SERVER['SCRIPT_NAME'];	
			} else if (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $filename) {
				$base_url = $_SERVER['PHP_SELF'];	
			} else if (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename) {
				$base_url = $_SERVER['ORIG_SCRIPT_NAME'];	
			} else {
				$path  = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
				$file  = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
				$segs  = explode('/', trim($file, '/'));
				$segs  = array_reverse($segs);
				$index = 0;
				$last  = count($segs);
				$base_url = '';
				do {
					$seg = $segs[$index];
					$base_url = '/' . $seg . $base_url;	
				} while(($last > $index) && (false !== ($pos = strpos($path, $base_url))) && (0 != $pos))
			}

			//处理request_uri  '?'
			$request_uri = $this->get_request_uri();

			if (0 === strpos($request_uri, $base_url)) {
				//所有路劲和request_uri匹配
				$this->__base_url = $base_url;
				return $this;	
			}

			if (0 === strpos($request_uri, dirname($base_url))) {
				//路劲的目录和request_uri匹配
				$base_dir = dirname($base_url);	
				$this->__base_url = $base_url === '/' ? $base_dir : rtrim(dirname($base_url), '/');
				return $this;
			}

			if (!strpos($request_uri, basename($base_url))) {
				//没有匹配到任何
				$this->__base_url = '';
				return $this;
			}

			//If using mod_rewrite or ISAPI_Rewrite strip the script filename
			//out of base_url. $pos !== 0 makes sure it is not matching a value
			//from PATH_INFO or QUERY_STRING
			if ((strlen($request_uri) >= strlen($base_url)) && ((false !== ($pos = strpos($request_uri, $base_url))) && ($pos !==0 ))) {
				$base_url = substr($request_uri, 0, $pos + strlen($base_url));
			}
		}
		$this->__base_url = $base_url === '/' ? $base_url : rtrim($base_url, '/');
		return $this;
	}

    // }}}
	// {{{ public function get_base_url()
	
	/**
	 * 获取base_url 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_base_url()
	{
		if (null === $this->__base_url) {
			$this->set_base_url();	
		}	

		return $this->__base_url;
	}

	// }}} 
	// {{{ public function set_base_path()

	/**
	 * 设置base_path 
	 * 
	 * @param string|null $base_path 
	 * @access public
	 * @return object sw_controller_request_http
	 */
	public function set_base_path($base_path = null)
	{
		if ($base_path === null) {
			$filename = basename($_SERVER['SCRIPT_FILENAME']);	

			$base_url = $this->get_base_url();
			if (empty($base_url)) {
				$this->__base_path = '';	
				return $this;
			}

			if (basename($base_url) === $filename) {
				$base_path = dirname($base_url);	
			} else {
				$base_path = $base_url;	
			}
		}	

		if (substr(PHP_OS, 0, 3) === 'WIN') {
			$base_path = str_replace('\\', '/', $base_path);	
		}

		$this->__base_path = rtrim($base_path, '/');
		return $this;
	}

	// }}}
	// {{{ public function get_base_path()

	/**
	 * 获取base_path 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_base_path()
	{
		if (null === $this->__base_path) {
			$this->set_base_path();	
		}	

		return $this->__base_path;
	}

	// }}}
	// {{{ public function set_path_info()

	/**
	 * 设置path_info 
	 * 
	 * @param string|null $path_info 
	 * @access public
	 * @return object sw_controller_request_http
	 */
	public function set_path_info($path_info = null) 
	{
		if ($path_info === null) {
			$base_url = $this->get_base_url();
			
			if (null === ($request_uri = $this->get_request_uri())) {
				return $this;	
			}

			//从request_uri 中移除查询字符串
			if ($pos = strpos($request_uri, '?')) {
				$request_uri = substr($request_uri, 0, $pos);	
			}

			if ((null !== $base_url) && (false === ($path_info = substr($request_uri, strlen($base_url))))) {
				$path_info = '';
			} else if (null === $base_url) {
				$path_info = $request_uri;
			}
		}

		$this->__path_info = (string) $path_info;
		return $this;
	}

	// }}}
	// {{{ public function get_path_info()

	/**
	 * 获取path_info 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_path_info()
	{
		if (empty($this->__path_info)) {
			$this->set_path_info();
		}

		return $this->__path_info;
	}

	// }}}
	// {{{ public function set_relative_path()
	
	/**
	 * 设置相对路劲 
	 * 
	 * @param string|null $base_url 
	 * @access public
	 * @return object sw_controller_request_http
	 */
	public function set_relative_path($base_url = null)
	{
		if (null === $base_url) {
			$base_url = $this->get_base_url();	
		}

		if (null === ($request_uri = $this->get_request_uri())) {
			return $this;	
		}

		if ($pos = strpos($request_uri, '?')) {
			$request_uri = substr($request_uri, 0, $pos);	
		}

		if (null !== $base_url && '' !== $base_url) {
			$pos = strpos($request_uri, $base_url);
			if (false !== $pos) {
				$relative_path = substr($request_uri, strlen($base_url));	
			} else {
				$relative_path = $request_uri;
			}
		} else {
			$relative_path = $request_uri;
		}

		$this->__relative_path = rtrim($relative_path, '/');
		return $this;
	}

	// }}}
	// {{{ public function get_relative_path()
	
	/**
	 * 获取相对路劲 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_relative_path()
	{
		if (empty($this->__relative_path)) {
			$this->set_relative_path();	
		}

		return $this->__relative_path();
	}

	// }}}
	// {{{ public function set_param_sources()

	/**
	 * 设置允许参数来源 
	 * 可以是空数组，或含有'_GET','_POST', 或'_REQUEST'的数组 
	 * 
	 * @param array $param_sources 
	 * @access public
	 * @return object sw_controller_request_http
	 */
	public function set_param_sources(array $param_sources = array()) 
	{
		$this->__param_sources = $param_sources;
		return $this;	
	}
	// }}}
	// {{{ public function get_param_sources()

	/**
	 * 获取允许设置参数的来源 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_param_sources()
	{
		return $this->__param_sources;	
	}

	// }}}
	// {{{ public function set_param()

	/**
	 * 设置用户自定义的参数 
	 * 
	 * @param string $key 
	 * @param mixed $value 
	 * @access public
	 * @return object sw_controller_request_http
	 */
	public function set_param($key, $value) 
	{
		$key = (null !== ($alias = $this->get_alias($key))) ? $alias : $key;
		parent::set_param($key, $value);
		return $this;
	}

	// }}} 
	// {{{ public function get_param()

	/**
	 * 获取参数 
	 * 
	 * 优先级： 用户自定义>_GET>_POST>_REQUEST
	 * @param string $key 
	 * @param mixed|null $default 
	 * @access public
	 * @return mixed
	 */
	public function get_param($key, $default = null)
	{
		$key_name = (null !== ($alias = $this->get_alias($key))) ? $alias : $key;
		
		$param_sources = $this->get_param_sources();
		if (isset($this->__params[$key_name])) {
			return $this->__params[$key_name];
		} else if (in_array('_GET', $param_sources) && (isset($_GET[$key_name]))) {
			return $_GET[$key_name];		
		} else if (in_array('_POST', $param_sources) && (isset($_POST[$key_name]))) {
			return $_POST[$key_name];	
		} else if (in_array('_REQUEST', $param_sources) && (isset($_REQUEST[$key_name]))) {
			return $_REQQUEST[$key_name];	
		}

		return $default;
	}

	// }}} 
	// {{{ public function get_params()

	/**
	 * 获取全部参数
	 * 
	 * @access public
	 * @return array
	 */
	public function get_params()
	{
		$return = $this->__params;
		if (isset($_GET) && is_array($_GET)) {
			$return += $_GET;	
		}

		if (isset($_POST) && is_array($_POST)) {
			$return += $_POST;	
		}

		if (isset($_REQUEST) && is_array($_REQUEST)) {
			$return += $_REQUEST;	
		}

		return $return;
	}

	// }}}
	// {{{ public function set_params()

	/**
	 * 批量设置参数 
	 * 
	 * @param array $params 
	 * @access public
	 * @return object sw_controller_request_http
	 */
	public function set_params(array $params)
	{
		foreach ($params as $key => $value) {
			$this->set_param($key, $value);	
		}

		return $this;
	}

	// }}} 
	// {{{ public function set_alias()
	
	/**
	 * 设置变量的别名，$target是真实运用的key值 
	 * 
	 * @param string $name 
	 * @param string $target 
	 * @access public
	 * @return void
	 */
	public function set_alias($name, $target)
	{
		$this->__aliases[$name] = $target;
		return $this;	
	}

	// }}}
	// {{{ public function get_alias()

	/**
	 * 获取别名 
	 * 
	 * @param string $name 
	 * @access public
	 * @return string|null
	 */
	public function get_alias($name)
	{
		if (isset($this->__aliases[$name]))	{
			return $this->__aliases[$name];		
		}

		return null;
	}

	// }}}
	// {{{ public function get_aliases()

	/**
	 * 获取别名列表 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_aliases()
	{
		return $this->__aliases;		
	}

	// }}}
	// {{{ public function get_method()

	/**
	 * 获取请求的方式 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_method()
	{
		return $this->get_server('REQUEST_METHOD');	
	}

	// }}}
	// {{{ public function is_post()

	/**
	 * 是否是POST请求 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_post()
	{
		if ('POST' == $this->get_method()) {
			return true;	
		}	
		return false;
	}

	// }}}
	// {{{ public function is_get()
	
	/**
	 * 是否是GET请求 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_get()
	{
		if ('GET' == $this->get_method()) {
			return true;	
		}
		return false;
	}

	// }}}
	// {{{ public function is_request()

	/**
	 * 是否是REQUEST请求 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_request()
	{
		if ('REQUEST' == $this->get_method()) {
			return true;	
		}	

		return false;
	}

	// }}}
	// {{{ public function is_put()

	/**
	 * 是否是PUT方式请求 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_put()
	{
		if ('PUT' == $this->get_method()) {
			return true;	
		}
		return false;
	}

	// }}}
	// {{{ public function is_delete()

	/**
	 * 是否是DELETE请求 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_delete()
	{
		if ('DELETE' == $this->get_method()) {
			return true;	
		}	
		return false;
	}

	// }}}
	// {{{ public function is_head()

	/**
	 * 是否是HEAD请求 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_head()
	{
		if ('HEAD' == $this->get_method()) {
			return true;	
		}	
		return false;
	}

	// }}}
	// {{{ public function is_options()

	/**
	 * 是否是OPTIONS请求 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_options()
	{
		if ('OPTIONS' == $this->get_method()) {
			return true;	
		}

		return false;
	}

	// }}}
	// {{{ public function is_xml_http_request()

	/**
	 * 是否是Javascript XMLHttpRequest?请求即ajax 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_xml_http_request()
	{
		return ($this->get_header('X_REQUESTED_WITH') == 'XMLHttpRequest');	
	}

	// }}}
	// {{{ public function is_flash_request()

	/**
	 * 是否是flash请求 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_flash_request()
	{
		$header = strtolower($this->get_header('USER_AGENT'));
		return (strstr($header, ' flash'))? true : false;	
	}

	// }}}
	// {{{ public function is_secure()

	/**
	 * 是否是以HTTPS协议访问 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_secure()
	{
		return ($this->get_scheme() === self::SCHEME_HTTPS);	
	}

	// }}}
	// {{{ public function get_raw_body()

	/**
	 * get_raw_body 
	 * 
	 * @access public
	 * @return string|false
	 */
	public function get_raw_body()
	{
		$body = file_get_contents('php://input');

		if (strlen(trim($body))> 0) {
			return $body;	
		}

		return false;
	}
	// }}}
	// {{{ public function get_header()

	/**
	 * 获取header信息
	 * 
	 * @param string $header 
	 * @access public
	 * @return string|boolean
	 */
	public function get_header($header)
	{
		if (empty($header)) {
			require_once PATH_SWAN_LIB . 'controller/request/sw_controller_request_exception.class.php';
			throw new sw_controller_request_exception('An HTTP header name is required', '000100020009');	
		}
		
		$temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
		if (!empty($_SERVER[$temp])) {
			return $_SERVER[$temp];	
		}

		if (function_exists('apache_request_headers')) {
			$headers = apache_request_headers();
			if (!empty($headers[$header])) {
				return $headers[$header];	
			}	
		}

		return false;
	}

	// }}}
	// {{{ public function get_scheme()

	/**
	 * 获取request uri 的协议描述 
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
	 * 获取HTTP HOST
	 * 注意：这个host header信息和URI host是不一样，这个返回的是包括端口号，80，443默认就不加 
	 * 
	 * @access public
	 * @return void
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

		if (($scheme == self::SCHEME_HTTP && $port == 80) || ($scheme == self::SCHEME_HTTPS && $port == 443)) {
			return $name;	
		} else {
			return $name . ':' . $port;	
		}
	}

	// }}}
	// }}}
}
