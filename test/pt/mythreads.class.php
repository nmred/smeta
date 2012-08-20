<?php
class mythreads extends Thread
{
	private $__thread_name;

	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造函数 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($thread_name)
	{
		$this->__thread_name = $thread_name;
	}

	// }}}
	// {{{ public function  run()
	/**
	 * run 
	 * 
	 * @access public
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 100; $i++) {
			if ($number > 0) {
				echo $this->__thread_name . ' number=' . $number--, "\n";	
			}
		}
	}

	// }}}
	// }}}
}

$tp = new mythreads("test");
$tp1 = new mythreads("test1");
echo "执行线程开始...\n";
$tp->start();
echo "执行线程结束...\n";
$tp1->start();



$stdout = fopen("php://stdout", "w");
echo "sssss";
fflush($stdout);
