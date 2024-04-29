<?php


namespace Main {

    use Main\Worker\Exception as Exception;
    use Main\Registry as Registry;

    /**
     * Class Worker
     * @package Main
     */
    class Worker
    {
        /**
         * hold the queue that the worker is commanded to work from
         * @var string
         */
        protected $queueName;

        /**
         * Worker constructor.
         * take a name of queue as argumnet and sets it to class prop
         * @param string $queue
         */
        public function __construct($queue = "")
        {
            $this->queueName = $queue;
        }


        /**
         * getter for the queueName
         * @return string
         */
        public function getQueueName(): string
        {
            return $this->queueName;
        }


        /**
         * setter for the queueName
         * @param string $queueName
         */
        public function setQueueName(string $queueName)
        {
            $this->queueName = $queueName;
        }


        /**
         * main processor for the worker. It gets the queue instance, sets the return item to one,
         * sets a loop to while item is always true, process the next unprocessed queue item.
         */
        public function process()
        {
            $Queue = Registry::get($this->queueName);
            $item = 1;

            while ($item) {
                $item = $Queue->process();
                var_dump("Please Wait 30 seconds...");
                sleep(30);
            }
        }
    }
}


