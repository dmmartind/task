<?php


namespace Application\Frontend {

    if (session_id() === "") {
        session_start();
    }

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
         * check user auth, get all the users, build an array with the data, and return the array
         * @return array|int
         */
        public static function getUserList()
        {
            $id = Session::getUserID();
            $user = self::getUserById($id);

            if (!is_array($user)) {
                header('/login');
                return 0;
            }

            if (ArrayMethods::array_get($user, 'success', 0) === false) {
                header('/login');
                return 0;
            }

            if (!Session::isUserLoggedIn()) {
                header('/login');
                return 0;
            }


            if ($user['isAdmin'] != 1) {
                header('/login');
                return 0;
            }

            $header = new Header();

            $adminID = $user['id'];
            $usersWithTodos = User::getAllUsers();
            $userList = [];

            foreach ($usersWithTodos as $user) {
                if ($user['isAdmin'] == 1) {
                    continue;
                }
                $userList[$user['id']]['name'] = $user['name'];
                $taskCount = 0;
                $taskCount = self::getTaskCount($user['id']);

                $userList[$user['id']]['taskCount'] = $taskCount;
            }
            return $userList;
        }

        /**
         * * takes the userID,  gets a db instance, connects the db,
         * builds and execute sql statement to delete task row.
         * @param $id
         * @return array
         */
        public static function getTaskCount($id)
        {
            if ($id == -1) {
                return ['success' => false, 'error' => 'bad arg'];
            }
            $database = Registry::get("Database");

            try {
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }

                $query = $database->query()
                    ->from("todos")
                    ->where("userID= ?", $id)
                    ->count();

                return $query;
            } catch (Sql $e) {
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }

        /**
         *  * getList
         * Desc: checks user auth, gets the tasks for the specified user, returns json with an empty array if their
         * was not tasks returned and if an error occured then it will return an error json. If the request had data,
         * it will build the array and return a json with the retrned tasks.
         * @return int
         */
        function getList()
        {
            if (isset($_REQUEST)) {
                $userID = ArrayMethods::array_get($_REQUEST, 'id', '');
            }

            $id = Session::getUserID();
            $user = self::getUserById($id);

            if (!is_array($user)) {
                header('/login');
                return 0;
            }

            if (ArrayMethods::array_get($user, 'success', 0) === false) {
                header('/login');
                return 0;
            }

            if (!Session::isUserLoggedIn()) {
                header('/login');
                return 0;
            }


            if ($user['isAdmin'] != 1) {
                header('/login');
                return 0;
            }

            $todos = $this->getTodosByID($userID);

            if ($todos === null || $todos === 0) {
                header('Content-type: application/json');
                echo json_encode(['success' => true, 'data' => []]);
                return 0;
            }

            if (is_array($todos)) {
                if (ArrayMethods::array_get($todos, 'success', 0) === false) {
                    header('HTTP/1.1 501 Internal Error');
                    echo json_encode($todos);
                    return 0;
                }
            }

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

            header('Content-type: application/json');
            echo json_encode(['success' => true, 'data' => $result]);
        }


        /**
         * @param int $id
         * @return array|int
         */
        public function getTodosByID(int $id)
        {
            if (!is_int($id)) {
                return ['success' => false, 'error' => "bad input"];
            }

            $database = Registry::get("Database");

            try {
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }
                $query = $database->query()
                    ->from("todos")
                    ->where("userid = ?", "{$id}")
                    ->order("priority", "desc")
                    ->all();
                if (empty($query)) {
                    return 0;
                } else {
                    return $query;
                }
            } catch (Sql $e) {
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }
    }
}


