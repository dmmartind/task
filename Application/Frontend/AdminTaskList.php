<?php


namespace Application\Frontend {

    if (session_id() === "") {
        session_start();
    }

    use Application\UI as UI;
    use Main\Session as Session;


    /**
     * Class AdminTaskList
     * @package Application\Frontend
     */
    class AdminTaskList extends UI
    {


        /**
         * AdminTaskList constructor.
         */
        public function __construct()
        {
        }

        /**
         * Checks authentication and call all the parts of the UI to display the Task List of a specified user
         * @return mixed|void
         */
        public function Display()
        {
            if (!Session::getAuth()) {
                header("refresh:0; url=logout.php");
            }

            if (!Session::isUserLoggedIn()) {
                header("refresh:0; url=logout.php");
            }

            $this->start_html();
            $this->Header();
            $this->includeCSS();
            $this->endHeader();
            $this->startBody();
            $this->printNav();
            $this->beginTable();
            $this->generateTable();
            $this->endTable();
            $this->endBody();
            $this->end_html();
        }

        /**
         * prints the top part of an HTML header
         * @return mixed|void
         */
        public function Header()
        {
            $csrf = Session::getCSRFToken();
            $html = <<<EOF
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{$csrf}">
    <title>TaskManagement System: Admin List</title>
    
EOF;

            echo $html;
        }

        /**
         * prints the required css for the page
         * @return mixed|void
         */
        public function includeCSS()
        {
            $html = <<<EOF
<link rel="stylesheet" href="assets/css/list.css?v={CURRENT_TIMESTAMP}">

EOF;
            echo $html;
        }

        /**
         *  prints the top navigation div
         */
        public function printNav()
        {
            $csrf = Session::getCSRFToken();
            $field = $this->csrf_field($csrf);
            $auth = Session::getAuth();
            if ($auth !== null && !$auth) {
                $name = ArrayMethods::array_get($auth, 'name', "");
            } else {
                $name = "";
            }

            $html = <<<EOF
<nav class="header">
    <div class="logo">TaskManager::Admin</div>
    <div class="header-right">
        <div class="username" style="font-size: 1rem;">{$auth['name']}</div>
        <a class="" href="dashboard.php">List</a>
        <a class="active" href="dashboard.php?cmd=profile">Profile</a>
        <form id="logout-form" action="logout.php" method="POST" style="display: none;">
            {$field}
        </form>
        <a class="#contact" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
    </div>
</nav>
EOF;

            echo $html;
        }

        /**
         * prints the table header that will contain the list of users
         */
        public function beginTable()
        {
            $html = <<<EOF
<table>
    <thead>
    <tr>
        <th>User ID</th>
        <th>Name</th>
        <th>Task Count</th>
        <th>Link</th>

    </tr>
    </thead>
    <tbody>
EOF;

            echo $html;
        }

        /**
         * builds the table using the data from an array captured from the DB
         */
        public function generateTable()
        {
            $userList = AdminTodo::getUserList();
            foreach ($userList as $userID => $item) {
                echo "<tr>";
                echo "<td>{$userID}</td>";
                echo "<td>{$item['name']}</td>";
                echo "<td>{$item['taskCount']}</td>";
                echo "<td><a href=\"dashboard.php?id={$userID}\" class=\"btn btn-danger sm control danger\" title=\"TaskLink\"
                   id=\"taskLink\">User Task</a></td>";
                echo "</tr>";
            }
        }

        /**
         * prints the end of the table
         */
        public function endTable()
        {
            $html = <<<EOF
</tbody>
</table>

EOF;
            echo $html;
        }

        /**
         * prints the required JS required for the page
         * @return mixed|void
         */
        public function includeJS()
        {
        }


    }
}


