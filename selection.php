<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Navigation</title>
    <link rel="stylesheet" href="style.css">
    <style>body{background: url('./images/login-background.png') no-repeat center center fixed;!important}</style>
</head>
<body>
    <div class="menu-container">
        <h1>Welcome admin!</h1>
        <div class="menu-option">
            <a href="./dashboard.php">View Dashboards</a>
        </div>
        <div class="menu-option">
            <a href="./home.php">Manage Working Hours</a>
        </div>
    </div>
</body>
</html>
