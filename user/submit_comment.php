<?php
session_start();
include '../conn.php'; // Database connection
include '../function.php'; // Functions class

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['toast'] = ["status" => "error", "message" => "You must be logged in to comment."];
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$database = new conn();
$conn = $database->conn;
$function = new Functions();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $news_id = isset($_POST['news_id']) ? intval($_POST['news_id']) : 0;
    $user_id = $_SESSION['user_id'];
    $comment = trim($_POST['comment']);

    // Validate input
    if (empty($comment)) {
        $_SESSION['toast'] = ["status" => "error", "message" => "Comment cannot be empty."];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    try {
        // Insert comment into database using PDO
        $stmt = $conn->prepare("INSERT INTO comments (news_id, user_id, comment) VALUES (:news_id, :user_id, :comment)");
        $stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['toast'] = ["status" => "success", "message" => "Comment submitted successfully!"];
        } else {
            $_SESSION['toast'] = ["status" => "error", "message" => "Failed to submit comment."];
        }
    } catch (PDOException $e) {
        $_SESSION['toast'] = ["status" => "error", "message" => "Database error: " . $e->getMessage()];
    }
} else {
    $_SESSION['toast'] = ["status" => "error", "message" => "Invalid request."];
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
