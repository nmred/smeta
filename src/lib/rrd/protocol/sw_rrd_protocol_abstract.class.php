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
* RRDTOOL 协议抽象类 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_rrd_protocol_abstract
{
	// {{{ const

	/**
	 * rrdtool命令行换行符  
	 */
	const RRD_NL = "\\\n";

	/**
	 *	rrdtool命令bin目录  
	 */
	const RRD_BIN = '/usr/local/swan/opt/rrdtool/bin/rrdtool';

	// }}}
	// {{{ members

	/**
	 * 是否将返回信息输出终端 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__is_output = true;

	/**
	 * 执行命令管道 
	 * 
	 * @var pipe
	 * @access protected
	 */
	protected $__pipes = null;

	/**
	 * rrd 资源句柄 
	 * 
	 * @var resource
	 * @access protected
	 */
	protected $__rrd_fp = NULL;

	/**
	 * 将要写入管道中的命令 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__command = '';

	// }}}
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * 构造函数 
	 * 
	 * @param boolean $output_to_term 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		// 设置 rrdtool 的环境变量
		$this->_set_rrdtool_env();	

		if ($this->__is_output) {
			$command = self::RRD_BIN . ' - ';	
		} else {
			$command = self::RRD_BIN . ' - >/dev/null 2>&1';	
		}

		$pipe_desc = array(
			0 => array('pipe', 'r'), // writeable
			1 => array('pipe', 'w')	 // readable
		);

		// 初始化管道
		$this->__pipes = null;
		$this->__rrd_fp = proc_open($command, $pipe_desc, $this->__pipes);
	}

	// }}}
	// {{{ public function exec()
	
	/**
	 * 执行命令 
	 * 
	 * @access public
	 * @return array
	 */
	public function exec()
	{
		if (!is_resource($this->__rrd_fp)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('not create rrdtool fp, operate faild.');	
		}	

		if (!isset($this->__pipes[0]) || !isset($this->__pipes[1])) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('create rrdtool fp fail, operate faild.');	
		}	

		if ('' === trim($this->__command)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('exec command empty');	
		}	

		if (!fwrite($this->__pipes[0], $this->__command)) {
			require_once PATH_SWAN_LIB . 'rrd/protocol/sw_rrd_protocol_exception.class.php';
			throw new sw_rrd_protocol_exception('fwrite pipe faild, command:' . $this->__command);	
		}
		fclose($this->__pipes[0]);

		$return_message = stream_get_contents($this->__pipes[1]);
		$return_status = $this->close_rrd();

		return array('res' => $return_status, 'message' => $return_message);
	}

	// }}}
	// {{{ public function set_is_output()
	
	/**
	 * 设置是否在终端中显示返回信息 
	 * 
	 * @param boolean $is_output 
	 * @access public
	 * @return sw_rrd_protocol_abstract
	 */
	public function set_is_output($is_output = true)
	{
		$this->__is_output = (boolean)$is_output;
		return $this;	
	}

	// }}}
	// {{{ public function set_command()
	
	/**
	 * 设置执行的命令 
	 * 
	 * @param string $command 
	 * @access public
	 * @return sw_rrd_protocol_abstract
	 */
	public function set_command($command)
	{
		$command = str_replace(RRD_NL, ' ', $command);
		//$this->__command = escapeshellarg($command);
		$this->__command = $command;
		return $this;	
	}

	// }}}
	// {{{ public function close_rrd()
	
	/**
	 * 关闭 rrdtool 
	 * 
	 * @access public
	 * @return int | boolean
	 */
	public function close_rrd()
	{
		if (is_resource($this->__rrd_fp)) {
			return proc_close($this->__rrd_fp);
		}

		return false;
	}

	// }}}
	// {{{ protected function _set_rrdtool_env()
	
	/**
	 * 设置 rrdtool 的环境变量 
	 * 
	 * @access protected
	 * @return void
	 */
	public function _set_rrdtool_env()
	{
		return true;
	}

	// }}}
	// }}}	
}
