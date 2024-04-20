<?php


namespace Main
{
    if(session_id() === "") session_start();

    use Main\ArrayMethods as ArrayMethods;

    /**
     * Class Session
     * @package Main
     */
    class Session
    {
        /**
         * @var
         */
        private $token;

        /**
         * @return array|mixed|null
         */
        public static function getCSRFToken()
        {
            return ArrayMethods::array_get($_SESSION, 'token', 0);

        }

        /**
         * @return array|mixed|null
         */
        public static function isUserLoggedIn()
        {
            return ArrayMethods::array_get($_SESSION, 'loggedin', false);
        }

        /**
         * @return array|mixed|null
         */
        public static function getUserID()
        {
            return ArrayMethods::array_get($_SESSION, 'id', -1);
        }

        /**
         * @param $id
         * @param $email
         */
        public static function setSession($id, $email)
        {
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['login_user'] = $email;
            self::generateCSRF();
        }

        /**
         *
         */
        public static function generateCSRF()
        {
            if(!ArrayMethods::array_get($_SESSION, 'token', 0))
            {
                $_SESSION['token'] = self::random(40);
            }
        }

        /**
         * @param int $length
         * @return string
         * @throws \Exception
         */
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

        /**
         * @return bool
         */
        public static function getAuth()
        {
            $id = Session::getUserID();
            return User::getUserById($id);
        }

    }

}


