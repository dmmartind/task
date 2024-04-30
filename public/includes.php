<?php

/**
 * ©2024 David Martin. All Rights Reserve.
 */

//set paths
define("DEBUG", true);
define("APP_PATH", dirname(__DIR__));


require(APP_PATH . DIRECTORY_SEPARATOR . "Main" . DIRECTORY_SEPARATOR . "core.php");
require(APP_PATH . DIRECTORY_SEPARATOR . "Main" . DIRECTORY_SEPARATOR . "ArrayMethods.php");

//initialize core
Main\Core::initialize();

//set db configuration
$DB_config = new Main\Configuration(
    [
        "type" => "ini",
        "class" => "database"
    ]
);
//set mail configuration
$MAIL_config = new Main\Configuration(
    [
        "type" => "ini",
        "class" => "mail"
    ]
);

//set the instances of the db configuration to the registry
Main\Registry::set("DBConfiguration", $DB_config->initialize());
//set the instances of the main configuration to the registry
Main\Registry::set("MAILConfiguration", $MAIL_config->initialize());

//create the database instance and set it to the registry
$database = new Main\Database([]);
Main\Registry::set("Database", $database->initialize());
//create the mailer instance and set it to the registry
$mail = new Main\Mailer([]);
Main\Registry::set("Mailer", $mail->initialize());

//create the login instance and set it to the registry
$login = new Main\Session\Login();
Main\Registry::set("Login", $login);

//create the register instance and set it to the registry
$register = new Main\Session\Register();
Main\Registry::set("Register", $register);

$emailqueue = new Main\Queue\EmailQueue();
Main\Registry::set("email", $emailqueue);

$worker = new Main\Worker();
Main\Registry::set("Worker", $worker);








