<?php
// Database Connection
session_start();
include "../db_connection.php";

// Check if doctor is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../log.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

// Insert Patient Data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_name = $_POST['patient_name'];
    $patient_age = $_POST['patient_age'];
    $patient_weight = $_POST['patient_weight'];
    $patient_temp = $_POST['patient_temp'];
    $patient_contact = $_POST['patient_contact'];
    $patient_email = $_POST['patient_email'];
    $diagnosis = $_POST['diagnosis'];
    $prescription = $_POST['prescription'];

    // Generate a random password
    $random_password = bin2hex(random_bytes(16));

    $user_sql = "INSERT INTO Users (username, password, userType) VALUES ('$patient_email', '$random_password', 'users')";
    // echo $user_sql;

    $user_result = $conn->query($user_sql);

    if ($user_result === TRUE) {
        $user_id = $conn->insert_id; // Get the last inserted ID (patient ID)

        $name_parts = explode(" ", $patient_name);
        $fname = $name_parts[0]; // Assuming first name is the first word
        $lname = isset($name_parts[1]) ? $name_parts[1] : ""; // Assuming last name is the rest (if available)

        $info_sql = "INSERT INTO completeInformation (fname, lname, age, weight, phone, userID) 
                     VALUES ('$fname', '$lname', '$patient_age', '$patient_weight', '$patient_contact', '$user_id')";


        $info_result = $conn->query($info_sql);

        if ($info_result === TRUE) {
            $patient_sql = "INSERT INTO Patients ( weight, temperature, medical_history, user_id) 
                            VALUES ('$patient_weight', '$patient_temp', '$diagnosis',  '$user_id')";
            
            if ($conn->query($patient_sql) === TRUE) {
                echo "<script>alert('Patient data added successfully. Password: $random_password');</script>";
            } else {
                echo "<script>alert('Error inserting patient data: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error inserting complete information: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error inserting user data: " . $conn->error . "');</script>";
    }
}

// Fetch Doctor Details
$doctor_sql = "SELECT u.id AS id, u.username, c.fname AS fname, c.lname, c.age, c.phone 
        FROM Users u
        JOIN completeInformation c ON u.id = c.userID
        WHERE u.userType = 'doctors' AND u.id = $doctor_id";
$result = $conn->query($doctor_sql);

$doctor = $result->fetch_assoc();

// Fetch All Patients
$patient_sql = "
    SELECT 
        p.patient_id AS id, 
        ci.fname AS name, 
        ci.age, 
        ci.phone AS contact, 
        ci.weight, 
        p.temperature, 
        p.medical_history AS diagnosis, 
        p.allergies AS prescription, 
        p.medications
    FROM 
        Patients p
    JOIN 
        completeInformation ci ON p.user_id = ci.userID";
$patients_result = $conn->query($patient_sql);

// Fetch Doctor's Scheduled Meetings based on the logged-in doctor's userID
$meeting_sql = "
    SELECT 
        dm.meetingID, 
        dm.meetingDate, 
        dm.meetingTime, 
        dm.userID AS patientID, 
        dm.createdAt, 
        ci.fname, 
        ci.lname, 
        ci.age, 
        ci.weight, 
        ci.phone
    FROM 
        DoctorMeetings dm
    JOIN 
        completeInformation ci ON dm.userID = ci.userID
    JOIN 
        Users u ON dm.docID = u.id
    WHERE 
        u.id = (SELECT docID FROM Doctor WHERE userID = '$doctor_id')
    ORDER BY 
        dm.meetingDate, dm.meetingTime
";

// echo $meeting_sql;
$meetings_result = $conn->query($meeting_sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css">
</head>
<body>
<header class="hero is-primary">
    <div class="hero-body">
        <p class="title">Doctor Portal</p>
        <p class="subtitle">Manage your patients efficiently</p>

        <button class="button" onclick="window.location.href='../logout.php'">Logout</button>
    </div>
</header>

<div class="container mt-5">
    <!-- Doctor Information -->
    <section id="home" class="section">
        <h2 class="title is-4">Doctor Information</h2>
        <div class="box">
            <p><strong>ID:</strong> <?php echo $doctor['id']; ?></p>
            <p><strong>Name:</strong> <?php echo $doctor['fname'] . " " . $doctor['lname']; ?></p>
            <p><strong>Age:</strong> <?php echo $doctor['age']; ?></p>
            <p><strong>Phone Number:</strong> <?php echo $doctor['phone']; ?></p>
        </div>
    </section>

    <!-- Add Patient Information -->
    <section id="add-patient" class="section">
        <h2 class="title is-4">Add Patient Information</h2>
        <form method="POST" action="" class="box">
            <div class="field">
                <label class="label" for="patient_name">Patient Name</label>
                <div class="control">
                    <input class="input" type="text" id="patient_name" name="patient_name" required>
                </div>
            </div>

            <div class="field">
                <label class="label" for="patient_age">Patient Age</label>
                <div class="control">
                    <input class="input" type="number" id="patient_age" name="patient_age" required>
                </div>
            </div>

            <div class="field">
                <label class="label" for="patient_weight">Weight (kg)</label>
                <div class="control">
                    <input class="input" type="number" id="patient_weight" name="patient_weight" required>
                </div>
            </div>

            <div class="field">
                <label class="label" for="patient_temp">Temperature (°C)</label>
                <div class="control">
                    <input class="input" type="number" id="patient_temp" name="patient_temp" step="0.1" required>
                </div>
            </div>

            <div class="field">
                <label class="label" for="patient_contact">Contact Number</label>
                <div class="control">
                    <input class="input" type="text" id="patient_contact" name="patient_contact" required>
                </div>
            </div>

            <div class="field">
                <label class="label" for="patient_email">Email</label>
                <div class="control">
                    <input class="input" type="email" id="patient_email" name="patient_email" required>
                </div>
            </div>

            <div class="field">
                <label class="label" for="diagnosis">Diagnosis</label>
                <div class="control">
                    <textarea class="textarea" id="diagnosis" name="diagnosis" rows="3" required></textarea>
                </div>
            </div>

            <div class="field">
                <label class="label" for="prescription">Prescription</label>
                <div class="control">
                    <textarea class="textarea" id="prescription" name="prescription" rows="3" required></textarea>
                </div>
            </div>

            <div class="control">
                <button class="button is-primary" type="submit">Add Patient</button>
            </div>
        </form>
    </section>

    <!-- Patient List -->
    <section id="patients" class="section">
        <h2 class="title is-4">Patient List</h2>
        <table class="table is-fullwidth is-striped is-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Weight</th>
                    <th>Temperature</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Diagnosis</th>
                    <th>Allergies</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $patients_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['age']; ?></td>
                        <td><?php echo $row['weight']; ?></td>
                        <td><?php echo $row['temperature']; ?></td>
                        <td><?php echo $row['contact']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['medical_history']; ?></td>
                        <td><?php echo $row['prescription']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <section id="meetings" class="section">
    <h2 class="title is-4">Scheduled Meetings</h2>
    <table class="table is-fullwidth is-striped is-hoverable">
        <thead>
            <tr>
                <th>Meeting ID</th>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Weight</th>
                <th>Phone</th>
                <th>Meeting Date</th>
                <th>Meeting Time</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $meetings_result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['meetingID']; ?></td>
                    <td><?php echo $row['fname'] . " " . $row['lname']; ?></td>
                    <td><?php echo $row['age']; ?></td>
                    <td><?php echo $row['weight']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['meetingDate']; ?></td>
                    <td><?php echo $row['meetingTime']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

</div>

<footer class="footer">
    <div class="content has-text-centered">
        <p>Doctor Portal &copy; 2025</p>
    </div>
</footer>
</body>
</html>

<?php $conn->close(); ?>
