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
                echo "found";

            }
            else
                echo "not found";
        }

        /**
         * getList
         * Desc: returns a user's task list
         * @return array
         */
        function getList()
        {
            echo "getList!!!";
            if (Session::isUserLoggedIn() === null)
                return header('/login');
            else
                echo"DONE!!!!!";
            $id = Session::getUserId();
            $todos = $this->getTodosByID($id);
            if ($todos === null)
                return [];

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

    }

}


