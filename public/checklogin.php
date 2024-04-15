<?php
require("includes.php");

if(session_id() === "") session_start();
$error=''; // Variable To Store Error Message
$success = '';

if (isset($_POST['submit'])) {

    $login = Main\Registry::get('Login');
    $message = ($test = $login->processLogin())?$test:[];
    $error = Main\ArrayMethods::array_get($message, 'error', "");


}
?>
