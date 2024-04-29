<?php
require("includes.php");


if($argc>1)
{
    $worker = Main\Registry::get("Worker");
    $worker->setQueueName($argv[1]);
    $worker->process();
}