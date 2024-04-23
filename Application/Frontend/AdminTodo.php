<?php


namespace Application\Frontend
{
    if(session_id() === "") session_start();
    use Main\User as User;
    use Main\Session as Session;
    use Main\Header as Header;
    use Main\Registry as Registry;
    use Main\ArrayMethods as ArrayMethods;


    /**
     * Class AdminTodo
     * @package Application\Frontend
     */
    class AdminTodo
    {
        /**
         * @return array
         */
        public static function getUserList()
        {
            $id = Session::getUserID();
            $user = self::getUserById($id);
            $header = new Header();


            if (!Session::isUserLoggedIn())
            {
                header('/login');
            }


            if ($user['isAdmin'] != 1)
            {
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

        /**
         * @param $id
         * @return mixed
         */
        public static function getTaskCount($id)
        {
            $database = Registry::get("Database");
            if (!$database->_isValidService()) {
                $database = $database->connect();
            }
            if ($database->_isValidService()) {
                $query = $database->query()
                    ->from("todos")
                    ->where("userID= ?", $id)
                    ->count();

                return $query;
            }


        }


        /**
         * @param int $id
         * @return mixed
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
         * getList
         * Desc: returns a user's task list
         * @return array
         */
        function getList()
        {
            if(isset($_REQUEST))
            {
                $userID = ArrayMethods::array_get($_REQUEST, 'id', '');
            }

            if (Session::isUserLoggedIn() === null)
            {
                header('/login');
            }

            $todos = $this->getTodosByID($userID);
            if ($todos === null)
                return [];
            $result = [];

            foreach ($todos as $info) {
                $id = ArrayMethods::array_get($info, 'id', "");
                $title = ArrayMethods::array_get($info, 'title', "");
                $completed = ArrayMethods::array_get($info, 'completed', "");
                $guid = ArrayMethods::array_get($info, 'guid', "");
                $priority = ArrayMethods::array_get($info, 'priority', "");
                $dbId = ArrayMethods::array_get($info, 'id', "");
                $userId = ArrayMethods::array_get($info, 'userID', "");
                $completed = ($completed == 0) ? false : true;

                $temArr = [
                    'id' => $id,
                    'title' => $title,
                    'completed' => $completed,
                    'guid' => $guid,
                    'priority' => $priority,
                    'dbId' => $dbId,
                    'userId' => $userId
                ];
                $result[] = $temArr;
            }
			
            header("Content-Type: application/json");
            echo json_encode($result);

        }

        /**
         * getTodosByID
         * DESC: Utility function top return a task query object by id
         * @param int $id
         *
         */
        public function getTodosByID(int $id)
        {
            if (!is_int($id))
                return null;
            $database = Registry::get("Database");
            if (!$database->_isValidService()) {
                $database = $database->connect();
            }
            if ($database->_isValidService()) {
                try {
                    $query = $database->query()
                        ->from("todos")
                        ->where("userid = ?", "{$id}")
                        ->order("priority", "desc")
                        ->all();
                    return $query;

                    //return $query;
                } catch (QueryException $e) {
                    return null;
                }
            }

        }



    }
}


