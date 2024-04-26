<?php


namespace Main {

    /**
     * Class Registry
     * @package Main
     */
    class Registry
    {

        private static $_instances = [];


        private function __construct()
        {
        }

        public static function set($key, $instance = null)
        {
            self::$_instances[$key] = $instance;
        }

        public static function get($key, $default = null)
        {
            if (isset(self::$_instances[$key])) {
                return self::$_instances[$key];
            }
            return $default;
        }

        public static function erase($key)
        {
            unset(self::$_instances[$key]);
        }

        private function __clone()
        {
        }
    }
}