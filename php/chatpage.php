<?php
$conn = new mysqli("localhost", "root", "", "forum_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$thread_id = $_GET['id'] ?? 1;

// Fetch main thread
$stmt = $conn->prepare("SELECT * FROM threads WHERE id=?");
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$thread_result = $stmt->get_result();
$thread = $thread_result->fetch_assoc();

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO comments (thread_id, username, comment, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $thread_id, $username, $comment);
    $stmt->execute();

    header("Location: chatpage.php?id=" . $thread_id);
    exit();
}

// Fetch comments
$stmt = $conn->prepare("SELECT * FROM comments WHERE thread_id=? ORDER BY created_at DESC");
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$comments_result = $stmt->get_result();
?>