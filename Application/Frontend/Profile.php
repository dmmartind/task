<?php


namespace Application\Frontend
{
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

    
EOF;
            echo $html;

        }

        public function printNav()
        {
            $html = <<<EOF

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

        public function Display()
        {
            $auth = [];
            $auth['name'] = "Admin";

            $html = <<<EOF
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Untitled</title>
    <link rel="stylesheet" href="assets\css\profile.css">
    <link rel="stylesheet" href="assets/css/list.css?v={CURRENT_TIMESTAMP}">
    
</head>

<body>
<nav class="header">
    <div class="logo">TaskManager</div>
    <div class="header-right">
        <div class="username" style="font-size: 1rem;">{$auth['name']}</div>
        <a class="" href="dashboard.php">List</a>
        <a class="active" href="dashboard.php?cmd=profile&id=30">Profile</a>
        <form id="logout-form" action="logout.php" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        <a class="#contact" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>

    </div>
</nav>
    <div class="container">
        <div class="row">            
                <div class="card1">
                    <div class="card1-body">
                        <h4>Contact details</h4>
                                    <div class="field1">
                                        <label>Name</label>
                                        <input type="text" class="form-control" placeholder="" aria-label="First name">
                                    </div>
                                    <div class="field2">
                                        <label>Email</label>
                                        <input type="email" class="form-control" placeholder="">
                                    </div>
                                    <div class="field3">
                                        <button class="btn btn-light me-2" type="button">Submit</button>
                                    </div>
                                
                    </div>
                </div>
            
            
                <div class="card2">
                    <div class="card2-body">
                        <h4>Change Password</h4>
								<!-- Old password -->
								<div class="field1">
									<label for="exampleInputPassword1" class="form-label">Old password *</label>
									<input type="password" class="form-control" id="exampleInputPassword1">
								</div>
								<!-- New password -->
								<div class="field2">
									<label for="exampleInputPassword2" class="form-label">New password *</label>
									<input type="password" class="form-control" id="exampleInputPassword2">
								</div>
								<!-- Confirm password -->
								<div class="field3">
									<label for="exampleInputPassword3" class="form-label">Confirm Password *</label>
									<input type="password" class="form-control" id="exampleInputPassword3">
								</div>
                                <div class="field4">
                                    <button class="btn btn-light me-2" type="button">Submit</button>
                                </div>
                    </div>
                </div>
            
        </div>
    </div>
    
</body>

</html>



EOF;

            echo $html;



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

EOF;
            echo $html;

        }

    }
}


