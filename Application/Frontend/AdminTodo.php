<?php


namespace Application\Frontend
{
    if(session_id() === "") session_start();
    use Main\User as User;
    use Main\Session as Session;
    use Main\Header as Header;
    use Main\Registry as Registry;
    use Main\ArrayMethods as ArrayMethods;



    class AdminTodo
    {

        public static function getUserList()
        {
            $id = Session::getUserID();
            $user = self::getUserById($id);

            if(is_array($user) && ArrayMethods::array_get($user, 'status', false))
                return $user;
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


        public static function getTaskCount($id)
        {
            $database = Registry::get("Database");

            try{
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }

                    $query = $database->query()
                        ->from("todos")
                        ->where("userID= ?", $id)
                        ->count();

                    return $query;
            }
            catch(Sql $e)
            {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }



        public static function getUserById(int $id)
        {
            $database = Registry::get("Database");
            try {
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }

                    $query = $database->query()
                        ->from("users")
                        ->where("id = ?", "{$id}")
                        ->first();
                    return $query;
            }
            catch(Sql $e)
            {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }


        }


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

            if(is_array($todos) && ArrayMethods::array_get($todos, 'status', false))
                return $todos;
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


        public function getTodosByID(int $id)
        {
            if (!is_int($id))
                return ['status' => 'error', 'message' => "bad input"];

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
                return $query;
					
                } catch (Sql $e) {
                    return ['status' => 'error', 'message' => $e->getMessage()];
                }
        }
    }
}


