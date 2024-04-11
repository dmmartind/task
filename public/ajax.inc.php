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
$actions = array(
    'task_update' => array(
        'object' => 'Application\Frontend\Todo',
        'method' => 'postUpdate'
    )
);

/*
 * Make sure the anti-CSRF token was passed and that the
 * requested action exists in the lookup array
 */

if ( ArrayMethods::array_get($actions,ArrayMethods::array_get($_POST, 'action',""), false) )
{

    //var_dump($actions);
    //var_dump($_POST['action']);
    //var_dump($actions[$_POST['action']]);
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
else
{
    echo ["error" => "Error"];
}



?>