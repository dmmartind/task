<?php


namespace Application\Frontend {
    if(session_id() === "") session_start();

    use Application\UI as UI;

    class AdminTaskList extends UI
    {

        public function __construct()
        {
           // $this->Display();

        }


        public function Display()
        {
            $auth = AdminTodo::getAuth();
            $html = <<<EOF
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/list.css?v={CURRENT_TIMESTAMP}">
</head>

<body>
<nav class="header">
    <div class="logo">TaskManager</div>
    <div class="header-right">
        <div class="username" style="font-size: 1rem;">{$auth['name']}</div>
        <a class="" href="dashboard.php">List</a>
        <a class="active" href="{{ route('profile.edit') }}">Profile</a>
        <form id="logout-form" action="logout.php" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        <a class="#contact" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>

    </div>
</nav>
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
            $this->test();
            $this->end();

        }

        public function test()
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

        public function end()
        {
            $html = <<<EOF
</tbody>
</table>

</body>

</html>
EOF;
            echo $html;

        }

        public function Header()
        {
        }

        public function includeJS()
        {
        }

        public function includeCSS()
        {

        }


    }

}


