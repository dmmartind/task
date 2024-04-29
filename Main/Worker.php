<?php


namespace Main {

    use Main\Worker\Exception as Exception;
    use Main\Registry as Registry;

    class Worker
    {
        protected $queueName;

        public function __construct($queue = "")
        {
            $this->queueName = $queue;
        }


        public function getQueueName(): string
        {
            return $this->queueName;
        }


        public function setQueueName(string $queueName)
        {
            $this->queueName = $queueName;
        }


        public function process()
        {
            $Queue = Registry::get($this->queueName);
            $item = 1;

            try {
                while ($item) {
                    $item = $Queue->process();
                }
            } catch (Exception $e) {
                error_log("failed job!!!!");
            }
        }
    }
}


