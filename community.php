<?php

session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: log.php"); 
    exit;
}

$user_id = $_SESSION['user_id'];
$userTypeQuery = "SELECT userType FROM Users WHERE id = '$user_id'";
$userTypeResult = mysqli_query($conn, $userTypeQuery);

if ($userTypeResult && mysqli_num_rows($userTypeResult) === 1) {
    $userTypeRow = mysqli_fetch_assoc($userTypeResult);
    $_SESSION['user_type'] = $userTypeRow['userType'];
} else {
    echo "Error: User not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $userID = $_SESSION['user_id']; 

    $insertQuery = "INSERT INTO Post (content, userID) VALUES ('$content', '$userID')";

    if (mysqli_query($conn, $insertQuery)) {
        header("Location: community.php");
        exit;
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

$query = "SELECT posts.content, posts.createdAt, users.username, posts.reply 
          FROM Post AS posts
          JOIN Users AS users ON posts.userID = users.id
          ORDER BY posts.createdAt DESC";

$result = mysqli_query($conn, $query);

$posts = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
} else {
    $posts = [];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Posts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <style>
        body {
            background-color: #f6f7f8;
            background-image: url('img/background.jpg');
            background-size: fill;   
            /* background-position: center; 
            background-repeat: 3; */
        }

        .navbar
        {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 10px;
        }

        .form-container {
            margin-bottom: 2rem;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .post {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background-color: #fff;
        }

        .timestamp {
            font-size: 0.875rem;
            color: #666;
        }

        .reply {
            margin-top: 1.5rem;  /* Adds spacing between post content and reply section */
            padding: 1rem;  /* Adds padding inside the reply section */
            background-color: #f9f9f9;  /* Light background color for better visibility */
            border-radius: 8px;  /* Rounded corners */
            border: 1px solid #ddd;  /* Light border to separate the reply section */
        }

        .reply p {
            margin-bottom: 0.5rem;  /* Adds spacing between reply text */
        }

        .timestamp {
            margin-bottom: 1rem;  /* Adds space between timestamp and post content */
            font-size: 0.875rem;  /* Adjusts the font size to make it slightly smaller */
            color: #666;  /* Sets the color to a lighter gray for the timestamp */
        }

        .content
        {
            font-size: 1.25rem;
            padding-left: 5px;
        }

        footer
        {
            box-shadow: rgba(0, 0, 0, 0.1) 0px -4px 10px;
        }

    </style>
</head>
<body>
    <nav class="navbar is-light">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item title is-4 has-text-black">
                    MatriSheba Community
                </a>
            </div>
            <div class="navbar-menu">
                <div class="navbar-end">
                    <a href="userType/<?php echo $_SESSION['user_type']; ?>.php" class="navbar-item">Dashboard</a> 
                    <a href="logout.php" class="navbar-item">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="section">
        <div class="container">
            <div class="form-container">
                <h1 class="title is-3 has-text-centered">Create a New Post</h1>
                <form action="community.php" method="POST">
                    <div class="field">
                        <label class="label" for="content">Content</label>
                        <div class="control">
                            <textarea class="textarea" id="content" name="content" rows="5" placeholder="Write your post here..." required></textarea>
                        </div>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button class="button is-primary is-fullwidth">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

            <h2 class="title is-4">All Posts</h2>
            <?php if (empty($posts)): ?>
                <div class="notification is-warning">No posts available.</div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <p><strong>User:</strong> <?= htmlspecialchars($post['username']) ?></p>
                        <p class="timestamp"><strong>Posted at:</strong> <?= htmlspecialchars($post['createdAt']) ?></p>
                        <p class="content"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        
                        <div class="reply">
                            <strong>Reply: </strong>
                        <?php if (!empty($post['reply'])): ?>
                            <p><?= nl2br(htmlspecialchars($post['reply'])) ?></p>
                        <?php else: ?>
                            <p>No reply yet.</p>
                        <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <footer class="footer">
        <div class="content has-text-centered">
            <p class="is-size-6 has-text-grey">
                © 2025 MatriSheba Community. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>