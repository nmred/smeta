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
* sw_validate_in_array 
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
class sw_validate_in_array extends sw_validate_abstract
{
	// {{{ const

	const NOT_IN_ARRAY = 'not_in_array';

	// }}}
	// {{{ members
	
	/**
	 * 定义模板 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__message_templates = array(
		self::NOT_IN_ARRAY => "'%value%' was not found in the haystack",
	);

	/**
	 * 查找的数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__haystack;

	/**
	 * 是否区别类型 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__strict = false;

	/**
	 * 是否递归查找 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__recursive = false;

	// }}}	
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造函数 
	 * 
	 * @param array $options 
	 * @access public
	 * @return void
	 */
	public function __construct($options)
	{
		if (is_array($options)) {
			$tmp['haystack'] = (array) array_shift($options);

			if (!empty($options)) {
				$tmp['strict'] = array_shift($options);	
			} else {
				$tmp['strict'] = false;	
			}

			if (!empty($options)) {
				$tmp['recursive'] = array_shift($options);	
			} else {
				$tmp['recursive'] = false;	
			}
		}		

		if (count($tmp['haystack']) < 1) {
			require_once PATH_SWAN_LIB . 'validate/sw_validate_exception.class.php';
			throw new sw_validate_exception("must given haystack");	
		}

		$this->set_haystack($tmp['haystack'])
			 ->set_strict($tmp['strict'])
			 ->set_recursive($tmp['recursive']);
	}

	// }}}
	// {{{ public function set_haystack()

	/**
	 * 设置查找的数组 
	 * 
	 * @param array $haystack 
	 * @access public
	 * @return sw_validate_in_array
	 */
	public function set_haystack(array $haystack)
	{
		$this->__haystack = $haystack;
		return $this;	
	}

	// }}}
	// {{{ public function get_haystack()

	/**
	 * 获取查找数组 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_haystack()
	{
		return $this->__haystack;	
	}

	// }}}
	// {{{ public function set_strict()

	/**
	 * 设置是否验证类型的一致性 
	 * 
	 * @param boolean $strict 
	 * @access public
	 * @return sw_validate_in_array
	 */
	public function set_strict($strict)
	{
		$this->__strict = (boolean) $strict;	
		return $this;
	}

	// }}}
	// {{{ public function get_strict()

	/**
	 * get strict 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function get_strict()
	{
		return $this->__strict;	
	}

	// }}}
	// {{{ public function set_recursive()

	/**
	 * 设置是否递归查找 
	 * 
	 * @param boolean $recursive 
	 * @access public
	 * @return sw_validate_in_array
	 */
	public function set_recursive($recursive)
	{
		$this->__recursive = (boolean) $recursive;
		return $this;
	}

	// }}}
	// {{{ public function get_recursive()

	/**
	 * 获取是否递归查找 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function get_recursive()
	{
		return $this->__recursive;	
	}

	// }}}
	// {{{ public function is_valid()

	/**
	 * 通过接口sw_validate_interface定义的接口
	 * 当在数组中的true 
	 * 
	 * @param mixed $value 
	 * @access public
	 * @return boolean
	 */
	public function is_valid($value)
	{
		$this->_set_value($value);
		if ($this->get_recursive()) {
			$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->__haystack));
			foreach ($iterator as $element) {
				if ($this->get_strict()) {
					if ($element === $value) {
						return true;	
					}	
				} elseif ($element == $value) {
					return true;	
				}
			}
		} else {
			if (in_array($value, $this->__haystack, $this->__strict)) {
				return true;	
			}	
		}

		$this->_error(self::NOT_IN_ARRAY);
		return false;
	}

	// }}}
	// }}}
}
