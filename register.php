<?php
session_start();
include 'db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $userType = $_POST['uType'];

    // Prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);

    // Insert into Users table
    $query = "INSERT INTO Users (username, password, userType) VALUES ('$email', '$password', '$userType')";
    if (mysqli_query($conn, $query)) {
        $user_id = mysqli_insert_id($conn);

        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_type'] = $userType;

        header("Location: complete-profile.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="styles/reg.css">
</head>
<body class="is-flex is-justify-content-center is-align-items-center">
    <main>
        <div class="fixed-grid">
            <div class="grid">
                <div class="cell has-background-white" id="imgCell">
                    <img src="img/pexels-diva-30232762.jpg" id="actualImg">
                </div>
                <div class="cell">
                    <div class="leftInfo is-flex is-flex-direction-column is-justify-content-center is-align-items-left px-6">
                        <div class="title is-1 mb-3">Register</div>
                        <div class="subtitle">Enter to get access to your data & information.</div>

                        <form action="register.php" method="POST">
                            <div class="subtitle m-2 is-6 has-text-weight-bold">Email</div>
                            <input type="email" name="email" class="input mb-3" placeholder="Enter your email address" required>

                            <div class="subtitle m-2 is-6 has-text-weight-bold">Password</div>
                            <input type="password" name="password" class="input mb-4" id="inputPass" placeholder="Enter password" required>

                            <label class="label subtitle is-6 has-text-weight-bold">Do you want to register as:</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="uType" id="uType">
                                        <option value="users" selected>User</option>
                                        <option value="doctors">Doctor</option>
                                        <option value="volunteers">Volunteers</option>
                                    </select>
                                </div>
                            </div>

                            <br>

                            <button type="submit" class="button py-3 my-3 signupbtn has-text-white">Sign up</button>
                        </form>

                        <div class="has-text-centered mt-4">
                            Already have an account? <a href="log.php" class="has-text-black-bis signup">Log in</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
