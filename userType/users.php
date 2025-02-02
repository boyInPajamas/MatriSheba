<<?php
    session_start();
    include '../db_connection.php';

    $user_id = $_SESSION['user_id'];

    $query = "SELECT username FROM Users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);

    // Fetch detailed user information
    $completeQuery = "
        SELECT 
            c.fname AS fname, 
            c.lname AS lname, 
            c.age, 
            c.phone AS contact, 
            u.username 
        FROM completeInformation AS c 
        JOIN Users AS u 
        ON c.userID = u.id 
        WHERE u.id = '$user_id';
    ";

    $userDetails = mysqli_query($conn, $completeQuery);
    $userInfo = mysqli_fetch_assoc($userDetails);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = rtrim($row['username'], '@gmail.com');

        // Fetch and store user type
        $userTypeQuery = "SELECT userType FROM Users WHERE id = '$user_id'";
        $userTypeResult = mysqli_query($conn, $userTypeQuery);
        if ($userTypeResult && mysqli_num_rows($userTypeResult) === 1) {
            $userTypeRow = mysqli_fetch_assoc($userTypeResult);
            $_SESSION['user_type'] = $userTypeRow['userType'];
        }
    } else {
        // Redirect to login if user not found
        header("Location: ../log.php");
        exit();
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="../styles/user.css">
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <div class="navbar-item has-text-weight-bold is-size-4 mx-4">
                MATRISHEBA
            </div>
        </div>
        <div class="navbar-menu">
            <div class="navbar-start">
                <a href="" class="navbar-item">Home</a>
                <a href="../requests.php" class="navbar-item">Request</a>
                <a href="../community.php" class="navbar-item">Community</a>
            </div>
            <div class="navbar-end">
                <div class="navbar-item">
                    <button class="button" onclick="window.location.href='../logout.php'">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Section -->
    <main class="py-6 has-background-light">
        <div class="container">
            <div class="columns is-vcentered">
                <!-- Left Content -->
                <div class="column">
                    <h1 class="title is-2 has-text-black mb-3">
                        Welcome, <?php echo $_SESSION['username']; ?>!
                    </h1>
                    <p class="subtitle is-size-5 has-text-grey-dark mb-6">
                        We are excited to have you on board.
                    </p>
                    <div class="content is-size-5">
                        <p><strong>First Name:</strong> <?php echo htmlspecialchars($userInfo['fname']); ?></p>
                        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($userInfo['lname']); ?></p>
                        <p><strong>Age:</strong> <?php echo htmlspecialchars($userInfo['age']); ?></p>
                        <p><strong>Contact:</strong> <?php echo htmlspecialchars($userInfo['contact']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($userInfo['username']); ?></p>
                    </div>
                </div>

                <!-- Right: User Image -->
                <div class="column is-half">
                    <figure class="image is-square">
                        <img src="../img/user.jpg" alt="User Avatar" style="border-radius: 30px;">
                    </figure>
                </div>
            </div>
        </div>
    </main>

    <!-- Community Section -->
    <section id="community" class="py-6">
        <div class="container">
            <h2 class="title is-3 has-text-black mb-4">Join the Community</h2>
            <p class="subtitle is-size-5 has-text-grey-dark mb-6">
                Share your experiences, ask questions, and connect with others.
            </p>
            <a href="../community.php" class="button is-link">Go to Community Page</a>
        </div>
    </section>

    <!-- Requests Section -->
    <section id="requests" class="py-6">
        <div class="container">
            <div class="columns">
                <div class="column is-flex is-justify-content-end">
                    <div>
                        <h2 class="title is-3 has-text-black mb-4">Need Help?</h2>
                        <p class="subtitle is-size-5 has-text-grey-dark mb-6">
                            Request assistance, schedule consultations, or get emergency support.
                        </p>
                        <a href="../requests.php" class="button is-danger">Go to Requests</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nearest Hospital Section -->
    <section id="nearest-hospital" class="py-6">
        <div class="container">
            <h2 class="title is-3 has-text-black mb-4">Find Nearest Hospital</h2>
            <p class="subtitle is-size-5 has-text-grey-dark mb-6">
                Locate hospitals near you and check their service charges at the lowest cost.
            </p>
            <a href="../hospital-info.php" class="button is-success">Find Hospitals</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-6 has-border">
        <div class="container">
            <div class="columns is-vcentered">
                <div class="column is-half">
                    <h2 class="title is-6 has-text-black">MatriSheba</h2>
                    <p class="subtitle is-size-6 has-text-grey">
                        Empowering pregnant women in rural areas with access to essential care, resources, and support.
                    </p>
                </div>
                <div class="column is-half is-flex is-justify-content-end">
                    <div class="is-flex is-gap-4">
                        <a href="#" class="has-text-grey has-text-weight-semibold">Facebook</a>
                        <a href="#" class="has-text-grey has-text-weight-semibold">Instagram</a>
                        <a href="#" class="has-text-grey has-text-weight-semibold">Twitter</a>
                    </div>
                </div>
            </div>
            <div class="content has-text-centered is-size-7 has-text-grey mt-2">
                © 2025 MatriSheba. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
