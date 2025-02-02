<?php
session_start();
include "db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: log.php");
    exit;
}

// Initialize success message
$success_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $age = (int)$_POST['age'];
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $weight = (float)$_POST['weight'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Insert into the database
    $query = "INSERT INTO completeInformation (userID, fname, lname, age, gender, weight, phone)
              VALUES ('$user_id', '$fname', '$lname', '$age', '$gender', '$weight', '$phone')";

    echo "$user_id <br>";
    echo $query;

    if (mysqli_query($conn, $query)) {
        $user_type = htmlspecialchars($_SESSION['user_type']);
        header("Location: userType/$user_type.php");
        exit;
    } else {
        $success_message = "An error occurred. Please try again later.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .form-container {
            margin-top: 4rem;
            padding: 2rem;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .submit-btn {
            width: 100%;
        }

        nav {
            box-shadow: rgba(209, 209, 209, 0.5) 0px 5px 20px;
        }

        .success-message {
            margin-bottom: 1rem;
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 0.75rem;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar is-light">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item title is-4 has-text-black">
                    MatriSheba
                </a>
            </div>
            <div class="navbar-menu">
                <div class="navbar-end">
                    <a href="logout.php" class="navbar-item">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Form Section -->
    <section class="section">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="form-container">
                        <h1 class="title is-3 has-text-centered has-text-black mb-2">Complete Your Information</h1>
                        <p class="subtitle is-6 has-text-centered has-text-grey-dark mt-4 mb-6">
                            Fill out your details to help us provide better recommendations and services.
                        </p>

                        <!-- Display Success Message -->
                        <?php if ($success_message): ?>
                            <div class="success-message">
                                <?= htmlspecialchars($success_message) ?>
                            </div>
                        <?php endif; ?>

                        <form action="complete-profile.php" method="POST">
                            <!-- First Name -->
                            <div class="field">
                                <label class="label">First Name</label>
                                <div class="control">
                                    <input class="input" type="text" name="fname" id="fname" placeholder="Enter your first name" required>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="field">
                                <label class="label">Last Name</label>
                                <div class="control">
                                    <input class="input" type="text" name="lname" id="lname" placeholder="Enter your last name" required>
                                </div>
                            </div>

                            <!-- Age -->
                            <div class="field">
                                <label class="label">Age</label>
                                <div class="control">
                                    <input class="input" type="number" name="age" id="age" placeholder="Enter your age" required>
                                </div>
                            </div>

                            <!-- Weight -->
                            <div class="field">
                                <label class="label">Weight (kg)</label>
                                <div class="control">
                                    <input class="input" type="number" step="0.1" name="weight" id="weight" placeholder="Enter your weight" required>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="field">
                                <label class="label">Phone Number</label>
                                <div class="control">
                                    <input class="input" type="tel" name="phone" id="phone" placeholder="Enter your phone number" required>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="field my-3">
                                <label class="label">Gender</label>
                                <div class="control">
                                    <div class="select">
                                        <select name="gender" id="gender" required>
                                            <option value="">Select your gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <!-- Submit Button -->
                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-primary submit-btn">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="content has-text-centered">
            <p class="is-size-6 has-text-grey">
                © 2025 MatriSheba. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>
