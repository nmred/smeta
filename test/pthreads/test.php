<?php
class MyWorker extends Thread {
	private $attempt;
	public function run(){
		if($this->attempt>1)
			sleep(rand(1, 3));
		printf(
				"%s: %lu running: %d/%d (%d) ...\n", 
				__CLASS__, $this->getThreadId(), $this->attempt++, $this->getStacked(), $this->getCount()
			  );
		return $this->attempt;
	}
}
$work = new MyWorker;
while($i++<10){
	printf(
			"Stacking: %d/%d\n", $i, $work->stack($work)
		  );
}
$work->start();
sleep(3);
while($i++<20){
	printf(
			"Stacking: %d/%d\n", $i, $work->stack($work)
		  );
}
printf("Result: %d/%d\n", $work->join(), $work->getStacked());
?>
