<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzeria Management System - LOGIN</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    
</head>
<body>
    <div class="container">
        <div class="loginHeader">
            <h3>Pizzeria Management System</h3>
            <p></p>
        </div>
        <div class="loginBody">
            <form action="login.php" method="post">
            <div class="input-container">
                <label >Username</label>
                <input name="user"placeholder="username" type="text" />

            </div>
            <div class="input-container">
                <label >Password</label>
                <input name="pass" placeholder="password" type="password" />
 
            </div>
            <div class="login-button">
                <button>Login</button>
            </div>
            </form>

        </div>
    </div>
    <?php
    // PHP Code Block

    // Check if form data is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Fetch form data
        $username = $_POST['user'];
        $password = $_POST['pass'];

        // Database connection and logic
        $conn = new mysqli('localhost', 'root', '', 'pizzeria_db');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User authenticated
            header("Location: ./selection.php");
        } else {
            echo "<script type='text/javascript'>alert('Invalid username or password.');</script>";
            
            
        }

        $conn->close();
    }
?>
</body>
</html>