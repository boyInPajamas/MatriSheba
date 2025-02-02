<?php
// Start session
session_start();

// Include database connection
include "../db_connection.php";

// Check if the volunteer is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../log.php"); // Redirect to login page if not logged in
    exit();
}

// Check if reply and postID are set in POST request
if (isset($_POST['reply']) && isset($_POST['postID'])) {
    $volunteer_id = $_SESSION['user_id']; // Logged-in volunteer's ID
    $reply = $conn->real_escape_string($_POST['reply']); // Sanitize reply content
    $post_id = intval($_POST['postID']); // Ensure postID is an integer

    // Update the Post table to add the reply
    $sql = "UPDATE Post 
            SET Replies = CASE 
                WHEN Replies IS NULL THEN ? 
                ELSE CONCAT(Replies, '\n', ?) 
            END 
            WHERE postID = ?";

    echo $sql;

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssi", $reply, $reply, $post_id);

        if ($stmt->execute()) {
            // Redirect back to the community section with a success message
            echo "<script>
                    alert('Reply added successfully!');
                    window.location.href = 'volunteer_portal.php#community';
                  </script>";
        } else {
            // Handle execution error
            echo "<script>
                    alert('Error adding reply. Please try again.');
                    window.location.href = 'volunteer_portal.php#community';
                  </script>";
        }

        $stmt->close();
    } else {
        // Handle preparation error
        echo "<script>
                alert('Failed to prepare the query. Please try again later.');
                window.location.href = 'volunteer_portal.php#community';
              </script>";
    }
} else {
    // Redirect back with an error message if required fields are missing
    echo "<script>
            alert('Invalid request. Please try again.');
            window.location.href = 'volunteer_portal.php#community';
          </script>";
}

// Close database connection
$conn->close();
?>
