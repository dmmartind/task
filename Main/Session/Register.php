<?php


namespace Main\Session {

    use Main\Auth as Auth;
    use Main\Registry as Registry;

    class Register
    {
        private $password;
        private $email;
        private $name;

        public function __construct()
        {

        }

        public function processRegistry()
        {
            $database = Registry::get("Database");
            $database = $database->connect();
            if ($database->_isValidService()) {

                $email = $this->email = self::array_get($_POST, 'email', "");
                $name = $this->name = self::array_get($_POST, 'name', "");
                $password = $this->password = self::array_get($_POST, 'password', "");
                $confirm = $this->password = self::array_get($_POST, 'confirm', "");

                //var_dump($this->email);
                if($password !== $confirm)
                    return [
                      "error" => "Passwords do not match"
                    ];

                $creds = $this->filterCreds($email,$name, $password,$confirm, $database);
                $this->email = $creds['email'];
                $this->password = $creds['password'];
                $this->confirm = $creds['confirm'];

                //var_dump($this->email);

                // Hash the password
                $password = $this->password = password_hash($password, PASSWORD_DEFAULT);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $all = $database->query()
                        ->from("users")
                        ->where("email = ?", $this->email)
                        ->all();

                    if(count($all) == 0)
                    {
                        $id = $database->query()
                            ->from("users")
                            ->save([
                                       "email" => $this->email,
                                       "name" => $this->name,
                                       "password" => $this->password
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
                        "error" => "email is not a valid email"
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


        public function filterCreds($email,$name, $password, $confirm, $db)
        {
            $result = [];
            $email = stripslashes($email);
            $name = stripslashes($name);
            $password = stripslashes($password);
            $confirm = stripslashes($confirm);
            $email = mysqli_real_escape_string($db->getService(), $email);
            $name = mysqli_real_escape_string($db->getService(), $name);
            $password = mysqli_real_escape_string($db->getService(), $password);
            $confirm = mysqli_real_escape_string($db->getService(), $confirm);
            $result = [
                'email' => $email,
                'name' =>$name,
                'password' => $password,
                'confirm'  => $confirm
            ];

            return $result;
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

?>