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

        }

        public function processLogin()
        {
            $database = Registry::get("Database");
            $database = $database->connect();
            if ($database->_isValidService()) {

                $username = $this->userName = $this->array_get($_POST, 'username', "");
                $password = $this->password = $this->array_get($_POST, 'password', "");
                $confirm = $this->password = $this->array_get($_POST, 'confirm', "");

                if($password === $confirm)
                    return [
                      "error" => "Passwords do not match"
                    ];

                $creds = $this->filterCreds($username, $password,$confirm, $database);
                $this->userName = $creds['username'];
                $this->password = $creds['password'];
                $this->confirm = $creds['confirm'];

                // Hash the password
                $password = $this->password = password_hash($password, PASSWORD_DEFAULT);
                if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
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

        public function test()
        {
            // Define $username and $password


            if ( empty($town) or empty($county) or empty($tel) )
            {
                $town = "Not Set";
                $county = "Not Set";
                $tel = "0830000000";
            }

            // Establishing Connection with Server by passing server_name, user_id and password as a parameter
            $conn= mysqli_connect("localhost", "db", "A700ttlckult", "compsys");

            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // To protect MySQL injection for Security purpose
            $surname = stripslashes($surname );
            $forename = stripslashes($forename);
            $email = stripslashes($email);
            $town = stripslashes($town);
            $county = stripslashes($county);
            $tel = stripslashes($tel);
            $username = stripslashes($username);
            $password = stripslashes($password);

            $surname = mysqli_real_escape_string($conn, $surname );
            $forename = mysqli_real_escape_string($conn, $forename);
            $email = mysqli_real_escape_string($conn, $email);
            $town = mysqli_real_escape_string($conn, $town);
            $county = mysqli_real_escape_string($conn, $county);
            $tel = mysqli_real_escape_string($conn, $tel);
            $username = mysqli_real_escape_string($conn, $username);
            $password = mysqli_real_escape_string($conn, $password);

            if (strlen($username) <= 11) {
                if ( is_numeric($tel) ) {
                    $query = "SELECT * FROM staff WHERE username = '$username'";
                    $valid = mysqli_query($conn, $query);

                    if (!$valid) {
                        $error = "Could not connect to the database!";
                    }

                    if (mysqli_num_rows($valid) == 0 ) {
                        $sql = "INSERT INTO staff (forename, surname, username, password, email, town, county, tel)
					VALUES ('$forename', '$surname', '$username', '$password', '$email', '$town', '$county', '$tel');";
                        $res = mysqli_query($conn, $sql);

                        if (!$res) {
                            $error = "Error registering....";
                        }

                        if (mysqli_affected_rows($conn) == 1) {
                            $success =  "Staff created successfully. Redirecting.....";
                            header("refresh:5; url=index.php");

                        } else {
                            $error =  ("Could not register due to system error!");
                        }

                    } else {
                        $error = "The user name already exist in the system.";
                    }

                } else {
                    $error = "Telephone number should numeric!";
                }

            } else {
                $error = "Username should be <= 11 characters long!";
            }

            mysqli_close($conn);
        }

        public function filterCreds($username, $password, $confirm, $db)
        {
            $result = [];
            $username = stripslashes($username);
            $password = stripslashes($password);
            $confirm = stripslashes($confirm);
            $username = mysqli_real_escape_string($db->getService(), $username);
            $password = mysqli_real_escape_string($db->getService(), $password);
            $confirm = mysqli_real_escape_string($db->getService(), $confirm);
            $result = [
                'username' => $username,
                'password' => $password,
                'confirn'  => $confirm
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