<?php
define("DEBUG", true);
define("APP_PATH", dirname(__DIR__));
$public = realpath (filter_input ( INPUT_SERVER , ' DOCUMENT_ROOT '));
define("APP_PUBLIC", $public);

require(APP_PATH . DIRECTORY_SEPARATOR ."Main" . DIRECTORY_SEPARATOR . "core.php");
require(APP_PATH . DIRECTORY_SEPARATOR . "Main" . DIRECTORY_SEPARATOR .  "ArrayMethods.php");


Main\Core::initialize();

$DB_config = new Main\Configuration([
                                                 "type" => "ini",
                                                 "class" => "database"
                                             ]);

$MAIL_config = new Main\Configuration([
                                        "type" => "ini",
                                        "class" => "mail"
                                    ]);

Main\Registry::set("DBConfiguration", $DB_config->initialize());

Main\Registry::set("MAILConfiguration", $MAIL_config->initialize());

$database = new Main\Database([]);
Main\Registry::set("Database", $database->initialize());

$mail = new Main\Mailer([]);
Main\Registry::set("Mailer", $mail->initialize());

$login = new Main\Session\Login();

Main\Registry::set("Login", $login);

$register = new Main\Session\Register();

Main\Registry::set("Register", $register);







