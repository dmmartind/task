<?php

require("includes.php");

$success = '';
$error = '';

//if correct request, gets the register instance, calls the register processor and returns error if register fails
if (isset($_POST['submit'])) {
    $login = Main\Registry::get('Register');
    $message = ($result = $login->processRegistry()) ? $result : [];
    $error = Main\ArrayMethods::array_get($message, 'error', "");
}
