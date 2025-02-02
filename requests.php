<?php
session_start();
include "db_connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: log.php");
    exit;
}

// Fetch doctors' information
$query = "SELECT c.userID, CONCAT(c.fname, ' ', c.lname) AS full_name, u.username AS email, c.phone 
          FROM completeInformation AS c 
          JOIN Users AS u ON c.userID = u.id 
          WHERE u.userType = 'doctors'";

$result = mysqli_query($conn, $query);

$doctors = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }
} else {
    $doctors = [];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma/css/bulma.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f7f7f7; /* Lighter grey background for contrast */
        }
        .container {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1rem;
        }
        .doctor-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .doctor-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
        }
        .doctor-card button {
            margin-top: 1rem;
        }
        .doctor-grid .doctor-card {
            flex: 1 1 calc(33.333% - 1rem);
        }

        .hero
        {
            background-color: rgb(221, 209, 209);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title has-text-centered">Request Section</h1>

        <section class="hero is-medium my-3 is-flex is-flex-direction-column is-align-items-center has-text-centered">
            <div class="hero-body">
                <p class="title py-5">Doctors List</p>
                <p class="subtitle">Want to schedule a meeting with the doctor? Select from the list and book your meeting</p>
            </div>
        </section>

        <!-- Doctors Grid -->
        <div class="doctor-grid">
            <?php if (empty($doctors)): ?>
                <div class="notification is-warning is-light">No doctors available at the moment.</div>
            <?php else: ?>
                <?php foreach ($doctors as $doctor): ?>
                    <div class="doctor-card">
                        <h3 class="subtitle"><?= htmlspecialchars($doctor['full_name']) ?></h3>
                        <p><strong>Email:</strong> <?= htmlspecialchars($doctor['email']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($doctor['phone']) ?></p>

                        <!-- Select Button that redirects to a new page with the doctor ID -->
                        <div class="control py-3">
                            <a href="doctor_info.php?doctor_id=<?= $doctor['userID'] ?>" class="button is-primary is-fullwidth">Select</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <section class="section">
            <div class="buttons is-centered">
                <a href="userType/<?php echo $_SESSION['user_type']; ?>.php" class="button is-dark">Return to Dashboard</a>
            </div>
        </section>
    </div>
</body>
</html>
