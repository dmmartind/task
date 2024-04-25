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
            error_log("getUserList");
            $id = Session::getUserID();
            $user = self::getUserById($id);

            error_log("step1*****");
            if(!is_array($user))
            {
                header('/login');
                return 0;
            }
            error_log("step2*****");
            error_log(print_r($user, true));
            error_log(ArrayMethods::array_get($user, 'success', 0));
            if(ArrayMethods::array_get($user, 'success', 0) === false)
            {
                header('/login');
                return 0;
            }
            error_log("step3*****");
            if (!Session::isUserLoggedIn())
            {
                header('/login');
                return 0;
            }

            error_log("step4*****");
            if ($user['isAdmin'] != 1)
            {
                header('/login');
                return 0;
            }
            error_log("step5*****");
            $header = new Header();
            error_log("step6*****");
            $adminID = $user['id'];
            $usersWithTodos = User::getAllUsers();
            $userList = [];
            error_log("step7*****");
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
                return ['success' => false, 'error' => $e->getMessage()];
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
                return ['success' => false, 'error' => $e->getMessage()];
            }


        }


        function getList()
        {
            if(isset($_REQUEST))
            {
                $userID = ArrayMethods::array_get($_REQUEST, 'id', '');
            }

            $id = Session::getUserID();
            $user = self::getUserById($id);

            if(!is_array($user))
            {
                header('/login');
                return 0;
            }

            if(ArrayMethods::array_get($user, 'success', 0) === false)
            {
                header('/login');
                return 0;
            }

            if (!Session::isUserLoggedIn())
            {
                header('/login');
                return 0;
            }


            if ($user['isAdmin'] != 1)
            {
                header('/login');
                return 0;
            }

            $todos = $this->getTodosByID($userID);

            if ($todos === null || $todos === 0)
            {
                error_log("step2-1");
                error_log("test!!!!!!");
                header('Content-type: application/json');
                echo json_encode(['success' => true, 'data' => []]);
                return 0;
            }

            if(is_array($todos))
            {
                error_log("fail1");
                if(ArrayMethods::array_get($todos, 'success', 0) === false )
                {
                    error_log("fail2");
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


        public function getTodosByID(int $id)
        {
            if (!is_int($id))
                return ['success' => false, 'error' => "bad input"];

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
                if(empty($query))
                {
                    error_log("empty");
                    return 0;
                }
                else
                    return $query;

            } catch (Sql $e) {
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }
    }
}


