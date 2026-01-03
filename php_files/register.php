<?php
session_start();
require "connections.php";
header('Content-Type: application/json');
$response = ['success' => false, 'error' => 'Something went wrong'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strtolower(trim($_POST['username'] ?? ''));
    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        exit;
    }

    // check duplicates
    $check = $conn->prepare("SELECT id FROM Clients WHERE LOWER(username) = ? OR LOWER(email) = ? LIMIT 1");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'Username or email already exists']);
        exit;
    }

    // hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // insert user
    $stmt = $conn->prepare("INSERT INTO Clients (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $passwordHash);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Registration failed']);
    }
}

exit;
