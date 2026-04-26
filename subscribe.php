<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'stmitrust2026';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?) ON DUPLICATE KEY UPDATE status = 'active', subscribed_at = CURRENT_TIMESTAMP");
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            $_SESSION['newsletter_message'] = 'Successfully subscribed to our newsletter!';
        } else {
            $_SESSION['newsletter_error'] = 'Subscription failed. Please try again.';
        }
        $stmt->close();
    } else {
        $_SESSION['newsletter_error'] = 'Please enter a valid email address.';
    }
}

$conn->close();
header('Location: contact.php');
exit();
?>