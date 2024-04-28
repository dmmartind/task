<?php
/**
 * ©2024 David Martin. All Rights Reserve.
 */
session_start();
if (session_destroy()) // Destroying All Sessions
{
    Main\Registry::clearAll();
    header("Location: index.php"); // Redirecting To Home Page
}
