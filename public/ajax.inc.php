<?php
/**
 * ©2024 David Martin. All Rights Reserve.
 */
/*
 * Enable sessions
 */
session_start();

require("includes.php");

use Main\ArrayMethods as ArrayMethods;
use Main\Core\Exception as Exception;

/*
 * Create a lookup array for form actions
 */
$actions = [
    'task_update' => [
        'object' => 'Application\Frontend\Todo',
        'method' => 'postUpdate'
    ],
    'task_delete' => [
        'object' => 'Application\Frontend\Todo',
        'method' => 'postDelete'
    ],
    'task_add' => [
        'object' => 'Application\Frontend\Todo',
        'method' => 'postAdd'
    ],
    'getlist' => [
        'object' => 'Application\Frontend\Todo',
        'method' => 'getList'
    ],
    'admin_getlist' => [
        'object' => 'Application\Frontend\AdminTodo',
        'method' => 'getList'
    ]
];

//handles POST and GET request
try {
    if (ArrayMethods::array_get($actions, ArrayMethods::array_get($_POST, 'action', ""), false)) {
        $use_array = $actions[$_POST['action']];
        $class = ArrayMethods::array_get($use_array, 'object', null);
        $obj = new $class();


        if (ArrayMethods::array_get($_POST, 'data', false)) {
            $data = ArrayMethods::array_get($_POST, 'data', false);
            $item = json_decode($data, 1);
        } else {
            $item = null;
        }
        $method = ArrayMethods::array_get($use_array, 'method', null);
        $obj->$method($item);
    } elseif (ArrayMethods::array_get($actions, ArrayMethods::array_get($_GET, 'action', ""),
                                      false)) {
        $use_array = $actions[$_GET['action']];
        $class = ArrayMethods::array_get($use_array, 'object', null);
        $obj = new $class();


        if (ArrayMethods::array_get($_GET, 'data', false)) {
            $data = ArrayMethods::array_get($_GET, 'data', false);
            $item = json_decode($data, 1);
        } else {
            $item = null;
        }
        $method = ArrayMethods::array_get($use_array, 'method', null);
        $obj->$method($item);
    }
} catch (Exception $e) {
    //failed request and redirects to logout
    error_log($e->getMessage());
    header("refresh:0, url:logout.php");
}