<?php


namespace Main {

    /**
     * Class ArrayMethods
     * @package Main
     */
    abstract class ArrayMethods
    {


        public static function array_get(Array $arr, $key, $default = null)
        {
            if (!is_array($arr)) {
                return $default;
            }
            if (is_null($key)) {
                return $arr;
            }

            if (in_array($key, array_keys($arr))) {
                return $arr[$key];
            } else {
                return $default;
            }
        }


        public static function getFirst($array)
        {
            if (sizeof($array) == 0) {
                return null;
            }

            $keys = array_keys($array);
            return $array[$keys[0]];
        }

    }
}


