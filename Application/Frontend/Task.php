<?php


namespace Application\Frontend
{
    if(session_id() === "") session_start();
    use Application\UI as UI;
    use Main\Session as Session;

    class Task extends UI
    {
        protected $authUser;

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
            $auth = Session::getAuth();
            if($auth !== null && !$auth)
            {
                $name = ArrayMethods::array_get($auth, 'name',"");
            }
            else
                $name = "";

            $html = <<<EOF
<nav class="header">
    <div class="logo">TaskManager111112222222</div>
    <div class="header-right">
        <div class="username" style="font-size: 1rem;">{$auth['name']}</div>
        <a class="active" href="dashboard.php?cmd=profile&id=30"">Profile</a>
        <form id="logout-form" action="logout.php" method="POST" style="display: none;">
            {{ csrf_field() }}
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
            $this->printSection();
            $this->printFooter();
            $this->endBody();
            $this->includeJS();
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
 <link rel="stylesheet" href="assets/css/task.css?v={CURRENT_TIMESTAMP}" charset="utf-8">
EOF;
            echo $html;

        }

    }
}





