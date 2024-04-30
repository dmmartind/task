<?php

/**
 * ©2024 David Martin. All Rights Reserve.
 */
require("includes.php");

//actions array to hold the cmd key, class namespace, and class method to call
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

//if cmd is found in the POST request, create the new class instance, and call the command
if (Main\ArrayMethods::array_get(
    $actions,
    Main\ArrayMethods::array_get($_POST, 'cmd', ""),
    false
)) {
    $use_array = Main\ArrayMethods::array_get(
        $actions,
        Main\ArrayMethods::array_get($_POST, 'cmd', ""),
        false
    );
    $class = Main\ArrayMethods::array_get($use_array, 'object', null);
    $obj = new $class();

    $method = Main\ArrayMethods::array_get($use_array, 'method', null);
    $obj->$method($_POST);
}
//if cmd is found in the GET request, create the new class instance, and call the command
if (Main\ArrayMethods::array_get($actions, Main\ArrayMethods::array_get($_GET, 'cmd', ""), false)) {
    $use_array = Main\ArrayMethods::array_get(
        $actions,
        Main\ArrayMethods::array_get($_GET, 'cmd', ""),
        false
    );
    $class = Main\ArrayMethods::array_get($use_array, 'object', null);
    $obj = new $class();

    $method = Main\ArrayMethods::array_get($use_array, 'method', null);
    $obj->$method();
} elseif (Main\ArrayMethods::array_get($_GET, 'id', -1) !== -1) {//if id query in the GET request
    $id = Main\ArrayMethods::array_get($_GET, 'id', -1);//create AdminTask instance and call display
//for the display of a users tsk list in the admin view
    $task = new Application\Frontend\AdminTask($id);
    $task->Display();
} else {
    $auth = Main\Session::getAuth();//else give me the admin page with the user list
    if ($auth) {
        if (Main\ArrayMethods::array_get($auth, 'isAdmin', 0) == 1) {
            $task = new Application\Frontend\AdminTaskList();
            $task->Display();
        } else {//if not admin just give user their task list because they are a regular user
            $task = new Application\Frontend\Task();
            $task->Display();
        }
    } else {//else failed and logout
        header("refresh:0; url=logout.php");
    }
}