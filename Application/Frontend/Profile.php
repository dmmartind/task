<?php


namespace Application\Frontend {

    use Application\UI as UI;
    use Main\ArrayMethods as ArrayMethods;
    use Main\Session as Session;
    use Main\User as User;


    /**
     * Class Profile
     * @package Application\Frontend
     */
    class Profile extends UI
    {

        /**
         * Profile constructor.
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
            $this->printContent();
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{$csrf}">
    <title>TaskManager:Profile</title>
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
<link rel="stylesheet" href="assets\css\profile.css">
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
            $auth = User::getUserById(ArrayMethods::array_get($_GET, 'id', -1));
            if ($auth !== null && !$auth) {
                $name = ArrayMethods::array_get($auth, 'name', "");
            } else {
                $name = "";
            }
            $html = <<<EOF
<nav class="header">
        <div class="logo">TaskManager</div>
        <div class="header-right">
            <div class="username" style="font-size: 1rem;">{$name}</div>
            <a class="" href="dashboard.php">List</a>
            <a class="active" href="dashboard.php?cmd=profile">Profile</a>
            <form id="logout-form" action="logout.php" method="POST" style="display: none;">
                {$field}
            </form>
            <a class="#contact" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>

        </div>
    </nav>
EOF;
            echo $html;
        }

        /**
         *  prints the two cards one containing the profile name and email. The other contains inputs for old
         * and new passwords
         */
        public function printContent()
        {
            $auth = Session::getAuth();
            if ($auth !== null && !$auth) {
                $name = ArrayMethods::array_get($auth, 'name', "");
            } else {
                $name = "";
            }
            $html = <<<EOF
<div class="container">
        <div class="row">
            <div class="card1">
                <div class="card1-body">
                    <h4>Contact details</h4>
                    <form class="form-1" method="post" action="dashboard.php">
                        <div class="field1">
                            <label>Name</label>
                            <input name=name type="text" class="form-control" value={$auth['name']} placeholder=""
                             aria-label="First name">
                        </div>
                        <div class="field2">
                            <label>Email</label>
                            <input name=email type="email" value={$auth['email']} class="form-control" placeholder="">
                            <input name=cmd type="hidden" value="update_user" >
                        </div>
                        <div class="field3">
                            <button class="btn btn-light me-2" type="submit">Submit</button>
                        </div>
                    </form>

                </div>
            </div>


            <div class="card2">
                <div class="card2-body">
                    <h4>Change Password</h4>
                    <form class="form-2" method="post" action="dashboard.php">
                        <!-- Old password -->
                        <div class="field1">
                            <label for="exampleInputPassword1" class="form-label">Old password *</label>
                            <input type="password" class="form-control" name="old_password">
                        </div>
                        <!-- New password -->
                        <div class="field2">
                            <label for="exampleInputPassword2" class="form-label">New password *</label>
                            <input type="password" class="form-control" name="new_password">
                        </div>
                        <!-- Confirm password -->
                        <div class="field3">
                            <label for="exampleInputPassword3" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" name="confirm_password">
                            <input name=cmd type="hidden" value="update_password" >
                        </div>
                        <div class="field4">
                            <button class="btn btn-light me-2" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
EOF;
            echo $html;
        }

        /**
         * prints the required JS required for the page
         * @return mixed|void
         */
        public function includeJS()
        {
            $html = <<<EOF

EOF;

            echo $html;
        }

    }
}


