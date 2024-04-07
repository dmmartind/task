<?php
require('checkRegister.php'); //includes adding new staff script
?>

<!DOCTYPE html>
<html>
<head>
    <title>PC Solutions - Register</title>
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/login.css" />

    <script src="assets/js/modernizr.custom.63321.js"></script>

    <style>
        @import url(http://fonts.googleapis.com/css?family=Raleway:400,700);
    </style>
</head>

<body>
<div class="container">
    <div class="rect reg">
        <img src="assets/images/logo-pcs.jpg">
        <form class="form-4" method="POST" action="">
            <h1>Register / <span class="reg"><a href="index.php">Login</a></span></h1>
            <span id="error"><?php echo $error; ?></span>
            <span id="error"><?php echo $success; ?></span>

            <label for="email">email</label>
            <input type="text" name="email" placeholder="Email" required>
            <input type="text" name="name" placeholder="Name" required>
            <label for="password">password</label>
            <input type="password" name="password" placeholder="Password" required>
            <input id="confirm" type="password" name="confirm" placeholder="Repeat Password" required>
            <input type="submit" name="submit" value="Register">

        </form>

    </div>
</div>
</body>

</html>