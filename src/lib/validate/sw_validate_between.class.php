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
* sw_validate_between 
+------------------------------------------------------------------------------
* 
* @uses sw
* @uses _validate_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_validate_between extends sw_validate_abstract
{
	// {{{ const

	const NOT_BETWEEN = 'not_between';
	const NOT_BETWEEN_STRICT = 'not_between_strict';

	// }}}
	// {{{ members
	
	/**
	 * 定义模板 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__message_templates = array(
		self::NOT_BETWEEN        => "'%value%' is not between '%min%' and '%max%', inclusively",
		self::NOT_BETWEEN_STRICT => "'%value%' is not strictly between '%min%' and '%max%'",
	);

	/**
	 * 错误提示信息中的变量 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__message_var = array(
		'max' => '__max',
		'min' => '__min',
	);

	/**
	 * 最大值 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__max;

	/**
	 * 最小值 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__min;

	/**
	 * 是否将边界计算在内 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__inclusive;

	// }}}	
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct(array $options)
	{
		if (is_array($options)) {
			$tmp['min'] = array_shift($options);	
			if (!empty($options)) {
				$tmp['max'] = array_shift($options);	
			}

			if (!empty($options)) {
				$tmp['inclusive'] = array_shift($options);	
			}
		}

		if (!array_key_exists('min', $tmp) || !array_key_exists('max', $tmp)) {
			require_once PATH_SWAN_LIB . 'validate/sw_validate_exception.class.php';
			throw new sw_validate_exception("Missing option. 'min' and 'max' has to be given");	
		}

		if (!array_key_exists('inclusive', $tmp)) {
			$tmp['inclusive'] = true;	
		}

		$this->set_min($tmp['min'])
			 ->set_max($tmp['max'])
			 ->set_inclusive($tmp['inclusive']);
	}

	// }}}
	// {{{ public function set_min()

	/**
	 * 设置最小值 
	 * 
	 * @param mixed $min 
	 * @access public
	 * @return sw_validate_between
	 */
	public function set_min($min)
	{
		$this->__min = $min;
		return $this;	
	}

	// }}}
	// {{{ public function get_min()

	/**
	 * 获取最小值
	 * 
	 * @access public
	 * @return mixed
	 */
	public function get_min()
	{
		return $this->__min;	
	}

	// }}}
	// {{{ public function set_max()

	/**
	 * 设置最大值 
	 * 
	 * @param mixed $max
	 * @access public
	 * @return sw_validate_between
	 */
	public function set_max($max)
	{
		$this->__max = $max;
		return $this;	
	}

	// }}}
	// {{{ public function get_max()

	/**
	 * 获取最大值
	 * 
	 * @access public
	 * @return mixed
	 */
	public function get_max()
	{
		return $this->__max;	
	}

	// }}}
	// {{{ public function set_inclusive()

	/**
	 * 设置是否包含边界 
	 * 
	 * @param bool $inclusive 
	 * @access public
	 * @return sw_validate_between
	 */
	public function set_inclusive($inclusive)
	{
		$this->__inclusive = (bool) $inclusive;
		return $this;
	}

	// }}}
	// {{{ public function get_inclusive()

	/**
	 * 获取是否包含边界的标记 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function get_inclusive()
	{
		return $this->__inclusive;	
	}

	// }}}
	// {{{ public function is_valid()

	/**
	 * 通过接口sw_validate_interface定义的接口
	 * 当是INT类型的true 
	 * 
	 * @param mixed $value 
	 * @access public
	 * @return boolean
	 */
	public function is_valid($value)
	{
		$this->_set_value($value);
		if ($this->__inclusive)	{
			if ($this->__min > $value || $value > $this->__max) {
				$this->_error(self::NOT_BETWEEN);
				return false;	
			}
		} else {
			if ($this->__min >= $value || $value >= $this->__max) {
				$this->_error(self::NOT_BETWEEN_STRICT);
				return false;	
			}
		}

		return true;
	}

	// }}}
	// }}}
}
