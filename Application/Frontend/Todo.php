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

                }
                echo print_r($details, true);

//                if ($details['status'] == 'error') {
//                    return ['status' => 'error'];
//                } else {
//                    $this->enqueue($details);
//                    return ['id' => $details['id'], 'status' => 'success'];
//                }

            }
        }


        public static function saveTasks(int $databaseID, int $userID, array $info)
        {
            if (!is_int($databaseID) && !is_int($userID) && !is_array($info)) {
                return null;
            }

            $database = Registry::get("Database");
            if (!$database->_isValidService()) {
                $database = $database->connect();
            }

            try {
                if ($database->_isValidService()) {
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
                }
            } catch (Sql $e) {
                return ['status' => $e->getMessage()];
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
                echo json_encode($details);

            }





        }


        function getList()
        {
            if (Session::isUserLoggedIn() === null)
			{
                header('/login');
			}

            $id = Session::getUserId();
            $todos = $this->getTodosByID($id);
            if ($todos === null)
                return [];
            $result = [];

            foreach ($todos as $info) {
                $id = $info['id'];
                $title = $info['title'];
                $completed = $info['completed'];
                $guid = $info['guid'];
                $priority = $info['priority'];
                $dbId = $info['id'];
                $userId = $info['userID'];
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




        public function updateTasks(int $databaseID, int $userID, array $info)
        {
            $resultID = [];
            if (!is_int($databaseID) && !is_int($userID) && !is_array($info)) {
                return null;
            }

            $database = Registry::get("Database");
            if (!$database->_isValidService()) {
                $database = $database->connect();
            }


            try {
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
                return ['status' => $e->getMessage()];
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

            if (!$result) {
                return ['status' => 'error'];
            } else
                return ['status' => 'success'];
        }
    }


        public static function deleteTask(int $databaseID, int $userID)
        {
           if ($databaseID == -1) {
                return ['status' => 'error'];
            }

            $database = Registry::get("Database");
            if (!$database->_isValidService()) {
                $database = $database->connect();
            }

            try {
                if ($database->_isValidService()) {
                    $query = $database->query();
                    $resultID = $query->from("todos")
                        ->where('id = ?', $databaseID)
                        ->where('userId = ?', $userID)
                        ->delete();
                }

                return $resultID;
            }
            catch (Sql $e) {
                return ['status' => $e->getMessage()];
            }
        }


        public function createMessageAttrib($details)
        {
            $to =  ArrayMethods::array_get($details, 'email', "");
            $subject = "New task has been added";
            $name = ArrayMethods::array_get($details, 'userName', "");
            $title = ArrayMethods::array_get($details, 'title', "");
            $priority = ArrayMethods::array_get($details, 'priority', "");
            $from = "system@test.com";
            $mail = new TodoMail($to, $subject,$name,$title,$priority,$from);
            $mail->createMessage();

            //


            //$mail->sendEmail();
        }
    }
}