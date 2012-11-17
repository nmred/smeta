<?php
class sw_validate
{
	// {{{ functions
	// {{{ static public function __call()
	
	/**
	 * __callStatic 
	 * 
	 * @param mixed $method 
	 * @param mixed $args 
	 * @static
	 * @access public
	 * @return void
	 */
	static public function __callStatic($method, $args)
	{
		if ('validate_' !== substr($method, 0, 9)) {
			require_once PATH_SWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception("Not exists $method () function");
		}

		if (!isset($args[0])) {
			require_once PATH_SWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception("Not exists will validate value. ");
		}

		$class_name = 'sw_validate_' . substr($method, 9);
		if (!class_exists($class_name)) {
			$class_file = PATH_SWAN_LIB . 'validate/' . $class_name . '.class.php';
			if (is_readable($class_file)) {
				require_once $class_file;	
			} else {
				require_once PATH_SWAN_LIB . 'sw_exception.class.php';
				throw new sw_exception("not exists $class_name. ");
			}
		}

		$options = func_get_args();
		$valid_value = array_shift($options[1]);
		$validate = new $class_name($options[1]);
		if ($validate->is_valid($valid_value)) {
			return true;	
		} else {
			require_once PATH_SWAN_LIB .  'sw_exception.class.php';	
			throw new sw_exception(implode(' ', $validate->get_messages()));
		}
	}
	
	// }}}
	// }}}	
}
