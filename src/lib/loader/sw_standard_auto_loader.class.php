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
 
namespace lib\loader;

require_once __DIR__ . '/sw_spl_auto_loader.class.php';

/**
+------------------------------------------------------------------------------
* sw_standard_auto_loader 
+------------------------------------------------------------------------------
* 
* @uses sw_spl_auto_loader
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_standard_auto_loader implements sw_spl_auto_loader
{
	// {{{ consts

	const NS_SEPARATOR     = '\\';  // 命名空间的语法分隔符
	const LOAD_NS          = 'namespaces';
	const AUTOREGISTER_SW  = 'autoregister_sw';

	// }}}	
	// {{{ members

	/**
	 * __namespaces 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__namespaces = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param mixed $options 
	 * @access public
	 * @return void
	 */
	public function __construct($options = null)
	{
		if (null !== $options) {
			$this->set_options($options);	
		}
	}

	// }}}
	// {{{ public function set_options()
	
	/**
	 * set_options 
	 * 
	 * @param array|Traversable $options 
	 * @access public
	 * @return sw_standard_auto_loader
	 */
	public function set_options($options)
	{
		if (!is_array($options) && !($options instanceof \Traversable)) {
			require_once __DIR__ . '/exception/sw_invalid_argument_exception.class.php';
			throw new exception\sw_invalid_argument_exception('options must be either an array or Traversable. ');	
		}

		foreach ($options as $type => $pairs) {
			switch ($type) {
				case self::AUTOREGISTER_SW:
					if ($pairs) {
						$this->register_namespace('lib', dirname(dirname(__DIR__)));	
					}
					break;
				case self::LOAD_NS:
					if (is_array($pairs) || $pairs instanceof \Traversable) {
						$this->register_namespaces($pairs);	
					}
					break;
				default:
					// 忽略
			}
		}

		return $this;
	}

	// }}} 
	// {{{ public function register_namespace()

	/**
	 * register_namespace 
	 * 
	 * @param string $namespace 
	 * @param string $directory 
	 * @access public
	 * @return sw_standard_auto_loader
	 */
	public function register_namespace($namespace, $directory)
	{
		$namespace = rtrim($namespace, self::NS_SEPARATOR) . self::NS_SEPARATOR;
		$this->__namespaces[$namespace] = $this->_normalize_directory($directory);	
		return $this;
	}

	// }}}
	// {{{ public function register_namespaces()

	/**
	 * 批量注册命名空间 
	 * 
	 * @param array|Traversable $namespaces 
	 * @access public
	 * @return sw_standard_auto_loader
	 */
	public function register_namespaces($namespaces)
	{
		if (!is_array($namespaces) && !$namespaces instanceof \Traversable) {
			require_once __DIR__ . '/exception/sw_invalid_argument_exception.class.php';
			throw new exception\sw_invalid_argument_exception('prefix pairs must be either an array or Traversable. ');	
		}

		foreach ($namespaces as $namespace => $directory) {
			$this->register_namespace($namespace, $directory);	
		}

		return $this;
	}

	// }}}
	// {{{ public function register()

	/**
	 * register 
	 * 
	 * @access public
	 * @return void
	 */
	public function register()
	{
		spl_autoload_register(array($this, 'autoload'));	
	}

	// }}}
	// {{{ public function autoload()

	/**
	 * autoload 
	 * 
	 * @param string $class 
	 * @access public
	 * @return boolean|string
	 */
	public function autoload($class)
	{
		if (false !== strpos($class, self::NS_SEPARATOR)) {
			if ($this->_load_class($class, self::LOAD_NS)) {
				return $class;		
			}
		}
		return false;
	}

	// }}}
	// {{{ protected function _load_class()
	
	/**
	 * 加载类 
	 * 
	 * @param string $class 
	 * @param string $type 
	 * @access protected
	 * @return boolean
	 */
	protected function _load_class($class, $type)
	{
		if (!in_array($type, array(self::LOAD_NS))) {
			require_once __DIR__ . '/exception/sw_invalid_argument_exception.class.php';
			throw new exception\sw_invalid_argument_exception();	
		}
		
		$attribute = '__' . $type;
		foreach ($this->{$attribute} as $leader => $path) {
			if (0 === strpos($class, $leader)) {
				$filename = $this->_transform_classname_to_filename($class, $path);
				if (file_exists($filename)) {
					return require_once $filename;	
				}

				return false;
			}
		}

		return false;
	}

	// }}}
	// {{{ protected function _transform_classname_to_filename()

	/**
	 * 将类名转化为文件名 
	 * 
	 * @param string $class 
	 * @param string $directory 
	 * @access protected
	 * @return string
	 */
	protected function _transform_classname_to_filename($class, $directory)
	{
		$matches = array();
		preg_match('/(?P<namespace>.+\\\)?(?P<class>[^\\\]+$)/', $class, $matches);
		
		$class     = (isset($matches['class'])) ? $matches['class'] : '';
		$namespace = (isset($matches['namespace'])) ? $matches['namespace'] : '';
		
		return $directory
		     . str_replace(self::NS_SEPARATOR, '/', $namespace)
			 . $class
			 . '.class.php';	
	}

	// }}}
	// {{{ protected function _normalize_directory()

	/**
	 * 统一目录的编写规范 /usr/lib . \usr\lib 最后加上/ \
	 * 
	 * @param  string $directory
	 * @access protected
	 * @return string
	 */
	protected function _normalize_directory($directory)
	{
		$last = $directory[strlen($directory) - 1];
		if (in_array($last, array('/', '\\'))) {
			$directory[strlen($directory) - 1] = DIRECTORY_SEPARATOR;	
			return $directory;
		}

		$directory .= DIRECTORY_SEPARATOR;
		return $directory;
	}

	// }}}
	// }}}
}
