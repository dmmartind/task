<?php


namespace Main
{
    use Main\Worker\Exception\Argument as Argument;
    use Main\Registry as Registry;

    class Worker
    {
        protected $queueName;

        public function __construct($queue = "")
        {
            $this->queueName = $queue;
        }

        /**
         * @return string
         */
        public function getQueueName(): string
        {
            return $this->queueName;
        }

        /**
         * @param string $queueName
         */
        public function setQueueName(string $queueName)
        {
            $this->queueName = $queueName;
        }



        public function process()
        {
            var_dump($this->queueName);
            $Queue = Registry::get($this->queueName);
            $item = 1;

            try {
                while($item)
                {
                    error_log("Loop");
                    $item = $Queue->process();
                    error_log(print_r($item, true));
                }

            }
            catch(Argument $e)
            {
                error_log("failed job!!!!");
            }
        }
    }



}


