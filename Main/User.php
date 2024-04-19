<?php


namespace Main
{
    class User
    {
        public static function getUserById(int $id)
        {
            $database = Registry::get("Database");
            if (!$database->_isValidService()) {
                $database = $database->connect();
            }
            if ($database->_isValidService()) {
                $query = $database->query()
                    ->from("users")
                    ->where("id = ?", "{$id}")
                    ->first();
                return $query;
            }

            return false;


        }

        public function updateUser($postArr)
        {
            //error_log("updateUser");
            $id = Session::getUserID();
            $user = User::getUserById($id);
            if (Session::isUserLoggedIn() && $user) {
                //error_log("loggedin");
                //error_log(print_r($postArr));
                if (!ArrayMethods::array_get($postArr, 'name', 0) && !ArrayMethods::array_get($postArr, 'email', 0)) {
                    return;
                }
                //error_log("extract");
                $name = ArrayMethods::array_get($postArr, 'name', 0);
                $email = ArrayMethods::array_get($postArr, 'email', 0);
                //error_log("database");
                $database = Registry::get("Database");
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }
                $query = $database->query()
                    ->from("users")
                    ->where("id = ?", "{$id}")
                    ->save(['name' => $name, 'email' => $email]);
                //error_log("result");
                //error_log($query);
            }
        }

        public function updatePassword($postArr)
        {
            error_log("updatePassword");
            $id = Session::getUserID();
            $user = User::getUserById($id);
            if (Session::isUserLoggedIn() && $user) {
               error_log("loggedin");
                error_log(print_r($postArr));
                if (!ArrayMethods::array_get($postArr, 'old_password', 0) && !ArrayMethods::array_get($postArr, 'new_password', 0) &&
                    !ArrayMethods::array_get($postArr, 'confirm_password', 0)) {
                    return;
                }
                error_log("extract");
                $oldPass = ArrayMethods::array_get($postArr, 'old_password', 0);
                $newPass = ArrayMethods::array_get($postArr, 'new_password', 0);
                $confirmPass = ArrayMethods::array_get($postArr, 'confirm_password', 0);
                error_log($newPass);
                error_log($confirmPass);
                error_log("before");

                if($newPass == $confirmPass)
                {
                    error_log("confirmed!!!");
                    if(password_verify($oldPass, $user['password']))
                    {
                        error_log("old password correct!!");
                        error_log("passed");
                        $hashNewPass = password_hash($newPass, PASSWORD_DEFAULT);
                        $database = Registry::get("Database");
                        if (!$database->_isValidService()) {
                            $database = $database->connect();
                        }
                        $query = $database->query()
                            ->from("users")
                            ->where("id = ?", "{$id}")
                            ->save(['password' => $hashNewPass]);
                        //error_log("result");
                        //error_log($query);

                    }

                }

            }
        }

        public static function getAllUsers()
        {
            $database = Registry::get("Database");
            if (!$database->_isValidService()) {
                $database = $database->connect();
            }
            if ($database->_isValidService()) {
                $query = $database->query()
                    ->from("users")
                    ->all();
                return $query;
            }

        }

    }
}


