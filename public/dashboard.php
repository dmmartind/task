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

if ( Main\ArrayMethods::array_get($actions,Main\ArrayMethods::array_get($_POST, 'cmd',""), false) )
{
    $use_array = Main\ArrayMethods::array_get($actions,Main\ArrayMethods::array_get($_POST, 'cmd',""), false);
    $class = Main\ArrayMethods::array_get($use_array,'object', NULL);
    $obj = new $class();

    $method = Main\ArrayMethods::array_get($use_array, 'method', NULL);
    $obj->$method($_POST);
}

if ( Main\ArrayMethods::array_get($actions,Main\ArrayMethods::array_get($_GET, 'cmd',""), false) )
{
    $use_array = Main\ArrayMethods::array_get($actions,Main\ArrayMethods::array_get($_GET, 'cmd',""), false);
    $class = Main\ArrayMethods::array_get($use_array,'object', NULL);
    $obj = new $class();

    $method = Main\ArrayMethods::array_get($use_array, 'method', NULL);
    $obj->$method();
}
else if(Main\ArrayMethods::array_get($_GET, 'id',-1) !== -1)
{
    $id = Main\ArrayMethods::array_get($_GET, 'id', -1);

    $task = new Application\Frontend\AdminTask($id);
    $task->Display();

}
else {
    $auth = Main\Session::getAuth();
    if ($auth) {
        if (Main\ArrayMethods::array_get($auth, 'isAdmin', 0) == 1) {
            $task = new Application\Frontend\AdminTaskList();
            $task->Display();
        } else {
            $task = new Application\Frontend\Task();
            $task->Display();
        }
    } else {
        header("refresh:0; url=logout.php");
    }
}