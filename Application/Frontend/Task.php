<?php
/**
 * ©2024 David Martin. All Rights Reserve.
 */

namespace Application\Frontend {

    if (session_id() === "") {
        session_start();
    }

    use Application\UI as UI;
    use Main\Session as Session;


    /**
     * Class Task
     * @package Application\Frontend
     */
    class Task extends UI
    {

        /**
         * @var
         */
        protected $authUser;


        /**
         * Task constructor.
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
            $this->printSection();
            $this->printFooter();
            $this->endBody();
            $this->includeJS();
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
    <title>My Todo List</title>
    <meta name="csrf-token" content="{$csrf}">
    
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
 <link rel="stylesheet" href="assets/css/task.css?v={CURRENT_TIMESTAMP}" charset="utf-8">
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
    <div class="logo">TaskManager</div>
    <div class="header-right">
        <div class="username" style="font-size: 1rem;">{$auth['name']}</div>
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
         * print the UI structure of the task list
         */
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

        /**
         *  print footer
         */
        public function printFooter()
        {
            $html = <<<EOF
<footer id=info>
    <p>To-List App by David Martin</p>
</footer>
EOF;
            echo $html;
        }

        /**
         * prints the required js
         * @return mixed|void
         */
        public function includeJS()
        {
            $html = <<<EOF
<script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/task.js"></script>
EOF;
            echo $html;
        }

    }
}





