<?php
session_start();
include "db_connection.php";

$hospitals = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        // Reset button clicked, no filtering
        $query = "SELECT * FROM Hospitals";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $hospitals = $result->fetch_all(MYSQLI_ASSOC);
        }
    } else {
        $budget = $_POST['budget'];
        $location = $_POST['location'] ?? ''; // Optional location filter
        $service = $_POST['service'] ?? ''; // Optional service filter

        $query = "SELECT * FROM Hospitals WHERE cost <= ? AND location LIKE ? AND services LIKE ?";

        $stmt = $conn->prepare($query);

        $searchLocation = "%" . $location . "%";
        $searchService = "%" . $service . "%";

        $stmt->bind_param("dss", $budget, $searchLocation, $searchService); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $hospitals = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
} else {
    // Fetch all hospitals if no search is performed
    $query = "SELECT * FROM Hospitals";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $hospitals = $result->fetch_all(MYSQLI_ASSOC);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma/css/bulma.min.css">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
        }
        .hospital-container {
            max-width: 800px;
            margin: 2rem auto;
        }
        .hospital-card {
            margin-bottom: 1rem;
        }
        .control {
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <div class="container hospital-container">
        <h1 class="title has-text-centered">Hospital Information</h1>

        <form action="hospital-info.php" method="POST" class="field has-addons">
            <div class="control mx-3">
                <input type="number" name="budget" class="input" placeholder="Enter your budget (e.g., 300)">
            </div>
            <!-- <div class="control mr-3">
                <input type="text" name="location" class="input" placeholder="Enter location (optional)">
            </div>
            <div class="control mr-3">
                <input type="text" name="service" class="input" placeholder="Enter service name (optional)">
            </div> -->
            <div class="control">
                <button type="submit" class="button is-primary">Search Hospitals</button>
            </div>
            <div class="control ml-3">
                <button type="submit" name="reset" class="button is-danger">Reset</button>
            </div>
        </form>

        <div class="hospital-list">
            <?php if (empty($hospitals)): ?>
                <div class="notification is-warning">No hospitals found matching your criteria.</div>
            <?php else: ?>
                <?php foreach ($hospitals as $hospital): ?>
                    <div class="card hospital-card">
                        <header class="card-header">
                            <p class="card-header-title">
                                <?= htmlspecialchars($hospital['name']) ?>
                            </p>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <strong>Location:</strong> <?= htmlspecialchars($hospital['location']) ?><br>
                                <strong>Services:</strong> <?= htmlspecialchars($hospital['services']) ?><br>
                                <strong>Cost:</strong> Tk. <?= htmlspecialchars($hospital['cost']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="has-text-centered my-6">
            <a href="userType/<?= $_SESSION['user_type'] ?>.php" class="button is-dark">Back to Dashboard</a>
        </div>
    </div>

</body>
</html>