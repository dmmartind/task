<?php
require("includes.php");

$success = '';
$error =  '';
if (isset($_POST['submit'])) {

    $login = Main\Registry::get('Register');
    $message = ($result = $login->processRegistry())?$result:[];
    $error = Main\ArrayMethods::array_get($message, 'error', "");


}
?>