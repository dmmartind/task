<?php


namespace Application\Frontend
{
    use Main\Registry as Registry;
    use Main\Session as Session;
    use Main\Header as Header;
    class Todo
    {
        /**
         * postUpdate
         * Desc: extracts the data from the post request to update task in the DB and returns the id in success and
         * error in response.
         * @param Request $request
         * @return array
         */
        public function postUpdate($item)
        {
            //echo print_r(getallheaders(), true);
            $header = new Header();
            if ($header->isAjax()) {
                //echo "found";
                $aRequest = $item;
                echo print_r($aRequest, true);
                $title = $aRequest['title'];
                $completed = $aRequest['completed'];
                $guid = $aRequest['guid'];
                $priority = $aRequest['priority'];
                $dbId = $aRequest['dbId'];
                $id = Session::userID();
                $user = $this->getUserById();

        }

        /**
         * getList
         * Desc: returns a user's task list
         * @return array
         */
        function getList()
        {
            if (Session::isUserLoggedIn() === null)
                return header('/login');

            $id = Session::getUserId();
            $todos = $this->getTodosByID($id);
            if ($todos === null)
                return [];
            $result = [];
            //echo print_r($todos, true);
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
         * @return |null
         */
        public static function getTodosByID(int $id)
        {
            if (!is_int($id))
                return null;
            $database = Registry::get("Database");
            $database = $database->connect();
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

        public function getUserById(int $id)
        {
            $database = Registry::get("Database");
            $database = $database->connect();
            if ($database->_isValidService()) {
                $query = $database->query()
                    ->from("users")
                    ->where("id = ?", "{$id}")
                    ->first();
                echo $query;
            }


        }

    }

}


