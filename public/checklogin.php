<?php
require("includes.php");

session_start(); // Starting Session
$error=''; // Variable To Store Error Message
$success = '';

if (isset($_POST['submit'])) {

    $login = Main\Registry::get('Login');
    $message = ($test = $login->processLogin())?$test:[];
    $error = Main\ArrayMethods::array_get($message, 'error', "");


}
?>
