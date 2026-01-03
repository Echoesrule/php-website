<?php
session_start();
require "connections.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$userInput = strtolower(trim($_POST['username'] ?? ''));
$password  = trim($_POST['password'] ?? '');

if (empty($userInput) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

// Fetch user by username or email
$stmt = $conn->prepare("
    SELECT id, username, email, password, is_admin
    FROM Clients
    WHERE LOWER(username) = ? OR LOWER(email) = ?
    LIMIT 1
");

$stmt->bind_param("ss", $userInput, $userInput);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'is_admin' => $user['is_admin']   // important!
        ];

        echo json_encode(['success' => true]);
        exit;
    }
}

// login failed
echo json_encode(['success' => false, 'error' => 'Invalid username/email or password']);
exit;
