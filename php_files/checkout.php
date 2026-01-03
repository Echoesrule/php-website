<?php
session_start();
include("connections.php");

if (!isset($_SESSION['user']['id'])) {
    die(json_encode(['success' => false, 'message' => 'Please log in.']));
}

$user_id = (int)$_SESSION['user']['id'];

// Fetch cart items
$stmt = $conn->prepare("
    SELECT c.id AS cart_id, c.quantity, p.id AS product_id, p.title, p.price, p.stock
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();
$cart_items = $cart_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cart_items)) {
    echo json_encode(['success' => false, 'message' => 'Your cart is empty.']);
    exit;
}

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Get latest order_number for this user
$stmt = $conn->prepare("SELECT MAX(order_number) AS last_order FROM orders WHERE client_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$last_order_number = $result['last_order'] ?? 0;
$new_order_number = $last_order_number + 1;
$stmt->close();

// Insert into orders
$insert_order = $conn->prepare("INSERT INTO orders (client_id, total_price, order_number) VALUES (?, ?, ?)");
$insert_order->bind_param("idi", $user_id, $total_price, $new_order_number);
$insert_order->execute();
$order_id = $conn->insert_id;
$insert_order->close();

// Insert each cart item into ordered_items and deduct stock
foreach ($cart_items as $item) {
    // Insert into ordered_items
    $stmt = $conn->prepare("INSERT INTO ordered_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmt->execute();
    $stmt->close();

    // Deduct stock
    $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
    $stmt->execute();
    $stmt->close();
}

// Clear cart
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

echo json_encode([
    'success' => true,
    'message' => "Your order #$new_order_number has been placed successfully!"
]);
