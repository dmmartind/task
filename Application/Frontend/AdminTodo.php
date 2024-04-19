<?php


namespace Application\Frontend
{
    if(session_id() === "") session_start();
    use Main\User as User;
    use Main\Session as Session;
    use Main\Header as Header;
    use Main\Registry as Registry;


    class AdminTodo
    {
        public static function getUserList()
        {
            $id = Session::getUserID();
            $user = self::getUserById($id);
            $header = new Header();


            if (!Session::isUserLoggedIn())
            {
                error_log("hurt1");
                header('/login');
            }


            if ($user['isAdmin'] != 1)
            {
                error_log("hurt2");
                header('/login');
            }


            $adminID = $user['id'];
            $usersWithTodos = User::getAllUsers();
            $userList = [];

            foreach ($usersWithTodos as $user) {
                if ($user['isAdmin'] == 1)
                    continue;
                $userList[$user['id']]['name'] = $user['name'];
                $taskCount = 0;
                $taskCount = self::getTaskCount($user['id']);

                $userList[$user['id']]['taskCount'] = $taskCount;
            }
            return $userList;

        }

        public static function getTaskCount($id)
        {
            $database = Registry::get("Database");
            if (!$database->_isValidService()) {
                $database = $database->connect();
            }
            if ($database->_isValidService()) {
                $query = $database->query()
                    ->from("users")
                    ->count();
                return $query;
            }


        }



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


        }



    }
}


