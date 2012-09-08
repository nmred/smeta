<?php
class file_put extends Thread
{
	public function run()
	{
		$time = time();
		while(100 > (time() - $time)) {
			printf("file copy|| thread_id: %lu\n",$this->getThreadId());	
		}
	}	
}

class write_db extends Thread
{
	public function __construct($thread_id)
	{
		//$this->__file_put_id = $thread_id;
		$this->__file_put_id = $thread_id;
	}

	public function run()
	{
		$time = time();
		while(100 > (time() - $time)) {
			printf("write db|| thread_id: %lu\n",$this->getThreadId());	
			if (time() % 20 === 0) {
				$this->file_tp->notify();	
			}
		}
	}	
}
$test1 = new file_put();
$test1->start();
$test2 = new write_db($test1->getThreadId());
$test2->start();
