<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);

    // Check if user exists
    $query = "SELECT id, username, userType FROM Users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_type'] = $row['userType'];

        // Redirect to user dashboard
        header("Location: userType/{$row['userType']}.php");
    } else {
        echo "<p>Invalid username or password.</p>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="styles/log.css">
    
</head>
<body class="is-flex is-justify-content-center is-align-items-center">
    <main>
        <div class="fixed-grid">
            <div class="grid">
                <div class="cell is-flex is-flex-direction-column is-justify-content-center is-align-items-left">
                    <div class="title is-1 mb-3">Welcome Back</div>
                    <div class="subtitle">Enter to get access to your data & information.</div>

                    <form action="log.php" method="POST">
                        <div class="subtitle m-2 is-6 has-text-weight-bold">Email</div>
                        <input type="text" name="username" class="input mb-3" placeholder="Enter your email address" required>

                        <div class="subtitle m-2 is-6 has-text-weight-bold">Password</div>
                        <input type="password" name="password" class="input mb-3" placeholder="Enter password" required>

                        <button type="submit" class="button py-3 my-3 has-text-white loginbtn">Log In</button>
                    </form>

                    <div class="has-text-centered mt-4">
                        Don't have an account? <a href="register.php" class="has-text-black-bis signup">Register here</a>
                    </div>
                </div>
                <div class="cell has-background-white" id="imgCell">
                    <img src="img/pexels-diva-30232760.jpg" alt="" id="actualImg">
                </div>
            </div>
        </div>
    </main>
</body>
</html>
