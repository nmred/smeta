<?php

namespace lib\process;

class sw_test
{
	protected $__log;
	protected $__message;
	public function set_log($log)
	{
		$this->__log = $log;	
	}

	public function run()
	{
		while(1) {
			$this->log("test ....................", LOG_DEBUG);
			sleep(1);	
		}	
	}

	public function set_proc_config($config)
	{
		
	}

	public function log($message, $level) 
	{
		$this->__message->message = $message;
		$this->__log->log($this->__message, $level);	
	}

	public function set_message($message)
	{
		$this->__message = $message;	
	}
}
