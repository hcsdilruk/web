<?php
include 'db.php'; // Include database connection

$thread_id = $_GET['id'] ?? 1; // Get thread ID from URL or default to 1

// Get thread info
$thread_sql = "SELECT * FROM threads WHERE id=$thread_id";
$thread_result = $conn->query($thread_sql);
$thread = $thread_result->fetch_assoc();

// Handle new comment or reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $comment = $_POST['comment'];
    $parent_id = $_POST['parent_id'] ?? null;

    // Insert into comments table
    $stmt = $conn->prepare("INSERT INTO comments (thread_id, parent_id, username, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiss", $thread_id, $parent_id, $username, $comment);
    $stmt->execute();
}

// Fetch all comments and replies
$comments_sql = "SELECT * FROM comments WHERE thread_id=$thread_id ORDER BY created_at ASC";
$comments_result = $conn->query($comments_sql);

// Organize comments into parent and children
$comments = [];
while ($row = $comments_result->fetch_assoc()) {
    if ($row['parent_id'] == null) {
        $comments[$row['id']] = $row;
        $comments[$row['id']]['replies'] = [];
    } else {
        $comments[$row['parent_id']]['replies'][] = $row;
    }
}
?>

<!-- HTML starts here -->
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $thread['title']; ?></title>
    <style>
        /* Light pastel theme */
        body {
            font-family: Arial, sans-serif;
            background-color: #fff0f5; /* light pink */
            color: #333;
            padding: 20px;
        }
        .thread {
            background: #ffffff;
            padding: 15px;
            border: 2px solid #ffa07a; /* light orange border */
            border-radius: 8px;
        }
        .comment {
            background: #fff5e6; /* light orange/pink mix */
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
        }
        .reply {
            background: #fffaf0;
            margin-left: 20px;
            padding: 8px;
            border-left: 3px solid #ffa07a;
        }
        form {
            margin-top: 15px;
            background: #fceae8;
            padding: 10px;
            border-radius: 6px;
        }
        input, textarea {
            width: 95%;
            padding: 8px;
            margin: 5px 0;
        }
        button {
            background-color: #ffa07a;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff8c66;
        }
    </style>
</head>
<body>

<!-- Thread content -->
<div class="thread">
    <h2><?php echo $thread['title']; ?></h2>
    <p><?php echo $thread['content']; ?></p>
</div>

<h3>Comments</h3>

<!-- Loop through comments -->
<?php foreach ($comments as $comment): ?>
    <div class="comment">
        <b><?php echo htmlspecialchars($comment['username']); ?>:</b>
        <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
        <small><?php echo $comment['created_at']; ?></small>

        <!-- Reply form under comment -->
        <form method="POST">
            <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
            <input type="text" name="username" placeholder="Your name" required>
            <textarea name="comment" placeholder="Your reply" required></textarea>
            <button type="submit">Reply</button>
        </form>

        <!-- Show replies -->
        <?php foreach ($comment['replies'] as $reply): ?>
            <div class="reply">
                <b><?php echo htmlspecialchars($reply['username']); ?>:</b>
                <p><?php echo nl2br(htmlspecialchars($reply['comment'])); ?></p>
                <small><?php echo $reply['created_at']; ?></small>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<!-- New top-level comment form -->
<h3>Add a Comment</h3>
<form method="POST">
    <input type="hidden" name="parent_id" value="">
    <input type="text" name="username" placeholder="Your name" required>
    <textarea name="comment" placeholder="Your comment" required></textarea>
    <button type="submit">Post Comment</button>
</form>

</body>
</html>