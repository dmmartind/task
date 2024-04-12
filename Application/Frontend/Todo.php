<?php


namespace Application\Frontend
{
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
                return redirect('/login');
            else
                echo"DONE!!!!!";
            $id = Session::getUserId();
 
        }

    }

}


