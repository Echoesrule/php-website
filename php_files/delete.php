<?php
session_start();
include("connections.php");

// Admin check
if (!isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] != 1) {
    http_response_code(403);
    exit("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Invalid request");
}

if (!isset($_POST['id'])) {
    exit("Missing product ID");
}

$id = (int)$_POST['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: Admin.php?msg=Product+deleted+successfully");
    exit;
}

die("Delete failed");
