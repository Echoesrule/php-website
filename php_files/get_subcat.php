<?php
include("connections.php");
$category_id = (int)$_GET['category_id'];
$stmt = $conn->prepare("SELECT id, name FROM subcategories WHERE category_id = ? ORDER BY name ASC");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
echo json_encode($res);
