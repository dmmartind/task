<?php


namespace Main\Session {

    use Main\Auth as Auth;
    use Main\Registry as Registry;

    class Register
    {
        private $userName;
        private $password;
        private $email;

        public function __construct()
        {
            $database = Registry::get("Database");
            $database = $database->connect();
            if ($database->_isValidService()) {
                $username = $this->userName = $this->array_get($_POST, 'username', "");
                $password = $this->password = $this->array_get($_POST, 'password', "");
                $email = $this->email = $this->array_get($_POST, 'email', "");

                $creds = $this->filterCreds($username, $password,$email, $database);
                $this->userName = $creds['username'];
                $this->password = $creds['password'];
                $this->email = $creds['email'];

                // Hash the password
                $password = $this->password = password_hash($password, PASSWORD_DEFAULT);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $all = $database->query()
                        ->from("users")
                        ->where("username = ?", $this->userName)
                        ->all();

                    if(count($all) == 0)
                    {
                        $id = $database->query()
                            ->from("users")
                            ->save([
                                       "username" => $this->userName,
                                       "password" => $this->password,
                                       "email"   => $this->email
                                   ]);

                        if ($id) {
                            $success =  "User created successfully. Redirecting.....";
                            header("refresh:5; url=index.php");

                        } else {
                            return [
                                "error" => "Could not register due to system error!"
                            ];
                        }

                    } else {
                        return [
                            "error" => "The user name already exist in the system."
                        ];

                    }

                } else {
                    return [
                        "error" => "username is not a valid email"
                    ];
                }
            }
            else
            {
                return [
                    "error" =>  "Could not connect to the database!"
                ];
            }
        }

        public function filterCreds($username, $password, $email, $db)
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