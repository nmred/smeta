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
* sw_snmp_abstract 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_snmp_abstract
{
	// {{{ consts

	/**
	 * trim获取出的结果  
	 */
	const REGEXP_RESULT_TRIM = '/(hex|counter(32|64)|gauge|gauge(32|64)|float|ipaddress|string|integer):/i';

	/**
	 * 通过php的扩展方式调用 
	 */
	const PHP_EXT = 'PHP_EXT';

	/**
	 * 通过php的系统调用方式调用 
	 */
	const PHP_EXEC = 'PHP_EXT';

	/**
	 * SNMP通信方式 
	 */
	const UCD_SNMP = 'UCD_SNMP';
	const NET_SNMP = 'NET_SNMP';

	// }}}	
	// {{{ members
	
	/**
	 * snmp通信方式 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__snmp_type;

	/**
	 * snmp版本 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__version;

	/**
	 * 获取方式 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__method_get;

	/**
	 * 获取的主机IP 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__host = 'localhost';

	/**
	 * snmp的端口 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__port = '161';

	/**
	 * 共同体名 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__community = 'public';

	/**
	 * 获取对象的id 
	 * 
	 * @var string|array
	 * @access protected
	 */
	protected $__object_id;

	/**
	 * 超时时间 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__timeout = 30;

	/**
	 * 重试次数 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__retries = 5;

	// }}}
	// {{{ functions
	// {{{ public function set_host()

	/**
	 * 设置主机名 
	 * 
	 * @access public
	 * @return sw_snmp_abstract 
	 */
	public function set_host($host)
	{
		//验证是否为正确的IP
		require_once PATH_SWAN_LIB . 'sw_validate.class.php';

		sw_validate::validate_ip($host);

		$this->__host = $host;
		return $this;
	}

	// }}}
	// {{{ public function set_port()

	/**
	 * 设置端口 
	 * 
	 * @param int $port 
	 * @access public
	 * @return sw_snmp_abstract
	 */
	public function set_port($port)
	{
		if (is_numeric($port)) {
			$this->__port = $port;
		}
		return $this;
	}

	// }}}
	// {{{ public function set_community()

	/**
	 * 设置共同体 
	 * 
	 * @param mixed $community 
	 * @access public
	 * @return sw_snmp_abstract
	 */
	public function set_community($community)
	{
		$this->__community = $community;
		return $this;	
	}

	// }}}
	// {{{ public function set_object_id()

	/**
	 * 设置对象的ids 
	 * 
	 * @param array | string $object_ids 
	 * @access public
	 * @return sw_snmp_abstract
	 */
	public function set_object_id($object_ids)
	{
		$this->__object_id = $object_ids;
		return $this;
	}

	// }}}
	// {{{ public function set_timeout()

	/**
	 * 设置超时时间 
	 * 
	 * @param int $timeout 
	 * @access public
	 * @return void
	 */
	public function set_timeout($timeout)
	{
		if (is_numeric($this->__timeout)) {
			$this->__timeout = $timeout;	
		}
		return $this;
	}

	// }}}
	// {{{ public function set_retries()

	/**
	 * 设置重试次数 
	 * 
	 * @param int $retries 
	 * @access public
	 * @return sw_snmp_abstract
	 */
	public function set_retries($retries)
	{
		if (is_numeric($this->__retries)) {
			$this->__retries = $retries;	
		}
		return $this;
			
	}

	// }}}
	// {{{ public function get_host()

	/**
	 * 获取主机名 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_host()
	{
		return $this->__host;	
	}

	// }}}
	// {{{ public function get_port()

	/**
	 * 获取端口 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_port()
	{
		return $this->__port;	
	}

	// }}}
	// {{{ public function get_community()
	
	/**
	 * 获取共同体 
	 * 
	 * @access public
	 * @return mixed
	 */
	public function get_community()
	{
		return $this->__community;	
	}

	// }}}
	// {{{ public function get_object_id()

	/**
	 * 获取所有监控的对象 
	 * 
	 * @access public
	 * @return array|int
	 */
	public function get_object_id()
	{
		return $this->__object_id;	
	}

	// }}}
	// {{{ public function get_timeout()

	/**
	 * 获取过期时间 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_timeout()
	{
		return $this->__timeout;	
	}

	// }}}
	// {{{ public function get_retries()
	
	/**
	 * 获取重试次数 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_retries()
	{
		return $this->__retries;	
	}

	// }}}
	// {{{ public function format_snmp()

	/**
	 * 格式化返回的值 
	 * 
	 * @param string $string 
	 * @access public
	 * @return string
	 */
	public function format_snmp($string)
	{
		$string = preg_replace(self::REGEXP_RESULT_TRIM, "", trim($string));
		if (substr($string, 0, 7) == 'No Such') {
			return '';	
		}
		
		$search_delimiters = array('"', '\'', '>', '<', '\\', '\n', '\r');
		$string = str_replace($search_delimiters, "", $string);

		return $string;
	}

	// }}}
	// }}}
}
