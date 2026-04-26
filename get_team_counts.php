<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'qvcakmyd_stmi_trust';
$username = 'qvcakmyd_admin2';
$password = 'StmiTrust2026!!';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => $conn->connect_error]);
    exit;
}

$depts = ['leadership', 'operations', 'volunteers', 'board'];
$counts = ['all' => 0];

foreach ($depts as $dept) {
    $result = $conn->query("SELECT COUNT(*) as cnt FROM teams WHERE department = '$dept' AND status = 'active'");
    if ($result) {
        $row = $result->fetch_assoc();
        $counts[$dept] = (int)$row['cnt'];
        $counts['all'] += $counts[$dept];
        $result->free();
    } else {
        $counts[$dept] = 0;
    }
}

$conn->close();

echo json_encode(['success' => true, 'counts' => $counts]);
?>