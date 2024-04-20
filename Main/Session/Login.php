<?php

namespace Main\Session {

    use Main\User as User;
    use Main\ArrayMethods as ArrayMethods;
    use Main\Registry as Registry;
    use Main\Session as Session;

    /**
     * Class Login
     * @package Main\Session
     */
    class Login extends Session
    {
        /**
         * @var
         */
        private $email;
        /**
         * @var
         */
        private $password;

        /**
         * Login constructor.
         */
        public function __construct()
        {

        }

        /**
         * @return array
         */
        public function processLogin()
        {
            // Define $username and $password
            $email = $this->email = ArrayMethods::array_get($_POST, 'email', "");
            $password = $this->password = ArrayMethods::array_get($_POST, 'password', "");
            // Establishing Connection with Server by passing server_name, user_id and password as a parameter
            $database = Registry::get("Database");
            $database = $database->connect();
            if ($database->_isValidService()) {

                $creds = $this->filterCreds($email, $password, $database);

                $this->email = $creds['email'];
                $this->password = $creds['password'];

                $all = $database->query()
                    ->from("users")
                    ->where("email = ?", "{$email}")
                    ->all();

                if (count($all) > 0) {
                    $hashed_password = $all[0]["password"];
                    if (password_verify($password, $hashed_password)) {
                        // Set the session variables
                       $this->setSession($all[0]['id'], $email);

                        // Redirect to the user's dashboard
                        header("Location: dashboard.php");
                        exit;
                    } else {
                        return [
                            'error' => "email or Password is invalid! Please re-enter..."
                        ];
                    }
                } else {
                    return [
                        'error' => "email or Password is invalid! Please re-enter..."
                    ];
                }
            }
        }


        /**
         * @param $email
         * @param $password
         * @param $db
         * @return array
         */
        public function filterCreds($email, $password, $db)
        {
            $result = [];
            $email = stripslashes($email);
            $password = stripslashes($password);
            $email = mysqli_real_escape_string($db->getService(), $email);
            $password = mysqli_real_escape_string($db->getService(), $password);
            $result = [
                'email' => $email,
                'password' => $password
            ];

            return $result;
        }

        }
}

?>