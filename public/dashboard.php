<?php

require("includes.php");
include_once("../Application/Frontend/AdminTaskList.php");

if(isset($_GET['id']))
{
    $id = Main\ArrayMethods::array_get($_POST, 'id', -1);

    $task = new Application\Frontend\AdminTask($id);
    $task->Display();

}
else
{

    $task = new Application\Frontend\AdminTaskList();
    $task->Display();
}