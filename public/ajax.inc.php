<?php

/*
 * Enable sessions
 */
session_start();

require("includes.php");

use Main\ArrayMethods as ArrayMethods;
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
    ]
];

/*
 * Make sure the anti-CSRF token was passed and that the
 * requested action exists in the lookup array
 */

if ( ArrayMethods::array_get($actions,ArrayMethods::array_get($_POST, 'action',""), false) )
{
    $use_array = $actions[$_POST['action']];
    $class = ArrayMethods::array_get($use_array,'object', NULL);
    $obj = new $class();


    /*
     * Check for an ID and sanitize it if found
     */
    if ( ArrayMethods::array_get($_POST,'data', false))
    {
        $data = ArrayMethods::array_get($_POST,'data', false);
        $item = json_decode($data,1);
    }
    else {
        $item = NULL;
         }
    $method = ArrayMethods::array_get($use_array, 'method', NULL);
    $obj->$method($item);
}
else if ( ArrayMethods::array_get($actions,ArrayMethods::array_get($_GET, 'action',""), false) )
{
    $use_array = $actions[$_GET['action']];
    $class = ArrayMethods::array_get($use_array,'object', NULL);
    $obj = new $class();


    /*
     * Check for an ID and sanitize it if found
     */
    if ( ArrayMethods::array_get($_GET,'data', false))
    {
        $data = ArrayMethods::array_get($_GET,'data', false);
        $item = json_decode($data,1);
    }
    else {
        $item = NULL;
    }
    $method = ArrayMethods::array_get($use_array, 'method', NULL);
    $obj->$method($item);


}
else
{
    echo print_r($_POST, true);
}



?>