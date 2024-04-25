<?php


namespace Application\Frontend
{
    if(session_id() === "") session_start();

    use Main\ArrayMethods;
    use Main\Registry as Registry;
    use Main\Session as Session;
    use Main\Header as Header;
    use Main\Database\Exception\Sql as Sql;
    use Main\User as User;



    class Todo
    {
        public function postAdd($item)
        {
            $header = new Header();
            if ($header->isAjax() && $item !== null) {
                $aRequest = $item;
                $title = ArrayMethods::array_get($aRequest, 'title', -1);
                $completed = ArrayMethods::array_get($aRequest, 'completed', -1);
                $guid = ArrayMethods::array_get($aRequest, 'guid', -1);
                $priority = ArrayMethods::array_get($aRequest, 'priority', -1);
                $dbId = ArrayMethods::array_get($aRequest, 'dbId', -1);
                $id = Session::getUserID();
                $user = User::getUserById($id);
                $info = [
                    'title' => $title,
                    'completed' => $completed,
                    'guid' => $guid,
                    'priority' => $priority,
                    'dbId' => $dbId,
                    'userName' => $user['name'],
                    'email' => $user['email']
                ];

                $details = $this->saveTasks($dbId, $id, $info);
                if($details['status'] === 'success')
                {
                    $this->createMessageAttrib($info);
                    header('Content-type: application/json');
                    echo json_encode(['id' => $details['id'], 'success' => true]);
                }
                else
                {
                    header('HTTP/1.1 501 Internal Error');
                    echo json_encode($details);
                }

            }
        }


        public static function saveTasks(int $databaseID, int $userID, array $info)
        {
            if (!is_int($databaseID) && !is_int($userID) && !is_array($info)) {
                return null;
            }

            $database = Registry::get("Database");


            try {
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }
                $query = $database->query();

                $dbArr = [
                    "title" => $info['title'],
                    "completed" => ($info['completed'] == false) ? 0 : 1,
                    "guid" => $info['guid'],
                    "priority" => $info['priority'],
                    "userId" => $userID,
                ];

                if($databaseID >= 0)
                {
                    $resultID = $query->from("todos")
                        ->where('id = ?', $databaseID)
                        ->where('userId = ?', $userID)
                        ->save($dbArr);
                }
                else
                {
                    $resultID = $query->from("todos")
                        ->save($dbArr);
                }

                return ['id' => $resultID, 'status' => 'success'];

            } catch (Sql $e) {
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }


        public function postUpdate($item)
        {
            $header = new Header();
            if ($header->isAjax() && $item !== null) {
                $aRequest = $item;
                $title = ArrayMethods::array_get($aRequest,'title', -1);
                $completed = ArrayMethods::array_get($aRequest, 'completed', -1);
                $guid = ArrayMethods::array_get($aRequest, 'guid', -1);
                $priority = ArrayMethods::array_get($aRequest, 'priority', -1);
                $dbId = ArrayMethods::array_get($aRequest, 'dbId', -1);
                $id = Session::getUserID();
                $user = User::getUserById($id);
                $info = [
                    'title' => $title,
                    'completed' => $completed,
                    'guid' => $guid,
                    'priority' => $priority,
                    'dbId' => $dbId,
                    'userName' => $user['name'],
                    'email' => $user['email']
                ];

                $details = $this->updateTasks($dbId, $id, $info);

                if (is_array($details)) {
                    header('HTTP/1.1 501 Internal Error');
                    echo json_encode($details);
                }
                else
                {
                    header('Content-type: application/json');
                    echo json_encode(['success' => true, 'data' => $details]);
                }
            }
        }


        function getList()
        {
            error_log("getlist");
            if (Session::isUserLoggedIn() === null)
			{
                header('/login');
			}

            $id = Session::getUserId();
            $todos = $this->getTodosByID($id);
            error_log("step2");
            error_log(print_r($todos, true));
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
            error_log("step3");
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



            error_log("step4");

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




        public function updateTasks(int $databaseID, int $userID, array $info)
        {
            $resultID = [];
            if (!is_int($databaseID) && !is_int($userID) && !is_array($info)) {
                return null;
            }

            $database = Registry::get("Database");



            try {

                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }

                $dbArr = [
                    'title' => $info['title'],
                    'completed' => ($info['completed'] == false) ? 0 : 1,
                    'guid' => $info['guid'],
                    'priority' => $info['priority'],
                    'userId' => $userID,
                ];


                if ($database->_isValidService()) {
                    $query = $database->query();
                    $resultID = $query->from("todos")
                        ->where('id = ?', $databaseID)
                        ->where('userId = ?', $userID)
                        ->save($dbArr);
                }

                return $resultID;
            }
            catch (Sql $e) {
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }


    public function postDelete($item)
    {
        $header = new Header();
        if ($header->isAjax() && $item !== null) {
            $aRequest = $item;
            $dbId = ArrayMethods::array_get($aRequest, 'id', -1);
            $userID = Session::getUserID();

            $result = $this->deleteTask($dbId, $userID);



            if (is_array($result)) {
                header('HTTP/1.1 501 Internal Error');
                echo json_encode($result);
            } else
            {
                header('Content-type: application/json');
                echo json_encode(['success' => true, 'data' => $result]);
            }
        }
    }


        public static function deleteTask(int $databaseID, int $userID)
        {
           if ($databaseID == -1) {
                return ['status' => 'error'];
            }

            $database = Registry::get("Database");


            try {
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }

                    $query = $database->query();
                    $resultID = $query->from("todos")
                        ->where('id = ?', $databaseID)
                        ->where('userId = ?', $userID)
                        ->delete();

                error_log($resultID);
                return $resultID;
            }
            catch (Sql $e) {
                error_log("hit");
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }


        public function createMessageAttrib($details)
        {
            error_log("message sent");
//            $to =  ArrayMethods::array_get($details, 'email', "");
//            $subject = "New task has been added";
//            $name = ArrayMethods::array_get($details, 'userName', "");
//            $title = ArrayMethods::array_get($details, 'title', "");
//            $priority = ArrayMethods::array_get($details, 'priority', "");
//            $from = "system@test.com";
//            $mail = new TodoMail($to, $subject,$name,$title,$priority,$from);
//            $mail->createMessage();
        }
    }
}