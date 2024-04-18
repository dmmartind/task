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


            $html = <<<EOF
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Untitled</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container py-4 py-xl-5">
        <div class="row gy-4">
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="mb-4 mt-0">Contact details</h4>
                                    <div class="col-md-6">
                                        <label>Name</label>
                                        <input type="text" class="form-control" placeholder="" aria-label="First name">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Email</label>
                                        <input type="email" class="form-control" placeholder="">
                                    </div>
                                    <div class="d-lg-flex justify-content-lg-end">
                                        <button class="btn btn-light me-2" type="button">Submit</button>
                                    </div>
                                
                    </div>
                </div>
            </div>
            <div class="col-xl-6" >
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="my-4">Change Password</h4>
								<!-- Old password -->
								<div class="col-md-6">
									<label for="exampleInputPassword1" class="form-label">Old password *</label>
									<input type="password" class="form-control" id="exampleInputPassword1">
								</div>
								<!-- New password -->
								<div class="col-md-6">
									<label for="exampleInputPassword2" class="form-label">New password *</label>
									<input type="password" class="form-control" id="exampleInputPassword2">
								</div>
								<!-- Confirm password -->
								<div class="col-md-6">
									<label for="exampleInputPassword3" class="form-label">Confirm Password *</label>
									<input type="password" class="form-control" id="exampleInputPassword3">
								</div>
                                <div class="d-lg-flex justify-content-lg-end">
                                    <button class="btn btn-light me-2" type="button">Submit</button>
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
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


