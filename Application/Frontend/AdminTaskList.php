<?php


namespace Application\Frontend {
    if(session_id() === "") session_start();

    use Application\UI as UI;
    use Main\Session as Session;


    class AdminTaskList extends UI
    {


        public function __construct()
        {

        }


        public function printNav()
        {
            $csrf = Session::getCSRFToken();
            $field = $this->csrf_field($csrf);
            $auth = Session::getAuth();
            if($auth !== null && !$auth)
            {
                $name = ArrayMethods::array_get($auth, 'name',"");
            }
            else
                $name = "";

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



        public function Display()
        {
            if(!Session::getAuth())
            {
                header("refresh:0; url=logout.php");
            }

            if(!Session::isUserLoggedIn())
            {
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


        public function endTable()
        {
            $html = <<<EOF
</tbody>
</table>

EOF;
            echo $html;

        }


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


        public function includeJS()
        {
        }


        public function includeCSS()
        {
            $html = <<<EOF
<link rel="stylesheet" href="assets/css/list.css?v={CURRENT_TIMESTAMP}">

EOF;
            echo $html;


        }


    }

}


