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
* 目前没有其他实质作用，只是记录了所有的环境（安装其他开源软件如LAMP）路劲
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_env_shell_const
{
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function get_shell_path()

	/**
	 * 获取shell形式的变量 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_shell_path()
	{
		return array(
			// {{{ 源码的目录
			'source' => array(
				// httpd相关
				'SRC_HTTPD'    => '$TMP_DIR/`ls $TMP_DIR |grep httpd`',
				'SRC_PCRE'     => '$TMP_DIR/`ls $TMP_DIR |grep pcre`',
				'SRC_APR'      => '$TMP_DIR/`ls  $TMP_DIR|grep apr-[0-9].*`',
				'SRC_APR_UTIL' => '$TMP_DIR/`ls $TMP_DIR|grep apr-util`',
				
				// mysql相关
				'SRC_MYSQL'    => '$TMP_DIR/`ls $TMP_DIR |grep mysql`',
				'SRC_CMAKE'    => '$TMP_DIR/`ls $TMP_DIR |grep cmake`',
				
				// php核心相关
				'SRC_PHP'      => '$TMP_DIR/`ls $TMP_DIR |grep php`',
				'SRC_JPEG'     => '$TMP_DIR/`ls $TMP_DIR |grep jpeg`',
				'SRC_LIBPNG'   => '$TMP_DIR/`ls $TMP_DIR |grep libpng`',
				'SRC_FREETYPE' => '$TMP_DIR/`ls $TMP_DIR |grep freetype`',
				'SRC_ZLIB'     => '$TMP_DIR/`ls $TMP_DIR |grep zlib`',
				'SRC_GD'       => '$TMP_DIR/`ls $TMP_DIR |grep gd`',
				'SRC_LIBXML'   => '$TMP_DIR/`ls $TMP_DIR |grep libxml',

				// php扩展
				'SRC_PTHREADS' => '$TMP_DIR/`ls $TMP_DIR |grep pthreads`', //多线程支持扩展包
			),
			// }}}
			// {{{ 安装目录
			'install' => array(
				'INSTALL_SOFT'   => '/usr/local/swan',
				'INSTALL_RUN'    => '$INSTALL_SOFT/run',
				'INSTALL_SRC'    => '$INSTALL_SOFT/src',
				'INSTALL_PHP'    => '$INSTALL_SRC/php',
				'INSTALL_WEB'    => '$INSTALL_SRC/web',
				'INSTALL_DIR'    => '$INSTALL_SOFT/opt',

				//lamp相关
				'INSTALL_HTTPD'    => '$INSTALL_DIR/apache2',
				'INSTALL_APR'      => '$SRC_HTTPD/srclib/apr',
				'INSTALL_APR_UTIL' => '$SRC_HTTPD/srclib/apr-util',
				'INSTALL_PCRE'     => '$INSTALL_HTTPD/pcre',
				'INSTALL_MYSQL'    => '$INSTALL_DIR/mysql',
				'INSTALL_CMAKE'    => '$INSTALL_DIR/cmake',
				'INSTALL_PHP'      => '$INSTALL_DIR/php',
				'INSTALL_JPEG'     => '$INSTALL_PHP/jpeg',
				'INSTALL_LIBPNG'   => '/usr/local/libpng',
				'INSTALL_FREETYPE' => '$INSTALL_PHP/freetype',
				'INSTALL_ZLIB'     => '$INSTALL_PHP/zlib',
				'INSTALL_GD'       => '$INSTALL_PHP/gd',
				'INSTALL_LIBXML'   => '$INSTALL_PHP/libxml',

				//php扩展相关
				'INSTALL_EXT'      => '$INSTALL_PHP/ext',
				//etc配置相关
				'ETC_DIR'          => '$INSTALL_DIR/etc'
			),
			// }}}
			// {{{ 日志记录文件
			'log' => array(
				'INATLL_LOG' => '$INSTALL_DIR/install.log',
				'RUN_LOG'    => '$INSTALL_DIR/run.log',
			),
			// }}}
			// {{{ 其他杂项
			'other' => array(
				'CUR_PWD' => '`pwd`',
				'TMP_DIR' => '/tmp/swan/',
				'OPENSOURCE' => '/usr/local/swan_open/',
			),
			// }}}
		);	
	}

	// }}}
	// {{{ public function get_restart_list()

	/**
	 * 控制在启动停止管理中的需要启动的列表
	 * 
	 * @access public
	 * @return array
	 */
	public function get_restart_list()
	{
		return array(
			'http'  => true,
			'mysql' => true,
		);
	}

	// }}}
	// {{{ public function get_service_param()

	/**
	 * 获取所有服务的参数 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_service_param()
	{
		return array(
			'httpd' => array(
				'port' => 80,
			),
			'mysql' => array(
				'port'     => 3306,
				'timeout' => 30,
			),
		);	
	}

	// }}}
	// {{{ public function get_status_string()

	/**
	 * 获取成功 | 失败 的显示字符 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_status_string()
	{
		return array(
			'ECHO_OK'   => '[\033[0;32mOK\033[0m]',
			'ECHO_FAIL' => '[\033[0;31mFAIL\033[0m]',
			'ECHO_DOT'  => '...',
			'ECHO_START'=> '>',
			'ECHO_TAB'  => '=',
		);
	}

	// }}}	
	// }}}
}
