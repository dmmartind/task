<?php


namespace Application\Frontend
{
    if(session_id() === "") session_start();
    use Main\Registry as Registry;
    use Main\Session as Session;
    use Main\Header as Header;
    use Main\Database\Exception\Sql as Sql;

    /**
     * Class Todo
     * @package Application\Frontend
     */
    class Todo
    {

        /**
         * postAdd
         * Desc: extracts the data from the post request to create a new task in the DB and returns the id in success and
         * error in response.
         * @param Request $request
         * @return array
         */
        public function postAdd($item)
        {
            $header = new Header();
            if ($header->isAjax()) {
                $aRequest = $item;
                $title = $aRequest['title'];
                $completed = $aRequest['completed'];
                $guid = $aRequest['guid'];
                $priority = $aRequest['priority'];
                $dbId = $aRequest['dbId'];
                $id = Session::getUserID();
                $user = Todo::getUserById($id);
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
                echo print_r($details, true);

//                if ($details['status'] == 'error') {
//                    return ['status' => 'error'];
//                } else {
//                    $this->enqueue($details);
//                    return ['id' => $details['id'], 'status' => 'success'];
//                }

            }
        }

        /**
         * saveTasks
         * DESC: creats a new task, updates a task if the id returns that it exists
         * @param int $databaseID
         * @param int $userID
         * @param array $info
         * @return array|null
         */
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

        /**
         * postUpdate
         * Desc: extracts the data from the post request to update task in the DB and returns the id in success and
         * error in response.
         * @param Request $request
         * @return array
         */
        public function postUpdate($item)
        {
            $header = new Header();
            if ($header->isAjax()) {
                $aRequest = $item;
                $title = $aRequest['title'];
                $completed = $aRequest['completed'];
                $guid = $aRequest['guid'];
                $priority = $aRequest['priority'];
                $dbId = $aRequest['dbId'];
                $id = Session::getUserID();
                $user = $this->getUserById($id);
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

        /**
         * getList
         * Desc: returns a user's task list
         * @return array
         */
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
                        ->order("id", "desc")
                        ->all();
                    return $query;

                    //return $query;
                } catch (QueryException $e) {
                    return null;
                }
            }

        }



        /**
         * updateTasks
         * DESC: updates a task edit
         * @param int $databaseID
         * @param int $userID
         * @param array $info
         * @return array|null
         */
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

        /**
     * postDelete
     * Desc: Deletes the task based on task id and user id given and returns success or error
     * @param Request $request
     * @return array
     */
    public function postDelete($item)
    {
        $header = new Header();
        if ($header->isAjax()) {
            $aRequest = $item;
            $dbId = $aRequest['id'];
            $userID = Session::getUserID();

            $result = $this->deleteTask($dbId, $userID);

            if (!$result) {
                return ['status' => 'error'];
            } else
                return ['status' => 'success'];
        }
    }

        /**
         * deleteTask
         * DESC: deletes a task
         * @param int $databaseID
         * @param int $userID
         * @return array
         */
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

        /**
         * enqueue
         * sets email job to the queue
         * @param $details
         */
        public function enqueue($details)
        {

        }
    }
}