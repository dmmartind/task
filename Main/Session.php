<?php


namespace Main {

    if (session_id() === "") {
        session_start();
    }

    use Main\ArrayMethods as ArrayMethods;


    /**
     * Class Session
     * contains all the seesion functions
     * @package Main
     */
    class Session
    {

        /**
         * @var
         */
        private $token;


        /**
         * returns the CSRF function
         * @return array|mixed|null
         */
        public static function getCSRFToken()
        {
            return ArrayMethods::array_get($_SESSION, 'token', 0);
        }


        /**
         * checks if a logged user in the session var
         * @return array|mixed|null
         */
        public static function isUserLoggedIn()
        {
            return ArrayMethods::array_get($_SESSION, 'loggedin', false);
        }

        /**
         * sets the user session for being log-in
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
         * generates a new CSRF token
         */
        public static function generateCSRF()
        {
            if (!ArrayMethods::array_get($_SESSION, 'token', 0)) {
                $_SESSION['token'] = self::random(40);
            }
        }

        /**
         * random token function
         * @param int $length
         * @return string
         * @throws \Exception
         */
        public static function random($length = 16)
        {
            $string = '';
            while (($len = strlen($string)) < $length) {
                $size = $length - $len;
                $bytesSize = (int)ceil($size / 3) * 3;
                $bytes = random_bytes($bytesSize);
                $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
            }

            return $string;
        }

        /**
         * returns whether a user is authenticated
         * @return bool
         */
        public static function getAuth()
        {
            $id = Session::getUserID();

            return User::getUserById($id);
        }

        /**
         * get userId of authentcated user from the session
         * @return array|mixed|null
         */
        public static function getUserID()
        {
            return ArrayMethods::array_get($_SESSION, 'id', -1);
        }

    }
}


