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
* sw_daemon 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_daemon
{
	// {{{ members

	/**
	 * PID文件的路劲 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__pid_file_path; 

	/**
	 * pid文件名称 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__pid_file_name;

	/**
	 * 是否在执行中输出信息 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__verbose = false;

	/**
	 * 单个进程
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__singleton = true;

	/**
	 * 关闭标准输入输出，错误输出STDIN STDOUT STDERR 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__close_std_handle = false;

	/**
	 * 进程ID 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__pid = 0;

	/**
	 * 执行文件 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__exec_file = "";

	/**
	 * 存放信号回调函数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__signal_handler_funs = array();

	// }}}	
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param array $configs 
	 * @access public
	 * @return void
	 */
	public function __construct($configs = array())
	{
		if (is_array($configs)) {
			$this->set_configs($configs);	
		}	
	}

	// }}}
	// {{{ public function _check_requirement()

	/**
	 * 检查系统需求 
	 * 
	 * @access public
	 * @return void
	 */
	public function _check_requirement()
	{
		if (!extension_loaded('pcntl')) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("daemon needs support of pcntl extension, please enable it.");	
		}

		if ('cli' != php_sapi_name()) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("daemon only works in cli sapi.");	
		}
	}

	// }}}
	// {{{ public function set_configs()
	
	/**
	 * 设置参数 
	 * 
	 * @param array $configs 
	 * @access public
	 * @return void
	 */
	public function set_configs($configs)
	{
		foreach ((array) $configs as $item => $config) {
			switch ($item) {
				case 'pid_file_path' :
					$this->set_pid_file_path($config);
					break;
				case 'pid_file_name' :
					$this->set_pid_file_name($config);
					break;
				case 'verbose' :
					$this->set_verbose($config);
					break;
				case 'singleton':
					$this->set_singleton($config);
					break;
				case 'close_std_handle':
					$this->set_close_std_handle($config);
					break;
				default:
					throw new sw_daemon("Unknown config item {$item}");
					break;
			}	
		}
	}

	// }}}
	// {{{ public function set_pid_file_path()

	/**
	 * 设置pid文件的路劲 
	 * 
	 * @param string $path 
	 * @access public
	 * @return boolean
	 */
	public function set_pid_file_path($path)
	{
		if (empty($path)) {
			return false;	
		}

		if (!is_dir($path)) {
			if (!mkdir($path, 0777)) {
				require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
				throw new sw_daemon_exception("set_pid_file_path() cannot make dir {$path}");	
			}
		}

		$this->__pid_file_path = rtrim($path, '/');
		return true;
	}

	// }}}
	// {{{ public function get_pid_file_path()

	/**
	 * 获取pid的文件路径 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_pid_file_path()
	{
		return $this->__pid_file_path;
	}

	// }}}
	// {{{ public function set_pid_file_name()

	/**
	 * 设置pid的文件名 
	 * 
	 * @param string $name 
	 * @access public
	 * @return boolean
	 */
	public function set_pid_file_name($name)
	{
		if (empty($name)) {
			return false;	
		}	

		$this->__pid_file_name = trim($name);
		return true;
	}

	// }}}
	// {{{ public function get_pid_file_name()

	/**
	 * 获取pid的文件名 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_pid_file_name()
	{
		return $this->__pid_file_name;	
	}

	// }}}
	// {{{ public function set_verbose()

	/**
	 * 设置是否打印信息 
	 * 
	 * @param boolean $open 
	 * @access public
	 * @return boolean
	 */
	public function set_verbose($open = true)
	{
		$this->__verbose = (boolean) $open;
		return true;	
	}

	// }}}
	// {{{ public function get_verbose()

	/**
	 * 获取verbose 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function get_verbose()
	{
		return $this->__verbose;	
	}

	// }}}
	// {{{ public function set_singleton()

	/**
	 * 设置成单件模式 
	 * 
	 * @param boolean $singleton 
	 * @access public
	 * @return boolean
	 */
	public function set_singleton($singleton = true)
	{
		$this->__singleton = (boolean) $singleton;
		return true;	
	}

	// }}}
	// {{{ public function get_singleton()

	/**
	 * 获取是否单件模式 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function get_singleton()
	{
		return $this->__singleton;	
	}

	// }}}
	// {{{ public function set_close_std_handle()

	/**
	 * 设置是否关闭标准输入和输出 
	 * 
	 * @param boolean $close 
	 * @access public
	 * @return boolean
	 */
	public function set_close_std_handle($close = true)
	{
		$this->__close_std_handle = (boolean) $close;
		return true;	
	}

	// }}}
	// {{{ public function get_close_std_handle()

	/**
	 * 获取是否关闭标准输入输出 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function get_close_std_handle()
	{
		return $this->__close_std_handle;	
	}

	// }}}
	// {{{ public function start()

	/**
	 * 启动守护进程 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function start()
	{
		$this->_check_requirement();
		
		$this->_daemonize();
		
		if (!pcntl_signal(SIGTERM, array($this, "signal_handler"))) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot setup signal handler for signo SIGTERM");
		}

		if ($this->__close_std_handle) {
			fclose(STDIN);	
			fclose(STDOUT);	
			fclose(STDERR);	
		}

		return true;
	}

	// }}}
	// {{{ public function stop()

	/**
	 * 停止 
	 * 
	 * @param boolean $force 
	 * @access public
	 * @return boolean
	 */
	public function stop($force = false)
	{
		$signo = ($force) ? SIGKILL : SIGTERM;
		
		if (!$this->__singleton) {	
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("'stop' only use in singleton model.");
		}

		if (false === ($pid = $this->_get_pid_from_file())) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("daemon is not running,cannot stop.");
		}

		if (!posix_kill($pid, $signo)) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot send signal $signo to daemon.");
		}

		$this->_unlink_pid_file();

		$this->_out("Daemon stopped with pid {$pid}...");
		return true;
	}

	// }}}
	// {{{ public function restart()
	
	/**
	 * 重启 
	 * 
	 * @access public
	 * @return void
	 */
	public function restart()
	{
		$this->stop();
		
		sleep(1);
		
		$this->start();	
	}

	// }}} 
	// {{{ public function get_daemon_pid()

	/**
	 * 获取守护进程的PID 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_daemon_pid()
	{
		return $this->_get_pid_from_file();	
	}

	// }}}
	// {{{ public function signal_handler()

	/**
	 * 设置信号回调句柄 
	 * 
	 * @param int $signo 
	 * @access public
	 * @return boolean
	 */
	public function signal_handler($signo)
	{
		$sign_funs = $this->__signal_handler_funs[$signo];
		if (is_array($sign_funs)) {
			foreach ($sign_funs as $fun) {
				call_user_func($fun);	
			}
		}

		switch ($signo) {
			case SIGTERM :
				exit;
				break;
			default:
				//所有的信号	
		}
	}

	// }}}
	// {{{ public function add_signal_handler()

	/**
	 * 添加信号回调句柄 
	 * 
	 * @param int $signo 
	 * @param string|array $fun 
	 * @access public
	 * @return sw_daemon
	 */
	public function add_signal_handler($signo, $fun)
	{
		if (is_string($fun)) { // 当回调的是函数
			if (!function_exists($fun)) {
				require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
				throw new sw_daemon_exception("handler function {$fun} not exists");
			}
		} elseif (is_array($fun)) { //当回调的是对象中的方法
			if (!method_exists($fun[0], $fun[1])) {
				require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
				throw new sw_daemon_exception("handler method not exists");
			}
		} else {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("error handler.");
		}

		if (!pcntl_signal($signo, array($this, 'signal_handler'))) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot setup signal handler for signo {$signo}");
		}

		$this->__signal_handler_funs[$signo][] = $fun;
		return $this;
	}

	// }}}
	// {{{ public function send_signal()

	/**
	 * 发送信号 
	 * 
	 * @param int $signo 
	 * @access public
	 * @return boolean
	 */
	public function send_signal($signo)
	{
		if (false === ($pid = $this->_get_pid_from_file())) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("daemon is not running,cannot send signal.");
		}

		if (!posix_kill($pid, $signo)) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot send signal $signo to daemon.");
		}

		return true;
	}

	// }}}
	// {{{ public function is_active()

	/**
	 * 判断是否该守护进程运行 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_active()
	{
		try {
			$pid = $this->_get_pid_from_file();	
		} catch (sw_daemon_exception $e) {
			return false;	
		}

		if (false === $pid) {
			return false;	
		}

		if (false === ($active = pcntl_getpriority($pid))) {
			return false;	
		}

		return true;
	}

	// }}}
	// {{{ protected function _daemonize()

	/**
	 * 创建守护进程 
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function _daemonize()
	{
		if ($this->__singleton) {
			$is_runing = $this->_check_running();
			if ($is_runing) {
				require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
				throw new sw_daemon_exception("Daemon already running");
			}
		}

		$pid = pcntl_fork();

		if (-1 == $pid) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Error happened while fork process");
		} elseif ($pid) {
			//结束父进程
			exit();	
		} else {
			$this->__pid = posix_getpid();	
		}

		$this->_out("Daemon started with pid {$this->__pid}...");

		//创建新的会话
		if (!posix_setsid()) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot detach from terminal");
		}

		if ($this->__singleton) {
			$this->_log_pid();	
		}

		return $this->__pid;
	}

	// }}}
	// {{{ protected function _get_pid_from_file()

	/**
	 * 获取pid 
	 * 
	 * @access protected
	 * @return int
	 */
	protected function _get_pid_from_file()
	{
		if ($this->__pid) {
			return (int) $this->__pid;	
		}		

		$pid_file = $this->__pid_file_path . '/' . $this->__pid_file_name;

		if (!file_exists($pid_file)) {
			return false;	
		}

		if (!$handle = fopen($pid_file, 'r')) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot open pid file {$pid_file} for read");
		}

		if (false === ($pid = fread($handle, 1024))) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot open pid file {$pid_file} for read");
		}

		fclose($handle);

		return $this->__pid = (int) $pid;
	}

	// }}}
	// {{{ protected function _check_running()

	/**
	 * 检查是否运行 
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function _check_running()
	{
		$pid = $this->_get_pid_from_file();

		if (false === $pid) {
			return false;	
		}

		switch (strtolower(PHP_OS)) {
			case "freebsd" :
				$str_exe = $this->_get_freebsd_pro_exe($pid);
				if (false === $str_exe)	{
					return false;
				}
				$str_args = $this->_get_freebsd_proc_args($pid);
				break;
			case "linux" :
				$str_exe = $this->_get_linux_pro_exe($pid);
				if (false === $str_exe)	{
					return false;
				}
				$str_args = $this->_get_linux_proc_args($pid);
				break;
			default:
				return false;
		}

		$exe_real_path = $this->_get_daemon_real_path($str_args, $pid);

		if ($str_exe != PHP_BINDIR . '/php') {
			return false;	
		}

		$self_file = '';
		$sapi = php_sapi_name();
		switch ($sapi) {
			case "cgi" :
				case "cgi-fcgi":
				$self_file = $_SERVER['argv'][0];
				break;
			default:
				$self_file = $_SERVER['PHP_SELF'];
				break;	
		}

		$current_real_path = realpath($self_file);

		if ($current_real_path != $exe_real_path) {
			return false;	
		}

		return true;
	}

	// }}}
	// {{{ protected function _log_pid()

	/**
	 * 记录PID 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _log_pid()
	{
		$pid_file = $this->__pid_file_path . '/' . $this->__pid_file_name;
		if (!$handle = fopen($pid_file, 'w')) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot open pid file {$pid_file} for write");
		}

		if (false == fwrite($handle, $this->__pid)) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot write to pid file {$pid_file}");
		}

		fclose($handle);
	}
	// }}}
	// {{{ protected function _unlink_pid_file()

	/**
	 * 删除pid文件 
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function _unlink_pid_file()
	{
		$pid_file = $this->__pid_file_path . '/' . $this->__pid_file_name;
		return unlink($pid_file);
	}

	// }}}
	// {{{ protected function _get_daemon_real_path()

	/**
	 * 获取真实的daemon文件路劲 
	 * 
	 * @param string $daemon_file 
	 * @param int $daemon_pid 
	 * @access protected
	 * @return string
	 */
	protected function _get_daemon_real_path($daemon_file, $daemon_pid)
	{
		$daemon_file = trim($daemon_file);
		if (substr($daemon_file, 0, 1) !== '/') {
			$cwd = $this->_get_linux_proc_cwd($daemon_pid);
			$cwd = rtrim($cwd, '/');
			$cwd = $cwd . '/' . $daemon_file;
			$cwd = realpath($cwd);
			return $cwd;
		}

		return realpath($daemon_file);
	}

	// }}}
	// {{{ protected function _get_freebsd_pro_exe()

	/**
	 * 获取freebsd中的proc路径 
	 * 
	 * @param int $pid 
	 * @access protected
	 * @return string
	 */
	protected function _get_freebsd_pro_exe($pid)
	{
		$str_proc_exe_file = '/proc/' . $pid . '/file';
		if (false === ($str_link = readlink($str_proc_exe_file))) {
			return false;	
		}	

		return $str_link;
	}

	// }}}
	// {{{ protected function _get_linux_pro_exe()

	/**
	 * 获取linux中的proc路径 
	 * 
	 * @param int $pid 
	 * @access protected
	 * @return string
	 */
	protected function _get_linux_pro_exe($pid)
	{
		$str_proc_exe_file = '/proc/' . $pid . '/exe';
		if (false === ($str_link = readlink($str_proc_exe_file))) {
			return false;	
		}	

		return $str_link;
	}

	// }}}
	// {{{ protected function _get_freebsd_proc_args()

	/**
	 * 获取参数 
	 * 
	 * @param int $pid 
	 * @access protected
	 * @return string
	 */
	protected function _get_freebsd_proc_args($pid)
	{
		return $this->_get_linux_proc_args($pid);	
	}

	// }}}
	// {{{ protected function _get_linux_proc_args()

	/**
	 * _get_linux_proc_args 
	 * 
	 * @param mixed $pid 
	 * @access protected
	 * @return void
	 */
	protected function _get_linux_proc_args($pid)
	{
		$str_proc_cmdline_file = "/proc/" . $pid . "/cmdline";
		
		if (!$fp = fopen($str_proc_cmdline_file, 'r')) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot open file {$str_proc_cmdline_file} for read");
		}

		if (!$str_contents = fread($fp, 4096)) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot read or empty file {$str_proc_cmdline_file}");
		}

		fclose($fp);
		$str_contents = preg_replace('/[^\w\.\/\-]/', ' ', $str_contents);
		$str_contents = preg_replace('/\s+/', ' ', $str_contents);

		$arr_tmp = explode(" ", $str_contents);
		if (count($arr_tmp) < 2) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Invalid content in {$str_proc_cmdline_file}");
		}

		return trim($arr_tmp[1]);
	}

	// }}}
	// {{{ protected function _get_linux_proc_cwd()

	/**
	 * 获取proc当前工作目录 
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _get_linux_proc_cwd($pid)
	{
		$str_proc_exe_file = '/proc/' . $pid . '/cwd';
		if (false === ($str_link = readlink($str_proc_exe_file))) {
			require_once PATH_SWAN_LIB . 'daemon/sw_daemon_exception.class.php';
			throw new sw_daemon_exception("Cannot read link file {$str_proc_exe_file}");
		}

		return $str_link;
	}

	// }}}
	// {{{ protected function _out()
	
	/**
	 * _out 
	 * 
	 * @param mixed $str 
	 * @access protected
	 * @return void
	 */
	protected function _out($str)
	{
		if ($this->__verbose) {
			fwrite(STDOUT, $str, "\n");	
		}	

		return true;
	}

	// }}}
	// }}}
}
