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
require_once PATH_SWAN_LIB . 'validate/sw_validate_abstract.class.php';
/**
+------------------------------------------------------------------------------
* 校验IP地址 
+------------------------------------------------------------------------------
* 
* @uses sw_validate_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_validate_ip extends sw_validate_abstract
{
	// {{{ members

	const INVALID        = 'ip_invalid';
	const NOT_IP_ADDRESS = 'not_ip_address';

	/**
	 * 定义模板 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__message_templates = array(
		self::INVALID        => "Invalid type given. String expected",
		self::NOT_IP_ADDRESS => "'%value%' does not appear to be a valid IP address",
	);

	/**
	 * 允许参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__options = array(
		'allowipv6' => true,
		'allowipv4' => true
	);

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param array $options 
	 * @access public
	 * @return void
	 */
	public function __construct($options = array())
	{
		if (!is_array($options)) {
			$options = func_get_args();
			$temp['allowipv6'] = array_shift($options);
			if (!empty($options)) {
				$temp['allowipv4'] = array_shift($options);	
			}

			$options = $temp;
		}

		$options += $this->__options;
		$this->set_options($options);
	}	

	// }}}
	// {{{ public function get_options()

	/**
	 * 获取参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_options()
	{
		return $this->__options;	
	}

	// }}}
	// {{{ public function set_options()

	/**
	 * 设置参数 
	 * 
	 * @param array $options 
	 * @access public
	 * @return sw_validate_ip
	 */
	public function set_options($options)
	{
		if (array_key_exists('allowipv6', $options)) {
			$this->__options['allowipv6'] = (boolean) $options['allowipv6'];	
		}

		if (array_key_exists('allowipv4', $options)) {
			$this->__options['allowipv4'] = (boolean) $options['allowipv4'];	
		}

		if (!$this->__options['allowipv4'] && !$this->__options['allowipv6']) {
			require_once PATH_SWAN_LIB . 'validate/sw_validate_exception.class.php';
			throw new sw_validate_exception('Nothing to validate. Check your options');
		}

		return $this;
	}

	// }}}
	// {{{ public function is_valid()

	/**
	 * 通过接口sw_validate_interface定义的接口
	 * 当是IP地址时返回true 
	 * 
	 * @param mixed $value 
	 * @access public
	 * @return boolean
	 */
	public function is_valid($value)
	{
		if (!is_string($value))	{
			$this->_error(self::INVALID);
			return false;	
		}

		$this->_set_value($value);
		if (($this->__options['allowipv4'] && !$this->__options['allowipv6'] && !$this->_validate_ipv4($value)) || (!$this->__options['allowipv4'] && $this->__options['allowipv6'] && !$this->_validate_ipv6($value)) || ($this->__options['allowipv4'] && $this->__options['allowipv6'] && !$this->_validate_ipv4($value) && !$this->_validate_ipv6($value))) {
			$this->_error(self::NOT_IP_ADDRESS);
			return false;	
		}

		return true;
	}

	// }}}
	// {{{ protected function _validate_ipv4()

	/**
	 * 验证是否是IPV4 
	 * 
	 * @param mixed $value 
	 * @access protected
	 * @return boolean
	 */
	protected function _validate_ipv4($value) 
	{
		$ip2long = ip2long($value);
		if ($ip2long === false) {
			return false;	
		}
		return $value == long2ip($ip2long);
	}

	// }}}
	// {{{ protected function _validate_ipv6()

	/**
	 * 校验IPV6 
	 * 
	 * @param mixed $value 
	 * @access protected
	 * @return boolean
	 */
	protected function _validate_ipv6($value)
	{
		if (strlen($value) < 3) {
			return $value == '::';	
		}

		if (strpos($value, '.')) {
			$lastcolon = strrpos($value, ':');
			if (!($lastcolon && $this->_validate_ipv4(substr($value, $lastcolon + 1)))) {
				return false;	
			}

			$value = substr($value, 0, $lastcolon) . ':0:0';
		}

		if (strpos($value, '::') == false) {
			return preg_match('/\A(?:[a-f0-9]{1,4}:){7}[a-f0-9]{1,4}\z/i', $value);	
		}

		$colon_count = substr_count($value, ':');
		if ($colon_count < 8) {
			return preg_match('/\A(?::|(?:[a-f0-9]{1,4}:)+):(?:(?:[a-f0-9]{1,4}:)*[a-f0-9]{1,4})?\z/i', $value);
		}

		if ($colon_count == 8) {
			return preg_match('/\A(?:::)?(?:[a-f0-9]{1,4}:){6}[a-f0-9]{1,4}(?:::)?\z/i', $value);	
		}

		return false;
	}

	// }}}
	// }}}
}
