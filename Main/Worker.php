<?php


namespace Main
{
    use Main\Worker\Exception\Argument as Argument;

    class Worker
    {
        protected $queueName;

        public function __construct($queue)
        {
            $this->queueName = $queue;
        }

        public function process()
        {
            $Queue = Registry::get($this->queueName);


            try {
                $item = $Queue->process();
            }
            catch(Argument $e)
            {
                error_log("failed job!!!!");
            }
        }
    }
    if($argc>1)
        parse_str(implode('&',array_slice($argv, 1)), $_GET);

}


