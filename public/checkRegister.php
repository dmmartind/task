<?php
$success = '';
$error =  '';
if (isset($_POST['submit'])) {

    $login = Main\Registry::get('Register');
    $message = $login->processRegistry();
    $error = $message['error'];


}
?>