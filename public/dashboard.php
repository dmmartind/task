<?php

require("includes.php");





$actions = [
    'update_user' => [
        'object' => 'Main\User',
        'method' => 'updateUser'
    ],
    'update_password' => [
        'object' => 'Main\User',
        'method' => 'updatePassword'
    ],
    'profile' => [
        'object' => 'Application\Frontend\Profile',
        'method' => 'display'
    ],
];

error_log(print_r($_POST, true));
if ( Main\ArrayMethods::array_get($actions,Main\ArrayMethods::array_get($_POST, 'cmd',""), false) )
{
    error_log("got in");
    $use_array = Main\ArrayMethods::array_get($actions,Main\ArrayMethods::array_get($_POST, 'cmd',""), false);
    $class = Main\ArrayMethods::array_get($use_array,'object', NULL);
    $obj = new $class();

    $method = Main\ArrayMethods::array_get($use_array, 'method', NULL);
    $obj->$method($_POST);
}

if ( Main\ArrayMethods::array_get($actions,Main\ArrayMethods::array_get($_GET, 'cmd',""), false) )
{
    error_log("got in");
    $use_array = Main\ArrayMethods::array_get($actions,Main\ArrayMethods::array_get($_GET, 'cmd',""), false);
    $class = Main\ArrayMethods::array_get($use_array,'object', NULL);
    $obj = new $class();

    $method = Main\ArrayMethods::array_get($use_array, 'method', NULL);
    $obj->$method();
}

//if(isset($_GET['cmd']) && isset($_GET['id']))
//{
//    $cmd = Main\ArrayMethods::array_get($_GET, 'cmd', -1);
//    $id = Main\ArrayMethods::array_get($_GET, 'id', -1);
//    if($cmd == 'profile')
//    {
//        $profile = new Application\Frontend\Profile($id);
//        $profile->Display();
//    }



//}
else if(isset($_GET['id']))
{
    error_log("test1-2");
    $id = Main\ArrayMethods::array_get($_GET, 'id', -1);

    $task = new Application\Frontend\AdminTask($id);
    $task->Display();

}
else
{
    error_log("test1-3");

    $auth = Main\Session::getAuth();
    if($auth)
    {
        if(Main\ArrayMethods::array_get($auth, 'isAdmin', 0) == 1)
        {
            $task = new Application\Frontend\AdminTaskList();
            $task->Display();
        }
        else
        {
            $task = new Application\Frontend\Task();
            $task->Display();
        }

    }
    else
    {
        error_log("test1-4");
        header("refresh:2; url=logout.php");
    }




}