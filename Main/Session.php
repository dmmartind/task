<?php


namespace Main
{
    class Session
    {
        private $token;

        public static function getCSRFToken()
        {
            return self::array_get($_SESSION, 'token', 0);

        }

        public static function setSession($id, $email)
        {
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['login_user'] = $email;
            self::generateCSRF();
        }

        public static function generateCSRF()
        {
            if(!self::array_get($_SESSION, 'token', 0))
            {
                $_SESSION['token'] = self::random(40);
            }
        }

        public static function random($length = 16)
        {
            $string = '';
            while(($len = strlen($string)) < $length)
            {
                $size = $length - $len;
                $bytesSize = (int)ceil($size / 3) * 3;
                $bytes = random_bytes($bytesSize);
                $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
            }

            return $string;
        }

        public static function array_get(Array $arr, $key, $default = null)
        {
            //var_dump("start");
            if(!is_array($arr))
                return $default;
            //var_dump("step1");
            if(is_null($key))
                return $arr;
            //var_dump("step2");
            //var_dump(array_keys($arr));
            //var_dump(in_array($key,array_keys($arr)));
            if(in_array($key,array_keys($arr)))
            {
                //var_dump("good");
                //var_dump($key);
                return $arr[$key];
            }
            else
            {
                //var_dump("not good");
                //var_dump($key);
                return $default;
            }
        }

    }

}


