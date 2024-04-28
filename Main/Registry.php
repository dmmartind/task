<?php

/**
 * ©2024 David Martin. All Rights Reserve.
 */

namespace Main {

    /**
     * Class Registry for holding instance to make them globally available for the app
     * @package Main
     */
    class Registry
    {

        /**
         * ;holds all the instances
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
         * sets the instance to the class instance array with the index key
         * @param $key
         * @param null $instance
         */
        public static function set($key, $instance = null)
        {
            self::$_instances[$key] = $instance;
        }

        /**
         * retrieves the instance of the name by key
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
         * erase all the instance
         */
        public static function clearAll()
        {
            if (is_array(self::$_instances)) {
                foreach (self::$_instances as $key => $instance) {
                    self::erase($key);
                }
            }
        }

        /**
         * erase the instance
         * @param $key
         */
        public static function erase($key)
        {
            unset(self::$_instances[$key]);
        }

        /**
         * auto clone function
         */
        private function __clone()
        {
        }
    }
}