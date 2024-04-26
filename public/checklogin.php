<?php

require("includes.php");

if (session_id() === "") {
    session_start();
}
$error = ''; // Variable To Store Error Message
$success = '';

if (isset($_POST['submit'])) {
    $login = Main\Registry::get('Login');
    $message = ($result = $login->processLogin()) ? $result : [];
    $error = Main\ArrayMethods::array_get($message, 'error', "");
}

