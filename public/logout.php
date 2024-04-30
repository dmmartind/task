<?php

define("APP_PATH", dirname(__DIR__));
require(APP_PATH . DIRECTORY_SEPARATOR . "Main" . DIRECTORY_SEPARATOR . "Registry.php");
/**
 * ©2024 David Martin. All Rights Reserve.
 */
session_start();
if (session_destroy()) // Destroying All Sessions
{
    Main\Registry::clearAll();
    header("Location: index.php"); // Redirecting To Home Page
}
