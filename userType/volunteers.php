<?php
// Start session to get the logged-in user's information
session_start();

// Include the database connection file
include "../db_connection.php";

// Check if the volunteer is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../log.php"); // Redirect to login page if not logged in
    exit();
}

$volunteer_id = $_SESSION['user_id']; // Get the logged-in volunteer's user ID

// Query to fetch volunteer information from the Users and completeInformation tables
$sql = "SELECT u.id AS userID, u.username AS email, ci.fname, ci.lname, ci.age, ci.phone AS phone
        FROM Users u
        JOIN completeInformation ci ON u.id = ci.userID
        WHERE u.id = $volunteer_id";
        
$result = $conn->query($sql);

// Check if the volunteer's data is found
if ($result->num_rows > 0) {
    // Fetch volunteer details
    $volunteer = $result->fetch_assoc();
} else {
    // If no data found, you can handle this case as needed
    echo "<script>alert('No volunteer information found.');</script>";
    exit();
}

// Close the database connection after fetching the data
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            background: #28a745;
            color: white;
            padding: 1.5rem 0;
            text-align: center;
        }

        nav {
            background: #333;
            color: white;
            display: flex;
            justify-content: center;
            padding: 1rem 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
            font-weight: bold;
        }

        nav a:hover {
            color: #28a745;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .section {
            background: white;
            margin-bottom: 2rem;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 1rem;
            color: #333;
        }

        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }

        input[type="text"], 
        textarea {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"], 
        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            margin-top: 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        input[type="submit"]:hover, 
        button:hover {
            background: #218838;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
        }

        .volunteer-info {
            display: flex;
            align-items: flex-start;
            gap: 2rem;
        }

        .volunteer-photo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .community-posts {
            margin-top: 2rem;
        }

        .post {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .post h3 {
            margin: 0 0 0.5rem;
        }

        .post p {
            margin: 0.5rem 0;
        }

        .reply-form {
            margin-top: 1rem;
        }

        .reply-form textarea {
            margin-bottom: 0.5rem;
        }

        .my-5
        {
            margin-bottom: 45px;
        }
    </style>
</head>
<body>

<header>
    <h1>Volunteer</h1>
    <p>Manage emergency requests and support women in need.</p>
</header>

<nav>
    <a href="#home">Home</a>
    <a href="#community">Community</a>
    <!-- <a href="#comments">Comments</a> -->
</nav>

<div class="container">
    <!-- Home Section -->
    <section id="home" class="section">
        <h2>Volunteer Information</h2>
        <div class="volunteer-info">
            <div class="volunteer-photo">
                <img src="../img/Dr._Proctor.jpg" alt="Volunteer Picture">
            </div>
            <div>
                <ul>
                    <li><strong>Volunteer ID:</strong> <?php echo $volunteer['userID']; ?></li>
                    <li><strong>Name:</strong> <?php echo $volunteer['fname'] . " " . $volunteer['lname']; ?></li>
                    <li><strong>Age:</strong> <?php echo $volunteer['age']; ?></li>
                    <li><strong>Phone Number:</strong> <?php echo $volunteer['phone']; ?></li>
                    <li><strong>Email:</strong> <?php echo $volunteer['email']; ?></li>
                </ul>
            </div>
        </div>
    </section>

    <button onclick="logout()" class="my-5">Logout</button>

    <!-- Community Section -->
    <section id="community" class="section">
        <h2>Community Posts</h2>

        <div class="community-posts">
            <?php
            // Query to fetch community posts
            include "../db_connection.php";
            $sql = "SELECT p.postID, p.content, p.createdAt, u.username 
                    FROM Post p 
                    JOIN Users u ON p.userID = u.id
                    ORDER BY p.createdAt DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Loop through each post
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='post'>
                            <h3>Post by: {$row['username']}</h3>
                            <p>{$row['content']}</p>
                            <p><em>Posted on: {$row['createdAt']}</em></p>
                            <form class='reply-form' method='POST' action='submit_reply.php'>
                                <label for='reply-{$row['postID']}'>Your Reply:</label>
                                <textarea id='reply-{$row['postID']}' name='reply' rows='2' required></textarea>
                                <input type='hidden' name='postID' value='{$row['postID']}'>
                                <input type='submit' value='Submit Reply'>
                            </form>
                          </div>";
                }
            } else {
                echo "<p>No community posts available.</p>";
            }

            $conn->close();
            ?>
        </div>
    </section>

    <!-- Comments Section -->
    <!-- <section id="comments" class="section">
        <h2>Comments</h2>
        <form method="POST" action="submit_comment.php">
            <label for="comment">Your Comment:</label>
            <textarea id="comment" name="comment" rows="4" required></textarea>
            <input type="submit" value="Submit Comment">
        </form>
    </section> -->
</div>

<footer>
    <p>Volunteer Portal &copy; 2025 | Designed to Serve</p>
</footer>

<script>
    function logout() {
        alert('Logging out...');
        window.location.href = '../logout.php'; // Redirect to the logout page
    }
</script>

</body>
</html>
