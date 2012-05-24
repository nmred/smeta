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
* response 抽象类
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_controller_response_abstract
{
	// {{{ members

	/**
	 * body 的内容 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__body = array();

	/**
	 * 异常堆栈 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__exception = array();

	/**
	 * 存放header的数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__headers = array();

	/**
	 * 存放raw header 的数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__headers_raw = array();

	/**
	 * HTTP 响应码在header用的 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__http_response_code = 200;

	/**
	 * 该响应是否是一个跳转 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__is_redirect = false;

	/**
	 * 是否渲染异常信息，默认关闭 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__render_exceptions = false;

	protected $__out_buffer = '';

	/**
	 * Flag; 如果为真当header操作调用后有异常则抛出异常，否则将忽略继续执行，默认为true 
	 * 
	 * @var boolean
	 * @access public
	 */
	public $_headers_sent_throws_exception = true;

	// }}}	
	// {{{ functions
	// {{{ public function append_buffer()
		
	/**
	 * 添加到缓存区 
	 * 
	 * @param string $content 
	 * @access public
	 * @return void
	 */
	public function append_buffer($content)
	{
		$this->__out_buffer .= $content;	
	}

	// }}}
	// {{{ public function get_buffer()

	/**
	 * 获取缓存区内容 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_buffer()
	{
		return $this->__out_buffer;
	}

	// }}}
	// {{{ protected function _normalize_header()

	/**
	 * 格式化正确的header (X-Capitalized-Names) 
	 * 
	 * @param string $name 
	 * @access protected
	 * @return string
	 */
	protected function _normalize_header($name)
	{
		$filtered = str_replace(array('-', '_'), ' ', (string) $name);
		$filtered = ucwords(strtolower($filtered));
		$filtered = str_replace(' ', '-', $filtered);
		return $filtered;	
	}

	// }}}
	// {{{ public function set_header()

	/**
	 * 设置header
	 * $replace 为 true时将覆盖已有的$name的header 
	 * 
	 * @param string $name 
	 * @param string $value 
	 * @param boolean $replace 
	 * @access public
	 * @return object sw_controller_response_abstract
	 */
	public function set_header($name, $value, $replace = false)
	{
		$this->can_send_headers(true);
		$name = $this->_normalize_header($name);
		$value = (string) $value;
		
		if ($replace) {
			foreach ($this->__headers as $key => $header) {
				if ($name == $header['name']) {
					unset($this->__headers[$key]);	
				}
			}
		}

		$this->__headers[] = array(
			'name'    => $name,
			'value'   => $value,
			'replace' => $replace,
		);

		return $this;
	}

	// }}} 
	// {{{ public function set_redirect()

	/**
	 * 设置一个跳转地址 
	 * 
	 * @param string $url 
	 * @param int $code 
	 * @access public
	 * @return object sw_controller_response_abstract
	 */
	public function set_redirect($url, $code = 302)
	{
		$this->can_send_headers(true);
		$this->set_header('Location', $url, true)
			 ->set_http_response_code($code);

		return $this;
	}

	// }}}
	// {{{ public function is_redirect()
	
	/**
	 * 是否是跳转地址响应 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_redirect()
	{
		return $this->__is_redirect;	
	}
	 
	// }}}
	// {{{ public function get_headers()
	
	/**
	 * 返回header的数组 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_headers()
	{
		return $this->__headers;	
	}
	 
	// }}}
	// {{{ public function clear_headers()

	/**
	 * 清除header 
	 * 
	 * @access public
	 * @return object sw_controller_response_abstract
	 */
	public function clear_headers()
	{
		$this->__headers = array();
		
		return $this;	
	}

	// }}}
	// {{{ public function set_raw_header()

	/**
	 * 设置raw HTTP header 
	 * 
	 * @param string $value 
	 * @access public
	 * @return object sw_controller_response_abstract
	 */
	public function set_raw_header($value)
	{
		$this->can_send_headers(true);
		if ('Location' == substr($value, 0, 8)) {
			$this->__is_redirect = true;	
		}

		$this->__headers_raw[] = (string) $value;

		return $this;
	}

	// }}} 
	// {{{ public function get_raw_headers()

	/**
	 * 获取所有raw HTTP headers 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_raw_headers()
	{
		return $this->__headers_raw;	
	}

	// }}}
	// {{{ public function clear_raw_headers()

	/**
	 * 清除raw headers 
	 * 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function clear_raw_headers()
	{
		$this->__headers_raw = array();
		return $this;	
	}

	// }}}
	// {{{ public function clear_all_headers()
	
	/**
	 * 清除所有header信息 
	 * 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function clear_all_headers()
	{
		return $this->clear_headers()
					->clear_raw_headers();	
	}
	 
	// }}}
	// {{{ public function set_http_response_code()

	/**
	 * 设置http的响应码 
	 * 
	 * @param int $code 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function set_http_response_code($code)
	{
		if (!is_int($code) || (100 > $code) || (599 < $code)) {
			require_once PATH_SWAN_LIB . 'controller/response/sw_controller_response_exception.class.php';
			throw new sw_controller_response_exception('Invalid HTTP response code: #%s#', '000100030001', $code);	
		}

		if ((300 <= $code) && (307 >= $code)) {
			$this->__is_redirect = true;	
		} else {
			$this->__is_redirect = false;	
		}

		$this->__http_response_code = $code;
		return $this;
	}

	// }}}
	// {{{ public function get_http_response_code()

	/**
	 * 获取http的响应码 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_http_response_code()
	{
		return $this->__http_response_code;	
	}

	// }}}
	// {{{ public function can_send_headers()

	/**
	 * 判断是否可以设置和修改header不能则会抛异常或返回false 
	 * 
	 * @param boolean $throw 
	 * @access public
	 * @return boolean
	 */
	public function can_send_headers($throw = false)
	{
		$ok = headers_sent($file, $line);
		if ($ok && $throw && $this->_headers_sent_throws_exception) {
			require_once PATH_SWAN_LIB . 'controller/response/sw_controller_response_exception.class.php';
			throw new sw_controller_response_exception('Cannot send headers; headers already sent in  #%s#, line #%s#', '000100030002', array($file, $line));
		}	

		return !$ok;
	}

	// }}}
	// {{{ public function send_headers()
	
	/**
	 * 传递和设置header 
	 * 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function send_headers()
	{
		if (count($this->__headers_raw) || count($this->__headers) || (200 != $this->__http_response_code)) {
			$this->can_send_headers(true);	
		} else if(200 == $this->__http_response_code) {
			return $this;	
		}

		$httpCodeSent = false;

		foreach ($this->__headers_raw as $header) {
			if (!$httpCodeSent && $this->__http_response_code) {
				header($header, true, $this->__http_response_code);
				$httpCodeSent = true;	
			} else {
				header($header);	
			}
		}

		foreach ($this->__headers as $header) {
			if (!$httpCodeSent && $this->__http_response_code) {
				header($header['name'] . ':' . $header['value'], $header['replace'], $this->__http_response_code);
				$httpCodeSent = true;	
			} else {
				header($header['name'] . ':' . $header['value'], $header['replace']);	
			}
		}

		if (!$httpCodeSent) {
			header('HTTP/1.1' . $this->__http_response_code);
			$httpCodeSent = true;	
		}

		return $this;
	}
	 
	// }}}
	// {{{ public funciton set_body()

	/**
	 * 设置body体的内容 
	 * 
	 * @param string $content 
	 * @param string $name 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function set_body($content, $name = null)
	{
		if ((null === $name) || !is_string($name)) {
			$this->__body = array('default' => (string) $content);
		} else {
			$this->__body[$name] = (string) $content;	
		}

		return $this;
	}

	// }}} 
	// {{{ public function append_body()

	/**
	 * 往body体内添加信息 
	 * 
	 * @param string $content 
	 * @param string $name 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function append_body($content, $name = null)
	{
		if ((null === $name) || !is_string($name)) {
			if (isset($this->__body['default'])) {
				$this->__body['default'] .= (string) $content;	
			} else {
				return $this->append('default', $content);	
			}
		} else if (isset($this->__body[$name])) {
			$this->__body[$name] .= (string) $content;
		} else {
			return $this->append($name, $content);	
		}

		return $this;
	}

	// }}}
	// {{{ public function clear_body()

	/**
	 * 清除body体中的内容 
	 * 
	 * @param string|null $name 
	 * @access public
	 * @return boolean
	 */
	public function clear_body($name = null)
	{
		if (null !== $name) {
			$name = (string) $name;
			if (isset($this->__body[$name])) {
				unset($this->__body[$name]);
				return true;	
			}

			return false;
		}
		
		$this->__body = array();
		return true;
	}

	// }}}
	// {{{ public function get_body()

	/**
	 * 获取body体的内容
	 * 如果$spec为false 则返回body体中所有信息数组拼接的字符串，为true时将返回$__body的数组，如果$spec为字符串并且在body存在返回对应内容，否则返回null 
	 * 
	 * @param boolean|string $spec 
	 * @access public
	 * @return string|array|null
	 */
	public function get_body($spec = false)
	{
		if (false === $spec) {
			ob_start();
			$this->output_body();
			return ob_get_clean();	
		} else if (true === $spec) {
			return $this->__body;	
		} else if (is_string($spec) && isset($this->__body[$spec])) {
			return $this->__body[$spec];	
		}

		return null;
	}

	// }}}
	// {{{ public function append()

	/**
	 * 追加内容 
	 * 
	 * @param string $name 
	 * @param string $content 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function append($name, $content)
	{
		if (!is_string($name)) {
			require_once PATH_SWAN_LIB . 'controller/response/sw_controller_response_exception.class.php';
			throw new sw_controller_response_exception('Invalid body segment key (#%s#)', '000100030001', gettype($name));
		}

		if (isset($this->__body[$name])) {
			unset($this->__body[$name]);	
		}

		$this->__body[$name] = (string) $content;
		return $this;
	}

	// }}}
	// {{{ public function prepend()

	/**
	 * 在body的最前面追加信息 
	 * 
	 * @param string $name 
	 * @param string $content 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function prepend($name, $content)
	{
		if (!is_string($name)) {
			require_once PATH_SWAN_LIB . 'controller/response/sw_controller_response_exception.class.php';
			throw new sw_controller_response_exception('Invalid body segment key (#%s#)', '000100030002' , gettype($name));	
		}

		if (isset($this->__body[$name])) {
			unset($this->__body[$name]);	
		}
		
		$new = array($name => (string) $content);
		$this->__body = $new + $this->__body;

		return $this;
	}

	// }}} 
	// {{{ public function insert()

	/**
	 * 在body体中插入一个元素 
	 * 
	 * @param string $name 
	 * @param string $content 
	 * @param null|string $parent 
	 * @param boolean $before 
	 * @access public
	 * @return sw_controller_response_abstract
	 */
	public function insert($name, $content, $parent = null, $before = false)
	{
		if (!is_string($name)) {
			require_once PATH_SWAN_LIB . 'controller/response/sw_controller_response_exception.class.php';
			throw new sw_controller_response_exception('Invalid body segment key (#%s#)', '000100030003' , gettype($name));	
		}

		if ((null !== $parent) && !is_string($parent)) {
			require_once PATH_SWAN_LIB . 'controller/response/sw_controller_response_exception.class.php';
			throw new sw_controller_response_exception('Invalid body segment key (#%s#)', '000100030004' , gettype($name));	
		}

		if (isset($this->__body[$name])) {
			unset($this->__body[$name]);	
		}

		if ((null == $parent) || !isset($this->__body[$parent])) {
			return $this->append($name, $content);	
		}

		$ins  = array($name => (string) $content);
		$keys = array_keys($this->__body);
		$loc  = array_search($parent, $keys);

		if (!$before) {
			++$loc;	
		}

		if (0 == $loc) {
			$this->__body = $ins + $this->__body;	
		} else if ($loc >= (count($this->__body))) {
			$this->__body = $this->__body + $ins;	
		} else {
			$pre  = array_slice($this->__body, 0, $loc);
			$post = array_slice($this->__body, $loc);
			$this->__body = $pre + $ins + $post;	
		}

		return $this;
	}

	// }}}
	// {{{ public function output_body()
	
	/**
	 * 输出body体内的信息 
	 * 
	 * @access public
	 * @return void
	 */
	public function output_body()
	{
		foreach ($this->__body as $content) {
			echo $content;	
		}	
	}
					
	// }}} end functions
}

