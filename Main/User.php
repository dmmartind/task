<?php


namespace Main {


    /**
     * Class User
     * @package Main
     */
    class User
    {

        /**
         * @return mixed
         */
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

        /**
         * @param $postArr
         */
        public function updateUser($postArr)
        {
            $id = Session::getUserID();
            $user = User::getUserById($id);
            if (Session::isUserLoggedIn() && $user) {
                if (!ArrayMethods::array_get($postArr, 'name', 0) && !ArrayMethods::array_get($postArr, 'email', 0)) {
                    return;
                }
                $name = ArrayMethods::array_get($postArr, 'name', 0);
                $email = ArrayMethods::array_get($postArr, 'email', 0);
                $database = Registry::get("Database");
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }
                $query = $database->query()
                    ->from("users")
                    ->where("id = ?", "{$id}")
                    ->save(['name' => $name, 'email' => $email]);
            }
        }

        /**
         * @param int $id
         * @return bool
         */
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

        /**
         * @param $postArr
         */
        public function updatePassword($postArr)
        {
            $id = Session::getUserID();
            $user = User::getUserById($id);
            if (Session::isUserLoggedIn() && $user) {
                if (!ArrayMethods::array_get($postArr, 'old_password', 0) && !ArrayMethods::array_get(
                        $postArr,
                        'new_password',
                        0
                    ) &&
                    !ArrayMethods::array_get($postArr, 'confirm_password', 0)) {
                    return;
                }

                $oldPass = ArrayMethods::array_get($postArr, 'old_password', 0);
                $newPass = ArrayMethods::array_get($postArr, 'new_password', 0);
                $confirmPass = ArrayMethods::array_get($postArr, 'confirm_password', 0);


                if ($newPass == $confirmPass) {
                    if (password_verify($oldPass, $user['password'])) {
                        $hashNewPass = password_hash($newPass, PASSWORD_DEFAULT);
                        $database = Registry::get("Database");
                        if (!$database->_isValidService()) {
                            $database = $database->connect();
                        }
                        $query = $database->query()
                            ->from("users")
                            ->where("id = ?", "{$id}")
                            ->save(['password' => $hashNewPass]);
                    }
                }
            }
        }

    }
}


