<?php


namespace Main\Session {

    use Main\ArrayMethods as ArrayMethods;
    use Main\Registry as Registry;
    use Main\Session as Session;


    /**
     * Class Register
     * @package Main\Session
     */
    class Register extends Session
    {

        /**
         * @var
         */
        private $password;

        /**
         * @var
         */
        private $email;

        /**
         * @var
         */
        private $name;


        /**
         * Register constructor.
         */
        public function __construct()
        {
        }


        /**
         * @return array
         */
        public function processRegistry()
        {
            $database = Registry::get("Database");
            $database = $database->connect();
            if ($database->_isValidService()) {
                $email = $this->email = ArrayMethods::array_get($_POST, 'email', "");
                $name = $this->name = ArrayMethods::array_get($_POST, 'name', "");
                $password = $this->password = ArrayMethods::array_get($_POST, 'password', "");
                $confirm = $this->password = ArrayMethods::array_get($_POST, 'confirm', "");

                if ($password !== $confirm) {
                    return [
                        "error" => "Passwords do not match"
                    ];
                }

                $creds = $this->filterCreds($email, $name, $password, $confirm, $database);
                $this->email = $creds['email'];
                $this->password = $creds['password'];
                $this->confirm = $creds['confirm'];

                // Hash the password
                $password = $this->password = password_hash($password, PASSWORD_DEFAULT);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $all = $database->query()
                        ->from("users")
                        ->where("email = ?", $this->email)
                        ->all();

                    if (count($all) == 0) {
                        $id = $database->query()
                            ->from("users")
                            ->save(
                                [
                                    "email" => $this->email,
                                    "name" => $this->name,
                                    "password" => $this->password
                                ]
                            );

                        if ($id) {
                            $success = "User created successfully. Redirecting.....";
                            header("refresh:0; url=index.php");
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
            } else {
                return [
                    "error" => "Could not connect to the database!"
                ];
            }
        }


        /**
         * @param $email
         * @param $name
         * @param $password
         * @param $confirm
         * @param $db
         * @return array
         */
        public function filterCreds($email, $name, $password, $confirm, $db)
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
                'name' => $name,
                'password' => $password,
                'confirm' => $confirm
            ];

            return $result;
        }

    }
}