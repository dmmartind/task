<?php


namespace Main {

    /**
     * Class Registry
     * @package Main
     */
    class Registry
    {

        /**
         * @var array
         */
        private static $_instances = [];


        /**
         * Registry constructor.
         */
        private function __construct()
        {
        }

        /**
         * @param $key
         * @param null $instance
         */
        public static function set($key, $instance = null)
        {
            self::$_instances[$key] = $instance;
        }

        /**
         * @param $key
         * @param null $default
         * @return mixed|null
         */
        public static function get($key, $default = null)
        {
            if (isset(self::$_instances[$key])) {
                return self::$_instances[$key];
            }
            return $default;
        }

        /**
         * @param $key
         */
        public static function erase($key)
        {
            unset(self::$_instances[$key]);
        }

        /**
         *
         */
        private function __clone()
        {
        }
    }
}