<?php

class directory_reader extends DirectoryIterator
{
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param string $path 
	 * @access public
	 * @return void
	 */
	public function __construct($path)
	{
		parent::__construct($path);	
	}	

	// }}}	
	// {{{ public function current()

	/**
	 * current 
	 * 
	 * @access public
	 * @return string
	 */
	public function current()
	{
		return parent::getFileName();
	}

	// }}}
	// {{{ public function valid()

	/**
	 * valid 
	 * 
	 * @access public
	 * @return bool
	 */
	public function valid()
	{
		if (parent::valid()) {
			if (parent::isDir()) {
				parent::next();
				return $this->valid();	
			}
			return true;
		}
		return false;
	}

	// }}}
}

$it = new directory_reader('./');

foreach ($it as $key => $value) {
	echo $key, "|", $value, "\n";	
}
