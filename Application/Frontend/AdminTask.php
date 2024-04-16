<?php


namespace Application\Frontend
{
    if(session_id() === "") session_start();
    use Application\UI as UI;
    use Main\ArrayMethods;

    class AdminTask extends UI
    {
        public function __construct()
        {
            //$this->Display();
        }

        public function Header()
        {
            $html = <<<EOF
<head>
    <title>My Todo List</title>
    <meta name="csrf-token" content="{Session::getCSRFToken()}">
    
EOF;
            echo $html;

        }

        public function printNav()
        {
            $html = <<<EOF
<nav class="header">
    <div class="logo">TaskManager</div>
    <div class="header-right">
        <div class="username" style="font-size: 1rem;">{{Auth::user()->name}}</div>
        <a class="active" onclick="profile.edit">Profile</a>
        <form id="logout-form" action="logout.php" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        <a class="#contact" href="logout.php"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>

    </div>
</nav>
EOF;
            echo $html;

        }

        public function printSection()
        {
            $html = <<<EOF
<section id="todoapp">
    <header id="header">
        <h1>My Todo List</h1>
        <input id="new-todo" placeholder="What needs to be done?" autofocus>
    </header>
    <section id="main">
        <input id="toggle-all" type="checkbox">
        <label for="toggle-all">Mark all as complete</label>

        <ul id="todo-list"></ul>
    </section>
    <footer id="footer">
    </footer>
</section>
EOF;
            echo $html;

        }

        public function printFooter()
        {
            $html = <<<EOF
<footer id=info>
    <p>To-List App by David Martin</p>
</footer>
EOF;
            echo $html;

        }

        public function Display()
        {
            $auth = AdminTodo::getUserById(ArrayMethods::array_get($_GET, 'id', ""));

           $html = <<<EOF
<html>

<head>
    <title>My Todo List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="assets/css/task.css?v={CURRENT_TIMESTAMP}">
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

<div class="subName"></div>
<section id="todoapp">
    <header id="header">
        <h1>My Todo List</h1>
        <input id="new-todo" placeholder="What needs to be done?" autofocus>
    </header>
    <section id="main">
        <input id="toggle-all" type="checkbox">
        <label for="toggle-all">Mark all as complete</label>

        <ul id="todo-list"></ul>
    </section>
    <footer id="footer">
    </footer>
</section>
<footer id=info>
    <p>To-List App by David Martin</p>
</footer>
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/adminTask.js"></script>
<div id="test"></div>
</body>

</html>


EOF;

           echo $html;



        }

        public function includeJS()
        {
            $html = <<<EOF
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/task.js"></script>
EOF;
            echo $html;
        }

        public function includeCSS()
        {
            $html = <<<EOF
<link rel="stylesheet" href='assets/css/task.css'>
EOF;
            echo $html;

        }

    }



}





