<?php

namespace Main {

    use Main\Core\Exception as Exception;

    /**
     * Class Core
     * @package Framework
     */
    class Core
    {
        /**
         * @var Array
         */
        private static Array $_loaded = [];
        /**
         * @var Array
         */
        private static Array $_paths = [
            ""
        ];

        /**
         * @throws \Exception
         */
        public static function initialize()
        {
            //var_dump("core init!!!!");
            if (!defined("APP_PATH")) {
                //var_dump("path not found");
                throw new \Exception("APP_PATH not defined");
            }

            //var_dump("continue");

            if (1) {
                //var_dump("true");
                $globals = ["_POST", "_GET", "_COOKIE", "_REQUEST", "_SESSION"];
                foreach ($globals as $global) {
                    //var_dump($global);
                    if (isset($GLOBALS[$global])) {
                        $GLOBALS[$global] = self::_clean($GLOBALS[$global]);
                    }
                }
            }

            $paths = array_map(
                function ($item) {
                    //var_dump(APP_PATH . $item);
                    return APP_PATH . $item;
                },
                self::$_paths
            );

            //var_dump($paths);

            $paths[] = get_include_path();

            //var_dump($paths);
            set_include_path(join(PATH_SEPARATOR, $paths));
            spl_autoload_register(__CLASS__ . "::_autoload");
        }

        /**
         * @param $array
         * @return array|string
         */
        protected static function _clean($array)
        {
            //var_dump("clean!!!");
            //var_dump($array);
            if (is_array($array)) {
                //var_dump("is array");
                return array_map(__CLASS__ . "::_clean", $array);
            } else {
                //var_dump("not array");
                return stripslashes($array);
            }
        }

        /**
         * @param $class
         * @throws Exception
         */
        protected static function _autoload($class)
        {
            //var_dump("autoload");
            $paths = explode(PATH_SEPARATOR, get_include_path());
            //var_dump("------------------");
            //var_dump($paths);
            //var_dump("/////////////////////");
            $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
            $file = strtolower(str_replace("\\", DIRECTORY_SEPARATOR, trim($class, "\\"))) . ".php";

            //var_dump($file);
            foreach ($paths as $path) {
                //var_dump($path);
                $combined = $path . DIRECTORY_SEPARATOR . $file;
                //var_dump($combined);
                if (file_exists($combined)) {
                    //var_dump("exists!!");
                    include($combined);
                    return;
                } else {
                    //var_dump("not!!!! exists");
                }
            }
            //var_dump("***************************");
            //var_dump($class);
            //var_dump("***************************");
            throw new Exception("{$class} not found");
        }


    }
}
