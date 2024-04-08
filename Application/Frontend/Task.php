<?php


namespace Application\Frontend
{
    use Application\UI as UI;

    class Task extends UI
    {

        public function __construct()
        {
            $this->Display();
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
            $this->start_html();
            $this->defaultHeader();
            $this->startBody();
            $this->printNav();
            $this->printSection();
            $this->printFooter();
            $this->endBody();
            $this->end_html();


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

    $task = new Task();
}





