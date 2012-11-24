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
 
require_once PATH_SWAN_LIB . 'sw_validate.class.php';
/**
+------------------------------------------------------------------------------
* sw_property_adapter_abstract 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_property_adapter_abstract
{
	// {{{ members
	
	/**
	 * 此对象允许设置的元素列表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__allow_attributes = array();

	/**
	 *	主键属性元素， prepared_attributes 不获取相应的属性  
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__key_attributes = array();

	/**
	 * 存储元素列表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__attributes = array();

	/**
	 * 存储其他的属性列表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__propertys = array();

	/**
	 * 存储验证结果数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__validate = array();

	/**
	 * 整形字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__int_fields = array();

	/**
	 * 布尔字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bool_fields = array();

	/**
	 * 整形枚举字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__int_enum_fields = array();

	/**
	 * 字符串字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__str_fields = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造函数 
	 * 
	 * @param array $attributes 
	 * @access public
	 * @return void
	 */
	public function __construct(array $attributes = array())
	{
		if (empty($attributes))	{
			return;	
		}

		foreach ($this->__allow_attributes as $attribute => $value) {
			if (array_key_exists($attribute, $attributes)) {
				$set = 'set_' . $attribute;
				if (method_exists($this, $set)) {
					$this->$set($attributes[$attribute]);
				} else {
					$this->__attributes[$attribute] = $attributes[$attribute];	
				}
			}		
		}
	}

	// }}}
	// {{{ public function attributes()

	/**
	 * 获取多个属性值 
	 * 
	 * @param array $keys 
	 * @access public
	 * @return array
	 */
	public function attributes(array $keys = array())
	{
		if (empty($keys)) {
			return $this->__attributes;	
		}

		$attributes = array();
		foreach ($keys as $key) {
			$attributes[$key] = $this->__attributes[$key];	
		}

		return $attributes;
	}

	// }}}
	// {{{ public function prepared_attributes()

	/**
	 * 获得添加修改前的属性值 
	 * 
	 * @param array $keys 
	 * @access public
	 * @return array
	 */
	public function prepared_attributes(array $keys = array())
	{
		$attributes = $this->attributes($keys);

		foreach ($this->__key_attributes as $key)
		{
			unset($attributes[$key]);	
		}

		return $attributes;
	}

	// }}}
	// {{{ public function __call()

	/**
	 * 魔术方法，set_xxx系列或get_xxx 
	 * 
	 * @param mixed $method 方法名
	 * @param array $args 参数
	 * @access public
	 * @return void
	 */
	public function __call($method, array $args)
	{
		list($type, $attribute) = explode('_', $method, 2) + array('', '');
		
		if (!isset($this->__allow_attributes[$attribute])
				&& isset($this->__allow_attributes[$attribute])) {
			require_once 'sw_property_adapter_exception.class.php';
			throw new sw_property_adapter_exception("Unrecognized method '$method()'");
		}

		if ('set' === $type) {
			if (1 !== count($args)) {
				require_once 'sw_property_adapter_exception.class.php';
				throw new sw_property_adapter_exception("'$method()' args error. ");
			}	

			if (isset($this->__allow_attributes[$attribute])) {
				$this->__attributesp[$attribute] = $args[0];	
			} elseif (isset($this->__allow_propertys[$attribute])) {
				$object = 'em_property_' . $attribute;
				if ($args[0] instanceof $object) {
					$this->__propertys[$attribute] = $args[0];	
				} else {
					require_once 'sw_property_adapter_exception.class.php';
					throw new sw_property_adapter_exception("The value of $attribute must be '$object' object!");
				}
			}
		} elseif ('get' === $type) {
			if (isset($this->__allow_attributes[$attribute])
					&& isset($this->__attributes[$attribute])) {
				return $this->__attributes[$attribute];	
			} elseif (isset($this->__allow_propertys[$attribute])
					&& isset($this->__propertys[$attribute])) {
				return $this->__propertys[$attribute];	
			}
		} else {
			require_once 'sw_property_adapter_exception.class.php';
			throw new sw_property_adapter_exception("Unrecognized method '$method()'");
		}
	}

	// }}}
	// {{{ public function get_validate()

	/**
	 * 获取验证错误字段列表 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_validate()
	{
		return array_unique($this->__validate);	
	}

	// }}}
	// {{{ public function check()
	
	/**
	 * 验证设置的数据是否符合基本规则 
	 * 
	 * @access public
	 * @return sw_property_adapter_exception
	 */
	public function check()
	{
		if (empty($this->__attributes)) {
			require_once 'sw_property_adapter_exception.class.php';
			throw new sw_property_adapter_exception("attributes are empty");
		}

		$attributes = $this->__attributes;

		foreach ($this->__int_fields as $field) {
			if (isset($attributes[$field])) {
				$value = $attributes[$field];
				try {
					sw_validate::validate_int($value);	
				} catch (sw_exception $e) {
					$this->__validate[] = $field;	
				}
			}	
		}

		foreach ($this->__bool_fields as $field) {
			if (isset($attributes[$field])) {
				$value = $attributes[$field];
				try {
					sw_validate::validate_in_array($value, array(0, 1));	
				} catch (sw_exception $e) {
					$this->__validate[] = $field;	
				}
			}	
		}
		
		foreach ($this->__int_enum_fields as $field => $haystack) {
			if (isset($attributes[$field])) {
				$value = $attributes[$field];
				try {
					sw_validate::validate_in_array($value, $haystack);	
				} catch (sw_exception $e) {
					$this->__validate[] = $field;	
				}
			}	
		}
		
		foreach ($this->__str_fields as $field => $haystack) {
			if (isset($attributes[$field])) {
				$value = $attributes[$field];
				if (!is_string($value)) {
					$this->__validate[] = $field;	
				}
			}	
		}

		if (!empty($this->__validate)) {
			require_once 'sw_property_adapter_exception.class.php';
			throw new sw_property_adapter_exception("some attribute is wrong");
		}
	}
	 
	// }}}	
	// }}}
}

