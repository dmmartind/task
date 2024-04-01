<?php
define("DEBUG", true);
define("APP_PATH", dirname(__DIR__));

require("../Main/core.php");


Main\Core::initialize();

$configuration = new Main\Configuration([
                                                 "type" => "ini",
                                                 "class" => "database"
                                             ]);

Main\Registry::set("Configuration", $configuration->initialize());

$database = new Main\Database([]);
Main\Registry::set("Database", $database->initialize());

$login = new Main\Session\Login();

Main\Registry::set("Login", $login);

