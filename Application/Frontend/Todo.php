<?php

/**
 * ©2024 David Martin. All Rights Reserve.
 */
namespace Application\Frontend {

    if (session_id() === "") {
        session_start();
    }

    use Main\ArrayMethods;
    use Main\Registry as Registry;
    use Main\Session as Session;
    use Main\Header as Header;
    use Main\Database\Exception\Sql as Sql;
    use Main\User as User;


    /**
     * Class Todo
     * @package Application\Frontend
     */
    class Todo
    {
        /**
         * handles the ajax add_task command by extracting attributes from the request, places them in a n array,
         * calls savetaks to send to the db, and check the return to decide which json to return to the JS.
         * @param $item
         * @return json
         */
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
                if ($details['status'] === 'success') {
                    $this->createMessageAttrib($info);
                    echo json_encode(['id' => $details['id'], 'success' => true]);
                } else {
                    header('HTTP/1.1 501 Internal Error');
                    echo json_encode($details);
                }
            }
        }


        /**
         * takes the dbID, userID, and data array, checks the valid arguments, gets a db instance, connects the db,
         * builds and execute sql statement as an update if dbID exist and insert otherwise.
         * @param int $databaseID
         * @param int $userID
         * @param array $info
         * @return array|null with row id
         */
        public function saveTasks(int $databaseID, int $userID, array $info)
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

                if ($databaseID >= 0) {
                    $resultID = $query->from("todos")
                        ->where('id = ?', $databaseID)
                        ->where('userId = ?', $userID)
                        ->save($dbArr);
                } else {
                    $resultID = $query->from("todos")
                        ->save($dbArr);
                }

                return ['id' => $resultID, 'status' => 'success'];
            } catch (Sql $e) {
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }

        /**
         * extract the data from the array, creates a new instance of the TodoMail with that data, and call
         * createMessage.
         * @param $details
         */
        public function createMessageAttrib($details)
        {
            $to = ArrayMethods::array_get($details, 'email', "");
            $subject = "New task has been added";
            $name = ArrayMethods::array_get($details, 'userName', "");
            $title = ArrayMethods::array_get($details, 'title', "");
            $priority = ArrayMethods::array_get($details, 'priority', "");
            $from = "system@test.com";
            $mail = new TodoMail($to, $subject, $name, $title, $priority, $from);
            $mail->createMessage();
        }

        /**
         * Desc: extracts the data from the post request to update task in the DB and returns the id in success and
         * error in response.
         * @param $item
         */
        public function postUpdate($item)
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

                $details = $this->updateTasks($dbId, $id, $info);

                if (is_array($details)) {
                    header('HTTP/1.1 501 Internal Error');
                    echo json_encode($details);
                } else {
                    header('Content-type: application/json');
                    echo json_encode(['success' => true, 'data' => $details]);
                }
            }
        }

        /**
         * updateTasks
         * DESC: takes the dbID, userID, and data array, checks the valid arguments, gets a db instance, connects
         * the db, builds and executes sql statement as an update, nd returns the row id.
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
            } catch (Sql $e) {
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }

        /**
         * * getList
         * Desc: checks user auth, gets the tasks for the specified user, returns json with an empty array if their
         * was not tasks returned and if an error occured then it will return an error json. If the request had data,
         * it will build the array and return a json with the retrned tasks.
         * @return json
         */
        public function getList()
        {
            if (Session::isUserLoggedIn() === null) {
                header('/login');
            }

            $id = Session::getUserId();
            $todos = $this->getTodosByID($id);

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
         * getTodosByID
         * DESC: takes the userID checks the valid arguments, gets a db instance, connects the db,
         * builds and executes select statement.
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

        /**
         * postDelete
         * Desc: Deletes the task based on task id and user id given and returns success or error
         * @param $item
         * @return json
         */
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
                } else {
                    header('Content-type: application/json');
                    echo json_encode(['success' => true, 'data' => $result]);
                }
            }
        }

        /**
         * * takes the dbID and userID checks the valid arguments, gets a db instance, connects the db,
         * builds and execute sql statement to delete task row.
         * @param int $databaseID
         * @param int $userID
         * @return array|int
         */
        public function deleteTask(int $databaseID, int $userID)
        {
            if ($databaseID == -1 && $userID == -1) {
                return ['success' => false, 'error' => 'bad arg'];
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

                return $resultID;
            } catch (Sql $e) {
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }
    }
}