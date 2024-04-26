<?php
/**
 * ©2024 David Martin. All Rights Reserve.
 */

require("includes.php");
//session start
if (session_id() === "") {
    session_start();
}
$error = ''; // Variable To Store Error Message
$success = '';

//if correct request, gets the login instance, calls the login processor and returns error if login fails
if (isset($_POST['submit'])) {
    $login = Main\Registry::get('Login');
    $message = ($result = $login->processLogin()) ? $result : [];
    $error = Main\ArrayMethods::array_get($message, 'error', "");
}

