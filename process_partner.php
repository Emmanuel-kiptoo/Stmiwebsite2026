<?php
session_start();

$host = 'localhost';
$dbname = 'stmitrust2026';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $organization = htmlspecialchars(trim($_POST['organization'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $partnership_type = $_POST['partnership_type'] ?? '';
    $partnership_level = $_POST['partnership_level'] ?? '';
    $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    $stmt = $conn->prepare("INSERT INTO partnership_inquiries (name, organization, email, phone, partnership_type, partnership_level, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $organization, $email, $phone, $partnership_type, $partnership_level, $message);
    
    if ($stmt->execute()) {
        header("Location: partner.php?success=1");
    } else {
        header("Location: partner.php?error=1");
    }
    
    $stmt->close();
}

$conn->close();
?>