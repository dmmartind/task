<?php

namespace Framework\Auth {

    require("../registry.php");
    use Framework\Registry as Registry;

    class Login
    {
        private $userName;
        private $password;

        public function __construct()
        {
            error_log("start");
            // Define $username and $password
            $username = $this->userName = $this->array_get($_POST, 'username', "");
            $password = $this->password = $this->array_get($_POST, 'password', "");

            // Establishing Connection with Server by passing server_name, user_id and password as a parameter
            $database = Registry::get("Database");
            $database = $database->connect();
            if ($database->_isValidService()) {

                $creds = $this->filterCreds($username, $password, $database);

                $this->userName = $creds['username'];
                $this->password = $creds['password'];

                $all = $database->query()
                    ->from("users")
                    ->where("username = ?", "{$username}")
                    ->all();

                if (count($all) > 0) {
                    $hashed_password = $all["password"];

                    if (password_verify($password, $hashed_password)) {
                        // Set the session variables
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $all["id"];
                        $_SESSION['username'] = $username;


                        // Redirect to the user's dashboard
                        header("Location: dashboard.php");
                        exit;
                    } else {
                        $error =  "Username or Password is invalid! Please re-enter...";
                        header("Location: index.php");
                    }
                } else {
                    $error = "Username or Password is invalid! Please re-enter...";
                }
            }
        }

        public function filterCreds($username, $password, $db)
        {
            $result = [];
            $username = stripslashes($username);
            $password = stripslashes($password);
            $username = mysqli_real_escape_string($db->getService(), $username);
            $password = mysqli_real_escape_string($db->getService(), $password);
            $result = [
                'username' => $username,
                'password' => $password
            ];

            return $result;
        }

        public function array_get(Array $arr, $key, $default = null)
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

?>