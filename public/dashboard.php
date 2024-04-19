<?php

require("includes.php");
include_once("../Application/Frontend/AdminTaskList.php");
include_once("../Application/Frontend/Profile.php");



$actions = [
    'update_user' => [
        'object' => 'Main\User',
        'method' => 'updateUser'
    ],
    'update_password' => [
        'object' => 'Main\User',
        'method' => 'updatePassword'
    ],
];

if ( Main\ArrayMethods::array_get($actions,Main\ArrayMethods::array_get($_POST, 'cmd',""), false) )
{
    $use_array = Main\ArrayMethods::array_get($actions,Main\ArrayMethods::array_get($_POST, 'cmd',""), false);
    $class = Main\ArrayMethods::array_get($use_array,'object', NULL);
    $obj = new $class();

    $method = Main\ArrayMethods::array_get($use_array, 'method', NULL);
    $obj->$method($_POST);
}

if(isset($_GET['cmd']) && isset($_GET['id']))
{
    $cmd = Main\ArrayMethods::array_get($_GET, 'cmd', -1);
    $id = Main\ArrayMethods::array_get($_GET, 'id', -1);
    if($cmd == 'profile')
    {
        $profile = new Application\Frontend\Profile($id);
        $profile->Display();
    }



}
else if(isset($_GET['id']))
{
    $id = Main\ArrayMethods::array_get($_GET, 'id', -1);

    $task = new Application\Frontend\AdminTask($id);
    $task->Display();

}
else
{

    $task = new Application\Frontend\AdminTaskList();
    $task->Display();
}