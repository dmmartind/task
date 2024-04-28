<?php
require("includes.php");


if($argc>1)
{
    error_log("worker!!!!");
    $worker = Main\Registry::get("Worker");
    $worker->setQueueName($argv[1]);
    $worker->process();
    error_log(print_r($argv, true));
}