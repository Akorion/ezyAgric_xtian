<?php

/*class AsyncOperation extends Thread {

public function __construct($arg) {
    $this->arg = $arg;
}

public function run() {
    if ($this->arg) {
        $sleep = mt_rand(1, 10);
        printf('%s: %s  -start -sleeps %d' . "\n", date("g:i:sa"), $this->arg, $sleep);
        sleep($sleep);
        printf('%s: %s  -finish' . "\n", date("g:i:sa"), $this->arg);
    }
}
}

// Create a array
$stack = array();

//Iniciate Miltiple Thread
foreach ( range("A", "D") as $i ) {
$stack[] = new AsyncOperation($i);
}

// Start The Threads
foreach ( $stack as $t ) {
$t->start();
}
*/
/*
class WorkerThreads extends Thread
{
    private $workerId;
 
    public function __construct($id)
    {
        $this->workerId = $id;
    }
 
    public function run()
    {
        sleep(rand(0, 3));
        echo "Worker {$this->workerId} ran" . PHP_EOL;
    }
}
 
// Worker pool
$workers = [];
 
// Initialize and start the threads
foreach (range(0, 5) as $i) {
    $workers[$i] = new WorkerThreads($i);
    $workers[$i]->start();
}
 
// Let the threads come back
foreach (range(0, 5) as $i) {
    $workers[$i]->join();
}
*/
class PThread extends Thread {
    private  $id = ""; //ThreadID
    public function __construct($idThread) {
        $this->id = $idThread;
    }
    public function run() {
        if ($this->id) {
            $sleep = mt_rand(1, 10);
            printf('Thread: %s  has started, sleeping for %d' . "\n", $this->id, $sleep);
            sleep($sleep);
            printf('Thread: %s  has finished' . "\n", $this->id);
        }
    }
}
// Creating the pool of threads(stored as array)
$poolArr = array();
//Initiating the threads
foreach (range("0", "3") as $i) {
    $poolArr[] = new PThread($i);
}
//Start each Thread
foreach ($poolArr as $t) {
    $t->start();
}
//Wait all thread to finish
foreach (range(0, 3) as $i) {
    $poolArr[$i]->join();
}
//Next... other sentences with all threads finished.
 
?>
