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
 
require_once PATH_SWAN_LIB . 'snmp/sw_snmp_abstract.class.php';

/**
+------------------------------------------------------------------------------
* sw_snmp_version_one 
+------------------------------------------------------------------------------
* 
* @uses sw
* @uses _snmp_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_snmp_version_one extends sw_snmp_abstract
{
	// {{{ functions
	// {{{ public function get()
	
	/**
	 * 获取snmp数据 
	 * 
	 * @access public
	 * @return mixed
	 */
	public function get()
	{
		if ((null === $this->__method_get) || (self::PHP_EXEC === $this->__method_get)) {
			return $this->get_exec('snmpget');	
		} else {
			return $this->get_ext('snmpget');			
		}
	}

	// }}}
	// {{{ public function get_next()
	
	/**
	 * 获取snmp数据 
	 * 
	 * @access public
	 * @return mixed
	 */
	public function get_next()
	{
		if ((null === $this->__method_get) || (self::PHP_EXEC === $this->__method_get)) {
			return $this->get_exec('snmpgetnext');	
		} else {
			return $this->get_ext('snmpgetnext');			
		}
	}

	// }}}
	// {{{ public function get_ext()

	/**
	 * 通过php扩展函数获取 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_ext($snmp_cmd)
	{
		//TODO snmp_get.....
		return 'U';	
	}

	// }}}
	// {{{ public function get_exec()

	/**
	 * 通过系统调用获取 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_exec($snmp_cmd)
	{
		$snmp_auth = (self::UCD_SNMP == $this->__snmp_type) ? $this->__community : ' -c ' . $this->__community;
		$cmd = PATH_SNMP_BIN . $snmp_cmd . ' -O ';
		if (self::UCD_SNMP == $this->__snmp_type) {
			$cmd .= ' vt -v1 -t ' . $this->__timeout . ' -r ' . $this->__retries;
			$cmd .= ' ' . $this->__host . ':' . $this->__port . $snmp_auth;
		} else {
			$cmd .= ' fntev ' . $snmp_auth . ' -v 1 -t ' . $this->__timeout . ' -r' . $this->__retries;
			$cmd .= ' ' . $this->__host . ':' . $this->__port;
		}
		
		$cmd .=  ' ' .escapeshellarg($this->__object_id);

		exec($cmd, $snmp_value);

		if (is_array($snmp_value)) {
			$snmp_value = implode(' ', $snmp_value);
		}

		if (substr_count($snmp_value, 'Timeout:')) {
			require_once PATH_SWAN_LIB . 'snmp/sw_snmp_exception.class.php';
			throw new sw_snmp_exception("WARNING: SNMP Get Timeout for host $this->__host");
		}

		$snmp_value = $this->format_snmp($snmp_value);
		return $snmp_value;
	}

	// }}}		
	// }}}
}
