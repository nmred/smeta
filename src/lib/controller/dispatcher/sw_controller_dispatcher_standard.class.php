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
 
require_once PATH_SWAN_LIB . 'controller/dispatcher/sw_controller_dispatcher_abstract.class.php';
/**
+------------------------------------------------------------------------------
* 系统默认分发器
+------------------------------------------------------------------------------
* 
* @uses sw_controller_dispatcher_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_controller_dispatcher_standard extends sw_controller_dispatcher_abstract
{
	// {{{ consts

	/**
	 * action类名的前缀 
	 * 
	 * @var string
	 */
	const CLASS_PREFIX = 'sw_controller_';

	// }}}
	// {{{ members

	/**
	 * 当前目录 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__cur_directory;

	/**
	 * 当前模块 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__cur_module;

	/**
	 * 控制器的目录 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__controller_directory = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public function __construct(array $params = array())
	{
		parent::__construct($params);
		$this->__cur_module = $this->get_default_module();
	}

	// }}}
	// {{{ public function add_controller_directory()

	/**
	 * 添加控制器目录 
	 * 
	 * @param string $path 
	 * @param string $module 
	 * @access public
	 * @return sw_controller_dispatcher_standard
	 */
	public function add_controller_directory($path, $module = null)
	{
		if (null === $module) {
			$module = $this->__default_module;	
		}	

		$module = (string) $module;
		$path = rtrim((string) $path, '/\\');

		$this->__controller_directory[$module] = $path;
		return $this;
	}

	// }}}
	// {{{ public function set_controller_directory()

	/**
	 * 设置控制器的目录 
	 * 
	 * @param array|string $directory 
	 * @param string $module 
	 * @access public
	 * @return sw_controller_dispatcher_standard
	 */
	public function set_controller_directory($directory, $module = null)
	{
		$this->__controller_directory = array();
		
		if (is_string($directory)) {
			$this->add_controller_directory($directory, $module);	
		} elseif (is_array($directory)) {
			foreach ((array) $directory as $module => $path) {
				$this->add_controller_directory($path, $module);	
			}
		} else {
			require_once PATH_SWAN_LIB . 'controller/dispatcher/sw_controller_dispatcher_exception.class.php';
			throw new sw_controller_dispatcher_exception('Controller directory spec must be either a string or an array');
		}

		return $this;
	}

	// }}}
	// {{{ public function get_controller_directory()

	/**
	 * 获取控制器目录 
	 * 
	 * @param mixed $module 
	 * @access public
	 * @return void
	 */
	public function get_controller_directory($module = null)
	{
		if (null === $module) {
			return $this->__controller_directory;	
		}

		$module = (string) $module;
		if (array_key_exists($module, $this->__controller_directory)) {
			return $this->__controller_directory[$module];	
		}

		return null;
	}

	// }}}
	// {{{ public function remove_controller_directory()

	/**
	 * 移除控制器目录 
	 * 
	 * @param string $module 
	 * @access public
	 * @return sw_controller_dispatcher_standard
	 */
	public function remove_controller_directory($module)
	{
		$module = (string) $module;
		if (array_key_exists($module, $this->__controller_directory)) {
			unset($this->__controller_directory[$module]);
			return true;	
		}

		return false;
	}

	// }}}
	// {{{ public function format_module_name()

	/**
	 * 格式化模块名称 
	 * 
	 * @param string $unformatted 
	 * @access public
	 * @return string
	 */
	public function format_module_name($unformatted)
	{
		return strtolower($unformatted);	
	}

	// }}}
	// {{{ public function format_controller_name()

	/**
	 * 格式化控制器名称 
	 * 
	 * @param string $unformatted 
	 * @access public
	 * @return string
	 */
	public function format_controller_name($unformatted)
	{
		return strtolower($unformatted);	
	}

	// }}}
	// {{{ public function format_action_name()

	/**
	 * 格式化方法名称 
	 * 
	 * @param string $unformatted 
	 * @access public
	 * @return string
	 */
	public function format_action_name($unformatted)
	{
		return strtolower($unformatted);	
	}

	// }}}
	// {{{ public function format_class_name()

	/**
	 * 格式化控制器类名 
	 * 
	 * @param string $module_name 
	 * @param string $controller_name 
	 * @access public
	 * @return string
	 */
	public function format_class_name($module_name, $controller_name)
	{
		return self::CLASS_PREFIX . $this->format_module_name($module_name) 
			   . '_' . $this->format_controller_name($controller_name) . '_action';
	}

	// }}}
	// {{{ public function class_to_filename()
	
	/**
	 * 从类名获取控制器文件 
	 * 
	 * @param string $class 
	 * @access public
	 * @return stringclass_to_filename
	 */
	public function class_to_filename($class)
	{
		return $class . '.class.php';	
	}

	// }}} 
	// {{{ public function is_dispatchable()

	/**
	 * 判断是否可以分发 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return boolean
	 */
	public function is_dispatchable(sw_controller_request_abstract $request)
	{
		$class_name = $this->get_controller_class($request);
		if (!$class_name) {
			return false;	
		}
		
		if (class_exists($class_name, false)) {
			return true;	
		}

		$file_spec = $this->class_to_filename($class_name);
		$dispatch_dir = $this->get_dispatch_directory();
		$test = $dispatcher . DIRECTORY_SEPARATOR . $file_spec;
		
		return is_readable($test);
	}

	// }}}
	// {{{ public function dispatch()

	/**
	 * 分发器 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @param sw_controller_response_abstract $response 
	 * @access public
	 * @return void
	 */
	public function dispatch(sw_controller_request_abstract $request, sw_controller_response_abstract $response)
	{
		$this->set_response($response);
	
		if (!$this->is_dispatchable($request)) {
			$controller = $this->get_controller_name();
			require_once PATH_SWAN_LIB . 'controller/dispatcher/sw_controller_dispatcher_exception.class.php';
			throw new sw_controller_dispatcher_exception('Invalid controller specified (' . $controller_name . ')');	
		}

		$class_name = $this->get_controller_class($request);
		
		$controller = new $class_name($request, $this->get_response(), $this->get_params());
		if (!($controller instanceof sw_controller_action_interface) &&
			!($controller instanceof sw_controller_action)) {
			require_once PATH_SWAN_LIB . 'controller/dispatcher/sw_controller_dispatcher_exception.class.php';
			throw new sw_controller_dispatcher_exception('controller "' . $class_name . '" is not an instance of sw_controller_action_interface');	
		}	

		$action = $this->get_action_method($request);

		$request->set_dispatched(true);

		$disable_ob = $this->get_param('disable_output_buffering');
		$ob_level = ob_get_level();
		if (empty($disable_ob)) {
			ob_start();
		}

		try {
			$controller->dispatch($action);	
		} catch (Exception $e) {
			$cur_ob_level = ob_get_level();
			if ($cur_ob_level > $ob_level) {
				do {
					ob_get_clean();
					$cur_ob_level = ob_get_level();
				} while ($cur_ob_level > $ob_level);	
			}
			throw $e;	
		}

		if (empty($disable_ob)) {
			$content = ob_get_clean();
			$response->append_body($content);	
		}
		
		$controller = null;
	}

	// }}}
	// {{{ public function get_controller_class()
	
	/**
	 * 获取控制器的类名 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_controller_class(sw_controller_request_abstract $request)
	{
		$controller_name = $request->get_controller_name();
		if (empty($controller_name)) {
			if (!$this->get_param('use_default_controller_always'))	 {
				return false;	
			}

			$controller_name = $this->get_default_controller_name();
			$request->set_controller_name($controller_name);
		}

		$controller_dirs = $this->get_controller_directory();
		$module = $request->get_module_name();
		if ($this->is_valid_module($module)) {
			$this->__cur_module = $module;
			$this->__cur_directory = $controller_dirs[$module];	
		} elseif ($this->is_valid_module($this->__default_module)) {
			$request->set_module_name($this->__default_module);
			$this->__cur_module = $this->__default_module;
			$this->__cur_directory = $controller_dirs[$this->__default_module];
		} else {
			require_once PATH_SWAN_LIB . 'controller/dispatcher/sw_controller_dispatcher_exception.class.php';
			throw new sw_controller_dispatcher_exception('No default module defined for this application');	
		}

		$class_name = $this->format_class_name($module, $controller_name);
		return $class_name;
	}

	// }}}
	// {{{ public function is_valid_module()
	
	/**
	 * 判断模块名是否合法 
	 * 
	 * @param string $module 
	 * @access public
	 * @return boolean
	 */
	public function is_valid_module($module)
	{
		if (!is_string($module)) {
			return false;	
		}	

		$module          = strtolower($module);
		$controller_dirs = $this->get_controller_directory($module);

		foreach (array_keys($controller_dirs) as $module_name) {
			if ($module == strtolower($module_name)) {
				return true;	
			}	
		}

		return false;
	}

	// }}}
	// {{{ public funciton get_dispatch_directory()

	/**
	 * 获取分发目录 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_dispatch_directory()
	{
		return $this->__cur_directory;
	}

	// }}}
	// {{{ public function get_action_method()

	/**
	 * 设置action的方法 
	 * 
	 * @param sw_controller_request_abstract $request 
	 * @access public
	 * @return string
	 */
	public function get_action_method(sw_controller_request_abstract $request)
	{
		$action = $request->get_action_name();
		if (empty($action))	{
			$action = $this->get_default_action();
			$request->set_action_name($action);	
		}

		return $this->format_action_name($action);
	}

	// }}}
	// }}}
}
