<?php
session_start();
include "db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: log.php");
    exit;
}

// Initialize success and error messages
$success_message = "";
$error_message = "";

// Get doctor ID from URL parameter
if (isset($_GET['doctor_id'])) {
    $doctor_id = mysqli_real_escape_string($conn, $_GET['doctor_id']);

    $query = "SELECT CONCAT(c.fname, ' ', c.lname) AS full_name, u.username AS email, c.phone 
              FROM completeInformation AS c 
              JOIN Users AS u ON c.userID = u.id 
              WHERE u.userType = 'doctors' AND c.userID = $doctor_id";


    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $doctor = mysqli_fetch_assoc($result);
    } else {
        $doctor = null;
    }
} else {
    header("Location: requests.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule'])) {
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $userID = $_SESSION['user_id']; // Assume user ID is stored in the session
    $docID = $doctor_id;

    $meeting_datetime = $date . ' ' . $time;

    $current_datetime = date('Y-m-d H:i:s');

    if ($meeting_datetime > $current_datetime) {
        $query = "INSERT INTO DoctorMeetings (meetingDate, meetingTime, userID, createdAt) 
              VALUES ('$date', '$time', $userID, NOW())";

        if (mysqli_query($conn, $query)) {
            $success_message = "Meeting scheduled successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Selected date and time must be in the future.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma/css/bulma.min.css">
    <style>
        .doctor-info {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 2rem auto;
        }
        .notification {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title has-text-centered py-6">Doctor Information</h1>

        <?php if ($doctor): ?>
            <div class="doctor-info">
                <h2 class="subtitle"><?= htmlspecialchars($doctor['full_name']) ?></h2>
                <p><strong>Email:</strong> <?= htmlspecialchars($doctor['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($doctor['phone']) ?></p>
            </div>
        <?php else: ?>
            <div class="notification is-warning is-light">Doctor not found.</div>
        <?php endif; ?>

        <!-- Scheduling Form -->
        <section class="section">
            <h2 class="subtitle has-text-centered">Schedule a Meeting</h2>

            <?php if ($success_message): ?>
                <div class="notification is-success">
                    <?= $success_message ?>
                </div>
            <?php elseif ($error_message): ?>
                <div class="notification is-danger">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>

            <form action="#" method="POST">
                <div class="field">
                    <label for="date" class="label">Date:</label>
                    <div class="control">
                        <input type="date" id="date" name="date" class="input" required>
                    </div>
                </div>

                <div class="field">
                    <label for="time" class="label">Time:</label>
                    <div class="control">
                        <input type="time" id="time" name="time" class="input" required>
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <button type="submit" name="schedule" class="button is-primary is-fullwidth">Schedule Meeting</button>
                    </div>
                </div>
            </form>
        </section>

        <section class="section">
            <div class="buttons is-centered">
                <a href="requests.php" class="button is-dark">Back to Doctor List</a>
            </div>
        </section>
    </div>
</body>
</html>
