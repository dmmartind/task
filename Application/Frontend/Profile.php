<?php


namespace Application\Frontend {

    use Application\UI as UI;
    use Main\ArrayMethods as ArrayMethods;

    class Profile extends UI
    {
        public function __construct()
        {
            //$this->Display();
        }

        public function Header()
        {
            $html = <<<EOF

    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Untitled333333333333</title>
EOF;
            echo $html;
        }

        public function printNav()
        {

            $auth = [];
            $auth['name'] = "Admin";

            $auth = AdminTodo::getUserById(ArrayMethods::array_get($_GET, 'id', ""));
            $html = <<<EOF
<nav class="header">
        <div class="logo">TaskManager</div>
        <div class="header-right">
            <div class="username" style="font-size: 1rem;">{$auth['name']}</div>
            <a class="" href="dashboard.php">List</a>
            <a class="active" href="dashboard.php?cmd=profile&id=30">Profile</a>
            <form id="logout-form" action="logout.php" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            <a class="#contact" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

EOF;
            echo $html;
        }

        public function printFooter()
        {
            $html = <<<EOF

EOF;
            echo $html;
        }

        public function printContent()
        {
            $auth = [];
            $auth['name'] = "Admin";

            $auth = AdminTodo::getUserById(ArrayMethods::array_get($_GET, 'id', ""));
            $html = <<<EOF
<div class="container">
        <div class="row">
            <div class="card1">
                <div class="card1-body">
                    <h4>Contact details</h4>
                    <form class="form-1" method="post" action="dashboard.php">
                        <div class="field1">
                            <label>Name</label>
                            <input name=name type="text" class="form-control" value={$auth['name']} placeholder="" aria-label="First name">
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

        public function Display()
        {
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

        public function includeJS()
        {
            $html = <<<EOF

EOF;

            echo $html;
        }

        public function includeCSS()
        {
            $html = <<<EOF
<link rel="stylesheet" href="assets\css\profile.css">
EOF;
            echo $html;
        }

    }
}


