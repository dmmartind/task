<?php
require("includes.php");

$success = '';
$error =  '';
if (isset($_POST['submit'])) {

    $login = Main\Registry::get('Register');
    $message = ($test = $login->processRegistry())?$test:[];
    $error = Main\ArrayMethods::array_get($message, 'error', "");


}
?>