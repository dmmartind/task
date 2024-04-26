<?php

namespace Main {

    use Main\Core\Exception as Exception;


    class Core
    {

        private static Array $_loaded = [];

        private static Array $_paths = [
            "/Main/Database",
            "/Main/Configuration",
            "/Main/Session",
            "/Main/Session/Login",
            "/application",
            ""
        ];


        private static Array $exceptionsPaths = [
            "ArrayMethods",
        ];


        public static function initialize()
        {
            if (!defined("APP_PATH")) {
                throw new \Exception("APP_PATH not defined");
            }


            $globals = ["_POST", "_GET", "_COOKIE", "_REQUEST", "_SESSION"];
            foreach ($globals as $global) {
                if (isset($GLOBALS[$global])) {
                    $GLOBALS[$global] = self::_clean($GLOBALS[$global]);
                }
            }

            $paths = array_map(
                function ($item) {
                    return APP_PATH . $item;
                },
                self::$_paths
            );

            $paths[] = get_include_path();

            set_include_path(join(PATH_SEPARATOR, $paths));
            spl_autoload_register(__CLASS__ . "::_autoload");
        }


        protected static function _clean($array)
        {
            if (is_array($array)) {
                return array_map(__CLASS__ . "::_clean", $array);
            } else {
                return stripslashes($array);
            }
        }


        protected static function _autoload($class)
        {
            if ($class == 'ArrayMethods') {
                return;
            }

            $paths = explode(PATH_SEPARATOR, get_include_path());
            $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
            $file = strtolower(str_replace("\\", DIRECTORY_SEPARATOR, trim($class, "\\"))) . ".php";

            foreach ($paths as $path) {
                $combined = $path . DIRECTORY_SEPARATOR . $file;
                if (file_exists($combined)) {
                    include($combined);
                    return;
                }
            }
            throw new Exception("{$class} not found");
        }


    }
}
