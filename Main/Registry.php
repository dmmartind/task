<?php


namespace Main
{
    class Registry
    {
        private static $_instances = [];

        private function __construct()
        {
        }

        private function __clone()
        {
        }

        public static function set($key, $instance = null)
        {
            //var_dump("set");
            //var_dump($key);
            //var_dump($instance);
            self::$_instances[$key] = $instance;
        }

        public static function get($key, $default = null)
        {
            //var_dump("get");
            //var_dump(self::$_instances);
            //var_dump($key);
            if (isset(self::$_instances[$key])) {
                //var_dump("test");
                return self::$_instances[$key];
            }
            //var_dump("fail");
            return $default;
        }

        public static function erase($key)
        {
            unset(self::$_instances[$key]);
        }
    }
}

?>