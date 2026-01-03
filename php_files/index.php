<?php
session_start();
require "connections.php";
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Valar Login</title>

<link rel="icon" href="images/retro.jpg" type="image/x-icon">
<link rel="stylesheet" href="../css/login.css">

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>

<div class="container" id="container">

    <!-- SIGN UP -->
    <div class="form-container sign-up-container">
        <form id="registerForm">
            <h1>Sign Up</h1>

            <input type="text" name="username" placeholder="Username" maxlength="20" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <p id="registerMessage" class="form-message"></p>

            <input type="submit" class="button" value="Sign Up">

            <p>Already have an account?
                <a href="#" id="Showlogin">Login</a>
            </p>
        </form>
    </div>

    <!-- SIGN IN -->
    <div class="form-container sign-in-container">
        <form id="loginForm">
            <h1>Login</h1>

            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <input type="submit" class="button" value="Sign In">

            <p>Don’t have an account?
                <a href="#" id="Showregister">Register</a>
            </p>
        </form>
    </div>

    <!-- OVERLAY -->
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Hello!</h1>
                <p>Enter your details to login</p>
                <button class="ghost" id="signIn" type="button">Sign In</button>
            </div>

            <div class="overlay-panel overlay-right">
                <h1>Welcome!</h1>
                <p>Create an account to get started</p>
                <button class="ghost" id="signUp" type="button">Sign Up</button>
            </div>
        </div>
    </div>

</div>

<script src="../scripts/Formsubmits.js"></script>

</body>
</html>

<?php $conn->close(); ?>
