<?php


namespace Main {

    /**
     * Class ArrayMethods
     * @package Main
     */
    abstract class ArrayMethods
    {


        /**
         * safe array getter
         * @param array $arr
         * @param $key
         * @param null $default
         * @return array|mixed|null
         */
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


        /**
         * retuns the first index of an array
         * @param $array
         * @return mixed|null
         */
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


